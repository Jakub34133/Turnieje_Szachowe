<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use App\Models\TurniejZawodnik;
use App\Models\User;
use Illuminate\Http\Request;

class ZawodnikController extends Controller
{
    public function index(Request $request,Turniej $turniej) {

        $zawodnicy = TurniejZawodnik::where('turniej_id', $turniej->id)
            ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
            ->with('zawodnik')
            ->orderByDesc('punkty')
            ->orderByDesc('users.pzszach_ranking')
            ->select('turniej_zawodnik.*')
            ->get();

        return view('zawodnicy.index', [
            'turniej' => $turniej,
            'turniejZawodnik' => $zawodnicy,
        ]);
    }

    public function show(Request $request, User $zawodnik) {

        $zawodnik = User::where('id', $zawodnik->id)->firstOrFail();

        return view('zawodnicy.show', [
            'zawodnik' => $zawodnik
        ]);
    }
}
