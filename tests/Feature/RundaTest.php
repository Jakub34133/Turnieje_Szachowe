<?php

namespace Tests\Feature;

use App\Enums\RodzajSzachow;
use App\Enums\RundaStatus;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\TurniejZawodnik;
use App\Models\User;
use Database\Seeders\RundaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RundaTest extends TestCase
{
    use RefreshDatabase;

    public function test_pair_generation_uses_real_columns_handles_odd_players_and_is_idempotent(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::NieRozpoczeta,
        ]);
        $players = User::factory()->count(3)->create();

        foreach ($players as $player) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
            ]);
        }

        $this->actingAs($organizator)
            ->post(route('rundy.store', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $this->assertDatabaseCount('partie', 1);
        $this->assertSame(RundaStatus::Przygotowana, $runda->refresh()->status);
        $this->assertSame(1, (int) TurniejZawodnik::where('turniej_id', $turniej->id)->sum('punkty'));

        $partia = $runda->partie()->firstOrFail();
        $this->assertNotSame($partia->biale_zawodnik_id, $partia->czarne_zawodnik_id);
        $this->assertDatabaseHas('partie', [
            'id' => $partia->id,
            'biale_zawodnik_id' => $partia->biale_zawodnik_id,
            'czarne_zawodnik_id' => $partia->czarne_zawodnik_id,
        ]);

        $this->actingAs($organizator)
            ->post(route('rundy.store', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('partie', 1);
    }

    public function test_round_page_displays_the_tournament_rounds_link_and_enum_status(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::NieRozpoczeta,
        ]);

        $this->actingAs($organizator)
            ->get(route('rundy.show', $turniej))
            ->assertOk()
            ->assertSee('Nie rozpoczęta')
            ->assertSee(route('rundy.show', $turniej));

        $this->assertSame(RodzajSzachow::Klasyczne, $turniej->refresh()->rodzaj_szachow);
        $this->assertSame(RundaStatus::NieRozpoczeta, $runda->refresh()->status);
    }

    public function test_round_seeder_creates_one_row_for_every_tournament_round(): void
    {
        $turniej = $this->createTournament(User::factory()->create());
        $turniej->update(['liczba_rund' => 5]);

        $this->seed(RundaSeeder::class);

        $this->assertSame(5, $turniej->rundy()->count());
        $this->assertSame([1, 2, 3, 4, 5], $turniej->rundy()->pluck('numer')->all());
    }

    private function createTournament(User $organizator): Turniej
    {
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);

        return Turniej::create([
            'organizator_id' => $organizator->id,
            'status_id' => $status->id,
            'nazwa' => 'Turniej testowy',
            'miejsce' => 'Kalisz',
            'data_rozpoczecia' => '2026-12-01',
            'data_zakonczenia' => '2026-12-01',
            'liczba_rund' => 1,
            'tempo_gry' => '10+5',
            'rodzaj_szachow' => RodzajSzachow::Klasyczne,
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
        ]);
    }
}
