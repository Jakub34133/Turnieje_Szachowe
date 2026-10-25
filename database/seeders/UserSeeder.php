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
            'pzszach_ranking' => 1600,
            'tytul' => 'III',
            'plec' => 'M',
            'data_urodzenia' => '2003-10-24',
        ]);

        User::factory()->create([
            'name' => 'Adam',
            'email' => 'adam@turnieje.pl',
            'password' => bcrypt('adam1234'),
            'pzszach_ranking' => 1400,
            'tytul' => 'IV',
            'plec' => 'M',
            'data_urodzenia' => '2002-11-03',
        ]);

        User::factory()->create([
            'name' => 'Tomasz',
            'email' => 'tomasz@turnieje.pl',
            'password' => bcrypt('tomasz1234'),
            'pzszach_ranking' => 1624,
            'tytul' => 'III',
            'plec' => 'M',
            'data_urodzenia' => '2005-05-05',
        ]);

        User::factory()->create([
            'name' => 'Alicja',
            'email' => 'alicja@turnieje.pl',
            'password' => bcrypt('alicja1234'),
            'pzszach_ranking' => 0,
            'tytul' => 'brak',
            'plec' => 'K',
            'data_urodzenia' => '2006-10-10',
        ]);

        User::factory()->create([
            'name' => 'Marek',
            'email' => 'marek@turnieje.pl',
            'password' => bcrypt('marek1234'),
            'pzszach_ranking' => 0,
            'tytul' => 'brak',
            'plec' => 'M',
            'data_urodzenia' => '1995-12-06',
        ]);
    }
}
