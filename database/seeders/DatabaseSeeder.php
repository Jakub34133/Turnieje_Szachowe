<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            TytulSeeder::class,
            TurniejStatusSeeder::class,
            TurniejSeeder::class,
            RundaSeeder::class,
            ZgloszenieStatusSeeder::class,
            ZgloszenieSeeder::class,
            TurniejZawodnikSeeder::class,
            TurniejSedziaSeeder::class
        ]);

    }
}
