<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use App\Models\TurniejStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TurniejController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $turnieje = Turniej::query()
            ->when($request->filled('nazwa'), function ($query) use ($request) {
                $query->where('nazwa', 'like', '%' . $request->input('nazwa') . '%');
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
            'turniej_statusy' => $turniej_statusy
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
            'status_id' => 'required|integer|exists:turniej_statusy,id',
            'opis' => 'nullable|string',
            'komunikat' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // max 2MB
        ]);

        // dodanie pliku z komunikatem
        if ($request->hasFile('komunikat')) {
            $file = $request->file('komunikat');
            
            // 1. Pobranie oryginalnej nazwy pliku wraz z rozszerzeniem
            $originalName = $file->getClientOriginalName();
            
            // 2. Opcjonalne oczyszczenie nazwy ze spacji i dziwnych znaków (zalecane)
            $safeName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // 3. Zapisanie pliku z własną nazwą w katalogu 'komunikaty' na dysku 'public'
            $filePath = $file->storeAs('komunikaty', $safeName, 'public');
            
            $validatedData['komunikat_path'] = $filePath;
        }

        // dodanie pozostałych danych autoamtycznie
        $validatedData['organizator_id'] = auth()->id();
        // $validatedData['status_id'] = 1; // domyślnie ustawiamy status na "Planowany"
        $validatedData['liczba_zawodnikow'] = 0; // na początku nie ma zawodników

        Turniej::create($validatedData);

        return redirect()->route('turnieje.index')->with('success', 'Turniej został utworzony pomyślnie.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turniej $turniej)
    {
        return view('turnieje.show', [
            'turniej' => $turniej
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
            'turniej_statusy' => $turniej_statusy
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
            'status_id' => 'required|integer|exists:turniej_statusy,id',
            'opis' => 'nullable|string',
        ]);

        if($request->hasFile('komunikat') && $turniej->komunikat_path) {
            // usunięcie starego pliku
            Storage::disk('public')->delete($turniej->komunikat_path);
        }

        // dodanie nowego pliku z komunikatem
        if ($request->hasFile('komunikat')) {
            $file = $request->file('komunikat');
            
            // 1. Pobranie oryginalnej nazwy pliku wraz z rozszerzeniem
            $originalName = $file->getClientOriginalName();
            
            // 2. Opcjonalne oczyszczenie nazwy ze spacji i dziwnych znaków (zalecane)
            $safeName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // 3. Zapisanie pliku z własną nazwą w katalogu 'komunikaty' na dysku 'public'
            $filePath = $file->storeAs('komunikaty', $safeName, 'public');
            
            $validatedData['komunikat_path'] = $filePath;
        }

        // dodanie pozostałych danych autoamtycznie
        // $validatedData['organizator_id'] = auth()->id();
        // $validatedData['status_id'] = 1; // domyślnie ustawiamy status na "Planowany"
        // $validatedData['liczba_zawodnikow'] = 0; // na początku nie ma zawodników

        Turniej::where('id', $turniej->id)->update($validatedData);

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
