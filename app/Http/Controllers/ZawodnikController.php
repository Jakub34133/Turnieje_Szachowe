<?php

namespace App\Http\Controllers;

use App\Enums\RundaStatus;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejZawodnik;
use App\Models\TurniejZawodnikHistoria;
use App\Models\Tytul;
use App\Models\User;
use Illuminate\Http\Request;

class ZawodnikController extends Controller
{
    public function index(Request $request, Turniej $turniej)
    {
        $rundy = $turniej->rundy()->get();

        $validated = $request->validate([
            'runda' => ['sometimes', 'integer', 'min:0'],
        ]);
        $ostatniaRundaZWynikami = Runda::query()
            ->where('turniej_id', $turniej->id)
            ->whereIn('id', TurniejZawodnikHistoria::query()
                ->where('turniej_id', $turniej->id)
                ->select('runda_id'))
            ->orderByDesc('numer')
            ->first();
        $numerRundy = isset($validated['runda'])
            ? (int) $validated['runda']
            : ($ostatniaRundaZWynikami?->numer ?? 0);
        $listaStartowa = $numerRundy === 0;
        $wybranaRunda = $listaStartowa ? null : $rundy->firstWhere('numer', $numerRundy);

        abort_if(! $listaStartowa && $wybranaRunda === null, 404);

        $poprzedniaRunda = $listaStartowa
            ? null
            : Runda::query()
                ->where('turniej_id', $turniej->id)
                ->where('numer', '<', $wybranaRunda->numer)
                ->orderByDesc('numer')
                ->first();
        $nastepnaRunda = $listaStartowa
            ? $rundy->first()
            : Runda::query()
                ->where('turniej_id', $turniej->id)
                ->where('numer', '>', $wybranaRunda->numer)
                ->orderBy('numer')
                ->first();

        if ($listaStartowa) {
            $zawodnicy = TurniejZawodnik::query()
                ->where('turniej_zawodnik.turniej_id', $turniej->id)
                ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
                ->with('zawodnik')
                ->orderByDesc('users.pzszach_ranking')
                ->select('turniej_zawodnik.*')
                ->get();
        } elseif ($wybranaRunda->status === RundaStatus::Zakonczona) {
            $zawodnicy = TurniejZawodnikHistoria::query()
                ->where('turniej_zawodnik_historia.turniej_id', $turniej->id)
                ->where('turniej_zawodnik_historia.runda_id', $wybranaRunda->id)
                ->join('users', 'turniej_zawodnik_historia.zawodnik_id', '=', 'users.id')
                ->with('zawodnik')
                ->orderByDesc('punkty_historia')
                ->orderByDesc('users.pzszach_ranking')
                ->select('turniej_zawodnik_historia.*')
                ->get();
        } else {
            $zawodnicy = collect();
        }

        $numerPoprzedniejRundy = $listaStartowa
            ? null
            : ($poprzedniaRunda?->numer ?? 0);
        $numerNastepnejRundy = $nastepnaRunda?->numer;

        return view('zawodnicy.index', [
            'turniej' => $turniej,
            'listaStartowa' => $listaStartowa,
            'wybranaRunda' => $wybranaRunda,
            'numerRundy' => $numerRundy,
            'numerPoprzedniejRundy' => $numerPoprzedniejRundy,
            'numerNastepnejRundy' => $numerNastepnejRundy,
            'turniejZawodnik' => $zawodnicy,
        ]);
    }

    public function show(Request $request, User $zawodnik)
    {

        $tytul_top = Tytul::where('zawodnik_id', $zawodnik->id)
            ->orderBy('data_nadania', 'desc')->first();

        return view('zawodnicy.show', [
            'zawodnik' => $zawodnik,
            'tytul_top' => $tytul_top,
        ]);
    }
}
