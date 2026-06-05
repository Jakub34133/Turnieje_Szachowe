<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Kuba',
            'email' => 'kuba@turnieje.pl',
            'password' => bcrypt('kuba1234'),
        ]);

        $this->call([
            TurniejStatusSeeder::class,
            TurniejSeeder::class,
        ]);

    }
}
