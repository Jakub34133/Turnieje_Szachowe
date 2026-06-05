<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use App\Models\TurniejStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TurniejController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnieje = Turniej::all();
        return view('turnieje.index', [
            'turnieje' => $turnieje
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turniej_statusy = TurniejStatus::all();
        return view('turnieje.create', [
            'statusy' => $turniej_statusy
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
        $validatedData['status_id'] = 1; // domyślnie ustawiamy status na "Planowany"
        $validatedData['liczba_zawodnikow'] = 0; // na początku nie ma zawodników

        Turniej::create($validatedData);

        return redirect()->route('turnieje.index')->with('success', 'Turniej został utworzony pomyślnie.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turniej $turniej)
    {
        $komunikatUrl = $turniej->komunikat_path ? asset('storage/' . $turniej->komunikat_path) : null;
        return view('turnieje.show', [
            'turniej' => $turniej
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turniej $turniej)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turniej $turniej)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turniej $turniej)
    {
        //
    }
}
