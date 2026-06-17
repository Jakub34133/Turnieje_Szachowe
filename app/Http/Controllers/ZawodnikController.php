<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use App\Models\TurniejZawodnik;
use Illuminate\Http\Request;

class ZawodnikController extends Controller
{
    public function index(Request $request,Turniej $turniej) {

        $zawodnicy = TurniejZawodnik::where('turniej_id', $turniej->id)
            ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
            ->with('zawodnik')
            ->orderByDesc('punkty')
            ->orderByDesc('users.ranking_krajowy')
            ->select('turniej_zawodnik.*')
            ->get();

        return view('zawodnicy.index', [
            'turniej' => $turniej,
            'turniejZawodnik' => $zawodnicy,
        ]);
    }
}
