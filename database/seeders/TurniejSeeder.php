<?php

namespace Database\Seeders;

use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class TurniejSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizatorId = User::query()
            ->where('email', 'kuba@turnieje.pl')
            ->firstOrFail()
            ->getKey();
        $planowanyStatusId = TurniejStatus::query()
            ->where('nazwa', 'Planowany')
            ->firstOrFail()
            ->getKey();
        $zakonczonyStatusId = TurniejStatus::query()
            ->where('nazwa', 'Zakończony')
            ->firstOrFail()
            ->getKey();

        Turniej::create([
            'organizator_id' => $organizatorId,
            'status_id' => $planowanyStatusId,
            'nazwa' => 'XVII DGCS OPEN Mistrzostwa Polski Przedsiębiorców BURSZTYNOWA I',
            'liczba_rund' => 7,
            'miejsce' => 'Technikum im. św. Józefa w Kaliszu ul. Złota 144a',
            'data_rozpoczecia' => '2026-10-24',
            'data_zakonczenia' => '2026-10-24',
            'tempo_gry' => '10\'+5\'\'',
            'rodzaj_szachow' => 'Szybkie',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 100,
            'opis' => 'Rozpoczęcie turnieju o godzinie 10:00 Potwierdzenie gry do godziny 9.30 na sali gry, (w wyjątkowych sytuacjach gdyby ktoś nie zdążył do tej godziny dojechać, można napisać że jest się w drodze wysyłając SMSa tel 793-733-396, z podaniem imienia nazwiska i grupy). Wejście na salę gry znajduje się od strony wewnętrznego parkingu szkoły, Na parkingu wewnętrznym szkoły jest ograniczona liczba miejsc parkingowych, Można też parkować wzdłuż głównego wejścia szkoły (na ulicy Złotej)',
        ]);

        Turniej::create([
            'organizator_id' => $organizatorId,
            'status_id' => $zakonczonyStatusId,
            'nazwa' => 'X Ogólnopolski Turniej Szachowy o Puchar Prezydenta Miasta Kalisza (gr. A)',
            'liczba_rund' => 5,
            'miejsce' => 'SP 8 Kalisz (ul. Serbinowska 22A)',
            'data_rozpoczecia' => '2025-10-25',
            'data_zakonczenia' => '2025-10-26',
            'tempo_gry' => '60\'+30\'\'',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
            'limit_ranking_od' => 1600,
            'limit_ranking_do' => 1800,
            'zgloszony_do_pzszach' => true,
        ]);

        Turniej::create([
            'organizator_id' => $organizatorId,
            'status_id' => $zakonczonyStatusId,
            'nazwa' => 'X Ogólnopolski Turniej Szachowy o Puchar Prezydenta Miasta Kalisza (gr. B)',
            'liczba_rund' => 7,
            'miejsce' => 'SP 8 Kalisz (ul. Serbinowska 22A)',
            'data_rozpoczecia' => '2025-10-25',
            'data_zakonczenia' => '2025-10-26',
            'tempo_gry' => '30\'+30\'\'',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
            'limit_ranking_od' => 1400,
            'limit_ranking_do' => 1600,
            'zgloszony_do_pzszach' => true,
        ]);

        Turniej::create([
            'organizator_id' => $organizatorId,
            'status_id' => $zakonczonyStatusId,
            'nazwa' => 'X Ogólnopolski Turniej Szachowy o Puchar Prezydenta Miasta Kalisza (gr. C)',
            'liczba_rund' => 7,
            'miejsce' => 'SP 8 Kalisz (ul. Serbinowska 22A)',
            'data_rozpoczecia' => '2025-10-25',
            'data_zakonczenia' => '2025-10-26',
            'tempo_gry' => '30\'+30\'\'',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
            'limit_ranking_od' => 1200,
            'limit_ranking_do' => 1400,
            'zgloszony_do_pzszach' => true,
        ]);
    }
}
