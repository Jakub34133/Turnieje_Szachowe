<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingUsers = [
            [
                'name' => 'Kuba',
                'email' => 'kuba@turnieje.pl',
                'password' => 'kuba1234',
                'pzszach_ranking' => 1600,
                'plec' => 'M',
                'data_urodzenia' => '2003-10-24',
                'pzszach_id' => '151421',
            ],
            [
                'name' => 'Adam',
                'email' => 'adam@turnieje.pl',
                'password' => 'adam1234',
                'pzszach_ranking' => 1400,
                'plec' => 'M',
                'data_urodzenia' => '2002-11-03',
            ],
            [
                'name' => 'Tomasz',
                'email' => 'tomasz@turnieje.pl',
                'password' => 'tomasz1234',
                'pzszach_ranking' => 1624,
                'plec' => 'M',
                'data_urodzenia' => '2005-05-05',
            ],
            [
                'name' => 'Alicja',
                'email' => 'alicja@turnieje.pl',
                'password' => 'alicja1234',
                'pzszach_ranking' => 0,
                'plec' => 'K',
                'data_urodzenia' => '2006-10-10',
            ],
            [
                'name' => 'Marek',
                'email' => 'marek@turnieje.pl',
                'password' => 'marek1234',
                'pzszach_ranking' => 0,
                'plec' => 'M',
                'data_urodzenia' => '1995-12-06',
            ],
        ];

        foreach ($existingUsers as $user) {
            User::query()->updateOrCreate(['email' => $user['email']], $user);
        }

        for ($number = 6; $number <= 20; $number++) {
            $faker = fake();

            User::query()->updateOrCreate(
                ['email' => sprintf('zawodnik%02d@turnieje.pl', $number)],
                [
                    'name' => $faker->name(),
                    'password' => 'zawodnik1234',
                    'plec' => $faker->randomElement(['M', 'K']),
                    'data_urodzenia' => $faker->dateTimeBetween('-80 years', '-8 years')->format('Y-m-d'),
                    'pzszach_ranking' => $faker->optional(0.8)->numberBetween(1000, 2500) ?? 0,
                    'fide_ranking_klasyczny' => $faker->optional(0.6)->numberBetween(1000, 2800) ?? 0,
                    'fide_ranking_szybkie' => $faker->optional(0.6)->numberBetween(1000, 2800) ?? 0,
                    'fide_ranking_blyskawiczne' => $faker->optional(0.6)->numberBetween(1000, 2800) ?? 0,
                    'pzszach_id' => $faker->optional(0.6)->numerify('######'),
                    'fide_id' => $faker->optional(0.6)->numerify('########'),
                    'klubowicz' => $faker->boolean(),
                ]
            );
        }
    }
}
