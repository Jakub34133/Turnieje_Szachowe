<?php

namespace App\Http\Controllers;

use App\Enums\RundaStatus;
use App\Enums\WynikPunktowyPartii;
use App\Models\Partia;
use App\Models\Runda;
use App\Models\Turniej;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PartiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Partia $partia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partia $partia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turniej $turniej, Runda $runda, Partia $partia)
    {
        Gate::authorize('update', [$runda, $turniej]);

        $validated = $request->validate([
            'biale_wynik_punktowy' => ['sometimes', 'nullable', Rule::enum(WynikPunktowyPartii::class)],
            'czarne_wynik_punktowy' => ['sometimes', 'nullable', Rule::enum(WynikPunktowyPartii::class)],
        ]);

        abort_if($validated === [], 422);

        DB::transaction(function () use ($validated, $turniej, $runda, $partia) {
            $runda = Runda::query()->lockForUpdate()->findOrFail($runda->id);
            $partia = Partia::query()->lockForUpdate()->findOrFail($partia->id);

            abort_unless($runda->turniej_id === $turniej->id, 404);
            abort_unless($partia->runda_id === $runda->id, 404);
            abort_unless($runda->status === RundaStatus::Trwajaca, 404);

            $partia->update($validated);
        });

        return redirect()->route('rundy.show', [$turniej, $runda]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partia $partia)
    {
        //
    }
}
