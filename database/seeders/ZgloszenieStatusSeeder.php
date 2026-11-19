<?php

namespace Database\Seeders;

use App\Models\ZgloszenieStatus;
use Illuminate\Database\Seeder;

class ZgloszenieStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ZgloszenieStatus::create(['nazwa' => 'wysłane']);
        ZgloszenieStatus::create(['nazwa' => 'zatwierdzone']);
        ZgloszenieStatus::create(['nazwa' => 'odrzucone']);
        ZgloszenieStatus::create(['nazwa' => 'anulowane']);
    }
}
