<?php

namespace App\Http\Controllers;

use App\Enums\RodzajSzachow;
use App\Enums\RundaStatus;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TurniejController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $turnieje = Turniej::query()
            ->when($request->filled('nazwa'), function ($query) use ($request) {
                $query->where('nazwa', 'like', '%'.$request->input('nazwa').'%');
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->where('status_id', $request->input('status_id'));
            })
            ->orderBy('data_rozpoczecia', 'desc')
            ->get();

        $turniej_statusy = TurniejStatus::all();

        return view('turnieje.index', [
            'turnieje' => $turnieje,
            'turniej_statusy' => $turniej_statusy,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turniej_statusy = TurniejStatus::all();

        return view('turnieje.create', [
            'turniej_statusy' => $turniej_statusy,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dodanie danych z formularza
        $validatedData = $request->validate([
            'nazwa' => 'required|string|max:255',
            'miejsce' => 'required|string|max:255',
            'data_rozpoczecia' => 'required|date',
            'data_zakonczenia' => 'required|date|after_or_equal:data_rozpoczecia',
            'liczba_rund' => 'required|integer|min:1',
            'limit_zawodnikow' => 'required|integer|min:1',
            'tempo_gry' => 'required|string|max:50',
            'rodzaj_szachow' => ['required', Rule::enum(RodzajSzachow::class)],
            'status_id' => 'required|integer|exists:turniej_statusy,id',
            'limit_plec' => 'nullable|in:M,K',
            'limit_wiek' => 'nullable|integer|min:1',
            'limit_ranking_od' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $limitRankingDo = $request->input('limit_ranking_do');
                    if ($value !== null && $limitRankingDo !== null && (int) $value > (int) $limitRankingDo) {
                        $fail('Ranking minimalny nie może być większy od rankingu maksymalnego.');
                    }
                },
            ],
            'limit_ranking_do' => 'nullable|integer|min:0',
            'limit_klub' => 'required|boolean',
            'zgloszony_do_pzszach' => 'required|boolean',
            'zgloszony_do_fide' => 'required|boolean',
            'opis' => 'nullable|string',
            'komunikat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        unset($validatedData['komunikat']);

        if ($request->hasFile('komunikat')) {
            $file = $request->file('komunikat');
            $originalName = $file->getClientOriginalName();
            $safeName = Str::uuid().'_'.Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $filePath = $file->storeAs('komunikaty', $safeName, 'public');

            if ($filePath === false) {
                throw new \RuntimeException('Nie udało się zapisać komunikatu turnieju.');
            }

            $validatedData['komunikat_path'] = $filePath;
        }

        // dodanie pozostałych danych autoamtycznie
        $validatedData['organizator_id'] = auth()->id();
        // $validatedData['status_id'] = 1; // domyślnie ustawiamy status na "Planowany"
        $validatedData['liczba_zawodnikow'] = 0; // na początku nie ma zawodników

        $turniej = DB::transaction(function () use ($validatedData) {
            $turniej = Turniej::create($validatedData);

            for ($numer = 1; $numer <= $turniej->liczba_rund; $numer++) {
                $turniej->rundy()->create([
                    'numer' => $numer,
                    'status' => RundaStatus::NieRozpoczeta,
                ]);
            }

            return $turniej;
        });

        return redirect()->route('turnieje.index')->with('success', 'Turniej został utworzony pomyślnie.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turniej $turniej)
    {
        $zgloszenie = $turniej->zgloszenia()->where('zawodnik_id', auth()->id())->first();

        return view('turnieje.show', [
            'turniej' => $turniej,
            'zgloszenie' => $zgloszenie,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turniej $turniej)
    {
        $turniej_statusy = TurniejStatus::all();

        return view('turnieje.edit', [
            'turniej' => $turniej,
            'turniej_statusy' => $turniej_statusy,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turniej $turniej)
    {
        // pobranie nowych danych z formularza
        $validatedData = $request->validate([
            'nazwa' => 'required|string|max:255',
            'miejsce' => 'required|string|max:255',
            'data_rozpoczecia' => 'required|date',
            'data_zakonczenia' => 'required|date|after_or_equal:data_rozpoczecia',
            'liczba_rund' => 'required|integer|min:1',
            'limit_zawodnikow' => 'required|integer|min:1',
            'tempo_gry' => 'required|string|max:50',
            'rodzaj_szachow' => ['required', Rule::enum(RodzajSzachow::class)],
            'status_id' => 'required|integer|exists:turniej_statusy,id',
            'limit_plec' => 'nullable|in:M,K',
            'limit_wiek' => 'nullable|integer|min:1',
            'limit_ranking_od' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $limitRankingDo = $request->input('limit_ranking_do');
                    if ($value !== null && $limitRankingDo !== null && (int) $value > (int) $limitRankingDo) {
                        $fail('Ranking minimalny nie może być większy od rankingu maksymalnego.');
                    }
                },
            ],
            'limit_ranking_do' => 'nullable|integer|min:0',
            'limit_klub' => 'required|boolean',
            'zgloszony_do_pzszach' => 'required|boolean',
            'zgloszony_do_fide' => 'required|boolean',
            'opis' => 'nullable|string',
            'komunikat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $rundyDoUsuniecia = $turniej->rundy()
            ->where('numer', '>', $validatedData['liczba_rund'])
            ->withCount('partie')
            ->get();

        if ($rundyDoUsuniecia->contains(
            fn (Runda $runda) => $runda->status !== RundaStatus::NieRozpoczeta || $runda->partie_count > 0
        )) {
            return back()
                ->withErrors(['liczba_rund' => 'Nie można usunąć rundy, która została rozpoczęta lub ma wygenerowane partie.'])
                ->withInput();
        }

        unset($validatedData['komunikat']);

        if ($request->hasFile('komunikat')) {
            $file = $request->file('komunikat');
            $originalName = $file->getClientOriginalName();
            $safeName = Str::uuid().'_'.Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $filePath = $file->storeAs('komunikaty', $safeName, 'public');

            if ($filePath === false) {
                throw new \RuntimeException('Nie udało się zapisać komunikatu turnieju.');
            }

            $validatedData['komunikat_path'] = $filePath;

            if ($turniej->komunikat_path) {
                Storage::disk('public')->delete($turniej->komunikat_path);
            }
        }

        // dodanie pozostałych danych autoamtycznie
        // $validatedData['organizator_id'] = auth()->id();
        // $validatedData['status_id'] = 1; // domyślnie ustawiamy status na "Planowany"
        // $validatedData['liczba_zawodnikow'] = 0; // na początku nie ma zawodników

        DB::transaction(function () use ($turniej, $validatedData, $rundyDoUsuniecia) {
            $turniej->update($validatedData);

            foreach ($rundyDoUsuniecia as $runda) {
                $runda->delete();
            }

            for ($numer = 1; $numer <= $turniej->liczba_rund; $numer++) {
                $turniej->rundy()->firstOrCreate(
                    ['numer' => $numer],
                    ['status' => RundaStatus::NieRozpoczeta]
                );
            }
        });

        return redirect()->route('turnieje.show', $turniej)->with('success', 'Turniej został zaktualizowany pomyślnie.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turniej $turniej)
    {
        // usunięcie pliku z komunikatem, jeśli istnieje
        if ($turniej->komunikat_path) {
            Storage::disk('public')->delete($turniej->komunikat_path);
        }

        $turniej->delete();

        return redirect()->route('turnieje.index')->with('success', 'Turniej został usunięty pomyślnie.');
    }
}
