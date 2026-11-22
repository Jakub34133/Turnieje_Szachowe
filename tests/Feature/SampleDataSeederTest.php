<?php

namespace Tests\Feature;

use App\Models\Turniej;
use App\Models\User;
use App\Models\Zgloszenie;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\TurniejSeeder;
use Database\Seeders\TurniejStatusSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ZgloszenieSeeder;
use Database\Seeders\ZgloszenieStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SampleDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_twenty_users_and_ten_unique_applications_per_tournament(): void
    {
        $this->seed([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            TurniejStatusSeeder::class,
            TurniejSeeder::class,
            ZgloszenieStatusSeeder::class,
            ZgloszenieSeeder::class,
        ]);

        $this->assertSame(20, User::query()->count());
        $this->assertSame(4, Turniej::query()->count());
        $this->assertSame(40, Zgloszenie::query()->count());
        $this->assertSame(
            [10, 10, 10, 10],
            Turniej::query()->withCount('zgloszenia')->orderBy('id')->pluck('zgloszenia_count')->all()
        );

        foreach (Turniej::query()->get() as $turniej) {
            $this->assertSame(
                10,
                $turniej->zgloszenia()->distinct('zawodnik_id')->count('zawodnik_id')
            );
        }

        $this->seed([UserSeeder::class, ZgloszenieSeeder::class]);

        $this->assertSame(20, User::query()->count());
        $this->assertSame(40, Zgloszenie::query()->count());
    }
}
