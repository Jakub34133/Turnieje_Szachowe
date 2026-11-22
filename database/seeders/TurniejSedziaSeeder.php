<?php

namespace Database\Seeders;

use App\Models\Turniej;
use App\Models\TurniejSedzia;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TurniejSedziaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Turniej::query()->each(function (Turniej $turniej) {
            TurniejSedzia::updateOrCreate([
                'turniej_id' => $turniej->getKey(),
                'sedzia_id' => User::query()->where('email', 'sedzia@turnieje.pl')->first()->getKey()
            ]);
        });
    }
}
