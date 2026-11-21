<?php

namespace App\Http\Controllers;

use App\Enums\RodzajSzachow;
use App\Enums\RundaStatus;
use App\Models\Partia;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejZawodnik;
use App\Models\TurniejZawodnikHistoria;
use Illuminate\Support\Facades\DB;

class RundaController extends Controller
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
    public function store(Turniej $turniej, Runda $runda)
    {
        abort_unless($runda->turniej_id === $turniej->id, 404);

        $poprzedniaRunda = $this->poprzedniaRunda($turniej, $runda);

        if ($poprzedniaRunda !== null && $poprzedniaRunda->status !== RundaStatus::Zakonczona) {
            return redirect()->route('rundy.show', [$turniej, $runda])
                ->with('error', 'Nie można wygenerować par, dopóki poprzednia runda nie zostanie zakończona.');
        }

        if ($runda->status !== RundaStatus::NieRozpoczeta || $runda->partie()->exists()) {
            return redirect()->route('rundy.show', [$turniej, $runda])
                ->with('error', 'Pary dla tej rundy zostały już wygenerowane.');
        }

        $rankingColumn = null;
        if ($turniej->zgloszony_do_pzszach) {
            $rankingColumn = 'users.pzszach_ranking';
        } elseif ($turniej->zgloszony_do_fide) {
            $rankingColumn = match ($turniej->rodzaj_szachow) {
                RodzajSzachow::Klasyczne => 'users.fide_ranking_klasyczny',
                RodzajSzachow::Szybkie => 'users.fide_ranking_szybkie',
                RodzajSzachow::Błyskawiczne, RodzajSzachow::Bullet => 'users.fide_ranking_blyskawiczne',
            };
        }

        $zawodnicy = TurniejZawodnik::where('turniej_id', $turniej->id)
            ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
            ->when($rankingColumn !== null, fn ($query) => $query->orderByDesc($rankingColumn))
            ->orderBy('turniej_zawodnik.id')
            ->select('turniej_zawodnik.*')
            ->get();

        if ($zawodnicy->isEmpty()) {
            return redirect()->route('rundy.show', [$turniej, $runda])
                ->with('error', 'Nie można wygenerować par bez zgłoszonych zawodników.');
        }

        /* Utworzenie partii dla pierwszej rundy
            Podział - górna i dolna połowa zawodników,
            Parowanie - 1 zawodnik z górnej połowy z 1 zawodnikiem z dolnej połowy, 2 z górnej z 2 z dolnej itd.
            Kolor - losowy dla pierwszej pary, naprzemiennie dla pozostałych par
        */
        DB::transaction(function () use ($zawodnicy, $runda, $turniej) {
            $wolnyLos = null;

            if ($zawodnicy->count() % 2 !== 0) {
                $poprzednieWolneLosy = Runda::query()
                    ->where('turniej_id', $turniej->id)
                    ->whereNotNull('wolny_los_zawodnik_id')
                    ->pluck('wolny_los_zawodnik_id');

                $kandydaci = TurniejZawodnik::query()
                    ->where('turniej_zawodnik.turniej_id', $turniej->id)
                    ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
                    ->orderBy('turniej_zawodnik.punkty')
                    ->orderBy('users.pzszach_ranking')
                    ->orderByDesc('turniej_zawodnik.id')
                    ->select('turniej_zawodnik.*');

                $wolnyLos = (clone $kandydaci)
                    ->when(
                        $poprzednieWolneLosy->isNotEmpty(),
                        fn ($query) => $query->whereNotIn('turniej_zawodnik.zawodnik_id', $poprzednieWolneLosy)
                    )
                    ->first() ?? $kandydaci->firstOrFail();

                $zawodnicy = $zawodnicy
                    ->reject(fn (TurniejZawodnik $zawodnik) => $zawodnik->zawodnik_id === $wolnyLos->zawodnik_id)
                    ->values();

                $wolnyLos->increment('punkty');
            }

            $liczbaPartii = intdiv($zawodnicy->count(), 2);
            $gornaPolowa = $zawodnicy->slice(0, $liczbaPartii)->values();
            $dolnaPolowa = $zawodnicy->slice($liczbaPartii, $liczbaPartii)->values();
            $kolorBialych = random_int(0, 1); // losowy kolor pierwszej pary

            for ($i = 0; $i < $liczbaPartii; $i++) {
                $biale = $kolorBialych === 0 ? $gornaPolowa[$i] : $dolnaPolowa[$i];
                $czarne = $kolorBialych === 0 ? $dolnaPolowa[$i] : $gornaPolowa[$i];

                Partia::create([
                    'runda_id' => $runda->id,
                    'stolik' => (string) ($i + 1),
                    'biale_zawodnik_id' => $biale->zawodnik_id,
                    'czarne_zawodnik_id' => $czarne->zawodnik_id,
                ]);

                $kolorBialych === 0 ? $kolorBialych = 1 : $kolorBialych = 0;
            }

            $runda->update([
                'status' => RundaStatus::Przygotowana,
                'wolny_los_zawodnik_id' => $wolnyLos?->zawodnik_id,
            ]);
        });

        return redirect()->route('rundy.show', ['turniej' => $turniej->id, 'runda' => $runda->id])
            ->with('success', 'Runda została utworzona pomyślnie.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turniej $turniej, ?Runda $runda = null)
    {
        $runda ??= Runda::query()
            ->where('turniej_id', $turniej->id)
            ->whereIn('status', [RundaStatus::Trwajaca->value, RundaStatus::Zakonczona->value])
            ->orderByDesc('numer')
            ->first()
            ?? $turniej->rundy()->first();

        abort_if($runda === null, 404);
        abort_unless($runda->turniej_id === $turniej->id, 404);

        $poprzedniaRunda = $this->poprzedniaRunda($turniej, $runda);

        $nastepnaRunda = Runda::query()
            ->where('turniej_id', $turniej->id)
            ->where('numer', '>', $runda->numer)
            ->orderBy('numer')
            ->first();

        $zawodnicy = TurniejZawodnik::where('turniej_id', $turniej->id)
            ->join('users', 'turniej_zawodnik.zawodnik_id', '=', 'users.id')
            ->with('zawodnik')
            ->orderByDesc('punkty')
            ->orderByDesc('users.pzszach_ranking')
            ->select('turniej_zawodnik.*')
            ->get();
        $partie = $runda->partie()->with(['bialeZawodnik', 'czarneZawodnik'])->orderBy('stolik')->get();
        $runda->loadMissing('wolnyLosZawodnik');

        return view('rundy.show', [
            'turniej' => $turniej,
            'turniejZawodnik' => $zawodnicy,
            'runda' => $runda,
            'partie' => $partie,
            'poprzedniaRunda' => $poprzedniaRunda,
            'nastepnaRunda' => $nastepnaRunda,
            'moznaWygenerowacPary' => $poprzedniaRunda === null || $poprzedniaRunda->status === RundaStatus::Zakonczona,
        ]);
    }

    public function begin(Turniej $turniej, Runda $runda)
    {
        abort_unless($runda->turniej_id === $turniej->id, 404);

        $poprzedniaRunda = $this->poprzedniaRunda($turniej, $runda);

        if ($poprzedniaRunda !== null && $poprzedniaRunda->status !== RundaStatus::Zakonczona) {
            return redirect()->route('rundy.show', [$turniej, $runda])
                ->with('error', 'Nie można rozpocząć rundy, dopóki poprzednia runda nie zostanie zakończona.');
        }

        $runda->update(['status' => RundaStatus::Trwajaca]);

        return redirect()->route('rundy.show', ['turniej' => $turniej->id, 'runda' => $runda->id])
            ->with('success', 'Runda została rozpoczęta pomyślnie.');
    }

    public function finish(Turniej $turniej, Runda $runda)
    {
        abort_unless($runda->turniej_id === $turniej->id, 404);

        $error = DB::transaction(function () use ($turniej, $runda) {
            $runda = Runda::query()->lockForUpdate()->findOrFail($runda->id);

            if ($runda->status !== RundaStatus::Trwajaca) {
                return 'Runda nie jest w trakcie rozgrywania.';
            }

            $partie = $runda->partie()->lockForUpdate()->get();

            if ($partie->isEmpty()) {
                return 'Nie można zakończyć rundy bez partii.';
            }

            if ($partie->contains(fn (Partia $partia) => $partia->biale_wynik_punktowy === null || $partia->czarne_wynik_punktowy === null)) {
                return 'Uzupełnij wyniki wszystkich partii przed zakończeniem rundy.';
            }

            $punktyPolowkowe = [];

            foreach ($partie as $partia) {
                foreach ([
                    $partia->biale_zawodnik_id => $partia->biale_wynik_punktowy,
                    $partia->czarne_zawodnik_id => $partia->czarne_wynik_punktowy,
                ] as $zawodnikId => $wynik) {
                    $punktyPolowkowe[$zawodnikId] = ($punktyPolowkowe[$zawodnikId] ?? 0)
                        + match ($wynik->value) {
                            '0' => 0,
                            '0.5' => 1,
                            '1' => 2,
                        };
                }
            }

            foreach ($punktyPolowkowe as $zawodnikId => $punkty) {
                $turniejZawodnik = TurniejZawodnik::query()
                    ->where('turniej_id', $turniej->id)
                    ->where('zawodnik_id', $zawodnikId)
                    ->firstOrFail();

                $turniejZawodnik->increment('punkty', $punkty / 2);
            }

            $turniejZawodnicy = TurniejZawodnik::query()->where('turniej_id', $turniej->id)->get();

            foreach ($turniejZawodnicy as $turniejZawodnik) {
                TurniejZawodnikHistoria::create([
                    'turniej_id' => $turniej->id,
                    'zawodnik_id' => $turniejZawodnik->zawodnik_id,
                    'runda_id' => $runda->id,
                    'punkty_historia' => $turniejZawodnik->punkty,
                ]);
            }

            $runda->update(['status' => RundaStatus::Zakonczona]);

            return null;
        });

        if ($error !== null) {
            return redirect()->route('rundy.show', [$turniej, $runda])->with('error', $error);
        }

        return redirect()->route('rundy.show', [$turniej, $runda])
            ->with('success', 'Runda została zakończona, a punkty zawodników zaktualizowane.');
    }

    private function poprzedniaRunda(Turniej $turniej, Runda $runda): ?Runda
    {
        return Runda::query()
            ->where('turniej_id', $turniej->id)
            ->where('numer', '<', $runda->numer)
            ->orderByDesc('numer')
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Runda $runda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Runda $runda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Runda $runda)
    {
        //
    }
}
