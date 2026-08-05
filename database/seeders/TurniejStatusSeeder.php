<?php

namespace Database\Seeders;

use App\Models\TurniejStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurniejStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Planowany',
            'W trakcie',
            'Zakończony',
            'Anulowany'
        ];

        foreach ($statuses as $status) {
            TurniejStatus::create(['nazwa' => $status]);
        }
    }
}
