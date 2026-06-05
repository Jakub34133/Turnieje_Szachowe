<?php

namespace Database\Seeders;

use App\Models\Turniej;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurniejSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Turniej::create([
            'organizator_id' => 1,
            'status_id' => 3, // Zakończony
            'nazwa' => 'I Turniej Szachowy naszego klubu',
            'liczba_rund' => 5,
            'miejsce' => 'Klub Szachowy kalisz, ul. Szachowa',
            'data_rozpoczecia' => '2025-07-01',
            'data_zakonczenia' => '2025-07-05',
            'tempo_gry' => '30+30',
            'liczba_zawodnikow' => 18,
            'limit_zawodnikow' => 30,
            'opis' => 'Nasz pierwszy turniej szachowy w klubie.',
        ]);

        Turniej::create([
            'organizator_id' => 1,
            'status_id' => 1, // Planowany
            'nazwa' => 'II Turniej Szachowy naszego klubu',
            'liczba_rund' => 7,
            'miejsce' => 'Klub Szachowy kalisz, ul. Szachowa',
            'data_rozpoczecia' => '2026-07-01',
            'data_zakonczenia' => '2026-07-07',
            'tempo_gry' => '30+30',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 30,
            'opis' => 'Nasz drugi turniej szachowy w klubie.',
        ]);
    }
}
