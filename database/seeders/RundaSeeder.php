<?php

namespace Database\Seeders;

use App\Enums\RundaStatus;
use App\Models\Turniej;
use Illuminate\Database\Seeder;

class RundaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Turniej::query()->each(function (Turniej $turniej) {
            for ($numer = 1; $numer <= $turniej->liczba_rund; $numer++) {
                $turniej->rundy()->firstOrCreate(
                    ['numer' => $numer],
                    ['status' => RundaStatus::NieRozpoczeta]
                );
            }
        });
    }
}
