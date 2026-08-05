<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Kuba',
            'email' => 'kuba@turnieje.pl',
            'password' => bcrypt('kuba1234'),
            'ranking_krajowy' => 1600,
            'kategoria' => 'III',
        ]);

        User::factory()->create([
            'name' => 'Adam',
            'email' => 'adam@turnieje.pl',
            'password' => bcrypt('adam1234'),
            'ranking_krajowy' => 1400,
            'kategoria' => 'IV',
        ]);

        User::factory()->create([
            'name' => 'Tomasz',
            'email' => 'tomasz@turnieje.pl',
            'password' => bcrypt('tomasz1234'),
            'ranking_krajowy' => 1624,
            'kategoria' => 'III',
        ]);

        User::factory()->create([
            'name' => 'Alicja',
            'email' => 'alicja@turnieje.pl',
            'password' => bcrypt('alicja1234'),
            'ranking_krajowy' => 0,
            'kategoria' => 'brak',
        ]);

        User::factory()->create([
            'name' => 'Marek',
            'email' => 'marek@turnieje.pl',
            'password' => bcrypt('marek1234'),
            'ranking_krajowy' => 0,
            'kategoria' => 'brak',
        ]);
    }
}
