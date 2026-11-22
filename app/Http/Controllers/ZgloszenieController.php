<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use App\Models\TurniejZawodnik;
use App\Models\Zgloszenie;
use App\Models\ZgloszenieStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ZgloszenieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Turniej $turniej)
    {
        Gate::authorize('viewAny', [Zgloszenie::class, $turniej]);

        $request = request();
        $zgloszenia = $turniej->zgloszenia()->with(['zawodnik', 'status'])
            ->when($request->filled('nazwa'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('zawodnik', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->input('nazwa').'%');
                    });
                });
            })
            ->when($request->filled('status_id'), function ($query) use ($request) {
                $query->where('status_id', $request->input('status_id'));
            })
            ->orderBy('data_wyslania', 'desc')
            ->get();

        $zgloszenia_statusy = ZgloszenieStatus::all();

        return view('zgloszenia.index', [
            'turniej' => $turniej,
            'zgloszenia' => $zgloszenia,
            'zgloszenia_statusy' => $zgloszenia_statusy,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Turniej $turniej)
    {
        Gate::authorize('create', [Zgloszenie::class, $turniej]);

        // sprawdz czy zalogowany uzytkownik złożył zgłoszenie, które ma status wysłane lub zatwierdzone
        $zgloszenie = Zgloszenie::with('status')
            ->where('zawodnik_id', '=', auth()->id())
            ->where('turniej_id', '=', $turniej->id)
            ->orderBy('data_wyslania', 'desc')
            ->first();

        return view('zgloszenia.create', [
            'turniej' => $turniej,
            'zgloszenie' => $zgloszenie,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Turniej $turniej)
    {
        Gate::authorize('create', [Zgloszenie::class, $turniej]);

        $validatedData = $request->validate([
            'komentarz' => 'nullable|string',
        ]);

        $validatedData['zawodnik_id'] = auth()->id(); // dodanie obecnego użytkownika jako zawodnika
        $validatedData['status_id'] = ZgloszenieStatus::query()
            ->where('nazwa', 'wysłane')
            ->firstOrFail()
            ->getKey();
        $validatedData['data_wyslania'] = now(); // obecna data i czas
        $validatedData['turniej_id'] = $turniej->getKey();

        Zgloszenie::create($validatedData);

        return redirect()->route('turnieje.show', $turniej)->with('success', 'Zgłoszenie zostało dodane pomyślnie.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zgloszenie $zgloszenie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zgloszenie $zgloszenie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zgloszenie $zgloszenie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zgloszenie $zgloszenie)
    {
        //
    }

    public function approve(Turniej $turniej, Zgloszenie $zgloszenie)
    {
        Gate::authorize('update', [$zgloszenie, $turniej]);

        if ($turniej->liczba_zawodnikow >= $turniej->limit_zawodnikow) {
            return redirect()->back()->with('error', 'Limit zawodników dla tego turnieju został osiągnięty.');
        }

        $zgloszenie->status_id = ZgloszenieStatus::query()
            ->where('nazwa', 'zatwierdzone')
            ->firstOrFail()
            ->getKey();
        $zgloszenie->save();

        $turniej->liczba_zawodnikow += 1; // zwiększenie liczby zawodników w turnieju
        $turniej->save();

        TurniejZawodnik::create([
            'turniej_id' => $turniej->id,
            'zawodnik_id' => $zgloszenie->zawodnik_id,
            'punkty' => 0,
        ]);

        return redirect()->back()->with('success', 'Zgłoszenie zostało zatwierdzone.');
    }

    public function reject(Turniej $turniej, Zgloszenie $zgloszenie)
    {
        Gate::authorize('update', [$zgloszenie, $turniej]);

        $zgloszenie->status_id = ZgloszenieStatus::query()
            ->where('nazwa', 'odrzucone')
            ->firstOrFail()
            ->getKey();
        $zgloszenie->save();

        return redirect()->back()->with('success', 'Zgłoszenie zostało odrzucone.');
    }
}
