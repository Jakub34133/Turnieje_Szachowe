<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Utworzenie użytkowników z różnymi rolami
        User::updateOrCreate(
            ['email' => 'kuba@turnieje.pl'],
            [
                'name' => 'Kuba',
                'password' => 'kuba1234',
                'pzszach_ranking' => 1600,
                'plec' => 'M',
                'data_urodzenia' => '2003-11-11',
                'pzszach_id' => '151421',
            ]
        )
            ->assignRole(RoleEnum::Administrator)
            ->assignRole(RoleEnum::Organizator)
            ->assignRole(RoleEnum::Sedzia)
            ->assignRole(RoleEnum::Zawodnik);

        User::updateOrCreate(
            ['email' => 'admin@turnieje.pl'],
            [
                'name' => 'Administrator Test',
                'password' => 'admin1234',
                'plec' => 'M',
                'data_urodzenia' => '2000-01-01',
            ]
        )
            ->assignRole(RoleEnum::Administrator);

        User::updateOrCreate(
            ['email' => 'organizator@turnieje.pl'],
            [
                'name' => 'Organizator Test',
                'password' => 'organizator1234',
                'plec' => 'M',
                'data_urodzenia' => '2000-01-01',
            ]
        )
            ->assignRole(RoleEnum::Organizator);

        User::updateOrCreate(
            ['email' => 'sedzia@turnieje.pl'],
            [
                'name' => 'Sedzia Test',
                'password' => 'sedzia1234',
                'plec' => 'M',
                'data_urodzenia' => '2000-01-01',
            ]
        )
            ->assignRole(RoleEnum::Sedzia);

        User::updateOrCreate(
            ['email' => 'zawodnik@turnieje.pl'],
            [
                'name' => 'Zawodnik Test',
                'password' => 'zawodnik1234',
                'plec' => 'M',
                'data_urodzenia' => '2000-01-01',
            ]
        )
            ->assignRole(RoleEnum::Zawodnik);

        // kolejne 20 losowych użytkowników
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
            )->assignRole(RoleEnum::Zawodnik);
        }
    }
}
