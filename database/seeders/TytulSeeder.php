<?php

namespace Database\Seeders;

use App\Models\Tytul;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TytulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zawodnikId = User::query()
            ->where('email', 'kuba@turnieje.pl')
            ->firstOrFail()
            ->getKey();

        Tytul::create(
            [
                'zawodnik_id' => $zawodnikId,
                'tytul_typ' => 'Kategoria krajowa',
                'nazwa' => 'Kategoria IV',
                'data_nadania' => '2025-10-24',
            ]
        );

        Tytul::create(
            [
                'zawodnik_id' => $zawodnikId,
                'tytul_typ' => 'Kategoria krajowa',
                'nazwa' => 'Kategoria III',
                'data_nadania' => '2026-10-24',
            ]
        );
    }
}
