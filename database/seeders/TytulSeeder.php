<?php

namespace Database\Seeders;

use App\Models\Tytul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TytulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // zawodnik Kuba
        Tytul::create(
            [
                'zawodnik_id' => 1,
                'tytul_typ' => 'Kategoria krajowa',
                'nazwa' => 'Kategoria IV',
                'data_nadania' => '2025-10-24',
            ]
        );

        Tytul::create(
            [
                'zawodnik_id' => 1,
                'tytul_typ' => 'Kategoria krajowa',
                'nazwa' => 'Kategoria III',
                'data_nadania' => '2026-10-24',
            ]
        );
    }
}
