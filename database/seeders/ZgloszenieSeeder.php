<?php

namespace Database\Seeders;

use App\Models\Turniej;
use App\Models\User;
use App\Models\ZgloszenieStatus;
use Illuminate\Database\Seeder;
use RuntimeException;

class ZgloszenieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Przypisanie po 10 losowych zawodników do istniejących turniejów
        $statusIds = ZgloszenieStatus::query()->pluck('id')->all();

        if ($statusIds === []) {
            throw new RuntimeException('Najpierw należy zasiać statusy zgłoszeń.');
        }

        Turniej::query()->each(function (Turniej $turniej) use ($statusIds): void {
            $existingPlayerIds = $turniej->zgloszenia()->pluck('zawodnik_id');
            $needed = max(0, 10 - $existingPlayerIds->count());

            if ($needed === 0) {
                return;
            }

            $zawodnicy = User::query()
                ->where('id', '!=', $turniej->organizator_id)
                ->whereNotIn('id', $existingPlayerIds)
                ->inRandomOrder()
                ->limit($needed)
                ->get();

            if ($zawodnicy->count() < $needed) {
                throw new RuntimeException("Za mało użytkowników, aby utworzyć 10 zgłoszeń do turnieju {$turniej->id}.");
            }

            foreach ($zawodnicy as $zawodnik) {
                $turniej->zgloszenia()->create([
                    'zawodnik_id' => $zawodnik->id,
                    'status_id' => 1,
                    'data_wyslania' => fake()->dateTimeBetween(
                        $turniej->data_rozpoczecia,
                        $turniej->data_zakonczenia
                    ),
                ]);
            }
        });
    }
}
