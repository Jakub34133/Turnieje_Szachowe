<?php

namespace Tests\Feature;

use App\Enums\RodzajSzachow;
use App\Enums\RoleEnum;
use App\Enums\RundaStatus;
use App\Enums\WynikPunktowyPartii;
use App\Models\Partia;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejSedzia;
use App\Models\TurniejStatus;
use App\Models\TurniejZawodnik;
use App\Models\TurniejZawodnikHistoria;
use App\Models\User;
use Database\Seeders\RundaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
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

    public function test_next_round_gives_a_bye_to_the_lowest_eligible_player_and_shows_them_below_pairs(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $players = User::factory()->count(3)->create();
        $previousBye = $players[0];
        $lowestStanding = $players[1];

        foreach ([
            [$previousBye, 2],
            [$lowestStanding, 0],
            [$players[2], 1],
        ] as [$player, $points]) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
                'punkty' => $points,
            ]);
        }

        Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Zakonczona,
            'wolny_los_zawodnik_id' => $previousBye->id,
        ]);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 2,
            'status' => RundaStatus::NieRozpoczeta,
        ]);

        $this->actingAs($organizator)
            ->post(route('rundy.store', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $this->assertSame($lowestStanding->id, $runda->refresh()->wolnyLosZawodnik->id);
        $this->assertSame(1, (int) TurniejZawodnik::where('turniej_id', $turniej->id)
            ->where('zawodnik_id', $lowestStanding->id)
            ->value('punkty'));
        $this->assertDatabaseCount('partie', 1);
        $this->assertDatabaseMissing('partie', [
            'runda_id' => $runda->id,
            'biale_zawodnik_id' => $lowestStanding->id,
        ]);
        $this->assertDatabaseMissing('partie', [
            'runda_id' => $runda->id,
            'czarne_zawodnik_id' => $lowestStanding->id,
        ]);

        $this->actingAs($organizator)
            ->get(route('rundy.show', [$turniej, $runda]))
            ->assertOk()
            ->assertSee('Wolny los:')
            ->assertSee($lowestStanding->name);
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
            ->assertSee('flex-nowrap overflow-x-auto')
            ->assertSee(route('rundy.show', $turniej));

        $this->assertSame(RodzajSzachow::Klasyczne, $turniej->refresh()->rodzaj_szachow);
        $this->assertSame(RundaStatus::NieRozpoczeta, $runda->refresh()->status);
    }

    public function test_round_without_parameter_shows_the_latest_active_or_completed_round(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);

        Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Zakonczona,
        ]);
        Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 2,
            'status' => RundaStatus::Trwajaca,
        ]);
        Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 3,
            'status' => RundaStatus::Przygotowana,
        ]);

        $this->actingAs($organizator)
            ->get(route('rundy.show', $turniej))
            ->assertOk()
            ->assertSee('Runda 2');
    }

    public function test_results_index_displays_snapshots_for_the_selected_round(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $rundy = collect([1, 2])->map(fn (int $numer) => Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => $numer,
            'status' => RundaStatus::Zakonczona,
        ]));
        [$zawodnikA, $zawodnikB] = User::factory()->count(2)->create();

        foreach ([
            [$rundy[0], $zawodnikA, '0.5'],
            [$rundy[0], $zawodnikB, '0'],
            [$rundy[1], $zawodnikA, '1.5'],
            [$rundy[1], $zawodnikB, '1'],
        ] as [$runda, $zawodnik, $punkty]) {
            TurniejZawodnikHistoria::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $zawodnik->id,
                'runda_id' => $runda->id,
                'punkty_historia' => $punkty,
            ]);
        }

        $this->actingAs($organizator)
            ->get(route('zawodnicy.index', [$turniej]).'?runda=1')
            ->assertOk()
            ->assertSee('Wyniki po rundzie 1')
            ->assertSeeInOrder([$zawodnikA->name, '0', '½', $zawodnikB->name, '0']);

        $this->actingAs($organizator)
            ->get(route('zawodnicy.index', [$turniej]))
            ->assertOk()
            ->assertSee('Wyniki po rundzie 2')
            ->assertSeeInOrder([$zawodnikA->name, '1', '½']);
    }

    public function test_results_navigation_includes_rounds_without_results(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);

        foreach ([
            [1, RundaStatus::Zakonczona],
            [2, RundaStatus::Trwajaca],
            [3, RundaStatus::Przygotowana],
        ] as [$numer, $status]) {
            Runda::create([
                'turniej_id' => $turniej->id,
                'numer' => $numer,
                'status' => $status,
            ]);
        }

        $this->actingAs($organizator)
            ->get(route('zawodnicy.index', [$turniej]).'?runda=2')
            ->assertOk()
            ->assertSee('Jeszcze nie ma wyników dla tej rundy')
            ->assertSee(route('zawodnicy.index', [$turniej, 'runda' => 1]))
            ->assertSee(route('zawodnicy.index', [$turniej, 'runda' => 3]));
    }

    public function test_starting_list_is_available_before_rounds_and_signups_exist(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);

        $this->actingAs($organizator)
            ->get(route('zawodnicy.index', $turniej))
            ->assertOk()
            ->assertSee('Lista startowa')
            ->assertSee('Jeszcze nie ma zgłoszonych zawodników');
    }

    public function test_starting_list_uses_roster_and_orders_only_by_rating_without_points(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $nizszyRanking = User::factory()->create();
        $wyzszyRanking = User::factory()->create();
        $nizszyRanking->forceFill(['pzszach_ranking' => 1500])->save();
        $wyzszyRanking->forceFill(['pzszach_ranking' => 2200])->save();

        TurniejZawodnik::create([
            'turniej_id' => $turniej->id,
            'zawodnik_id' => $nizszyRanking->id,
            'punkty' => 5,
        ]);
        TurniejZawodnik::create([
            'turniej_id' => $turniej->id,
            'zawodnik_id' => $wyzszyRanking->id,
            'punkty' => 0,
        ]);

        $this->actingAs($organizator)
            ->get(route('zawodnicy.index', [$turniej, 'runda' => 0]))
            ->assertOk()
            ->assertSee('Lista startowa')
            ->assertSeeInOrder([$wyzszyRanking->name, $nizszyRanking->name])
            ->assertDontSee('Punkty');
    }

    public function test_next_round_pair_generation_is_hidden_and_blocked_while_previous_round_is_active(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $poprzedniaRunda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Trwajaca,
        ]);
        $nastepnaRunda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 2,
            'status' => RundaStatus::NieRozpoczeta,
        ]);

        $this->actingAs($organizator)
            ->get(route('rundy.show', [$turniej, $nastepnaRunda]))
            ->assertOk()
            ->assertDontSee('Wygeneruj listę par zawodników')
            ->assertSee('Poprzednia runda musi zostać zakończona');

        $this->actingAs($organizator)
            ->post(route('rundy.store', [$turniej, $nastepnaRunda]))
            ->assertRedirect(route('rundy.show', [$turniej, $nastepnaRunda]))
            ->assertSessionHas('error');

        $this->actingAs($organizator)
            ->patch(route('rundy.begin', [$turniej, $nastepnaRunda]))
            ->assertRedirect(route('rundy.show', [$turniej, $nastepnaRunda]))
            ->assertSessionHas('error');

        $this->assertSame(RundaStatus::Trwajaca, $poprzedniaRunda->refresh()->status);
        $this->assertSame(RundaStatus::NieRozpoczeta, $nastepnaRunda->refresh()->status);
        $this->assertDatabaseCount('partie', 0);
    }

    public function test_changing_a_game_score_updates_only_that_game_result(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Trwajaca,
        ]);
        [$biale, $czarne] = User::factory()->count(2)->create();

        foreach ([$biale, $czarne] as $player) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
            ]);
        }

        $partia = Partia::create([
            'runda_id' => $runda->id,
            'stolik' => '1',
            'biale_zawodnik_id' => $biale->id,
            'czarne_zawodnik_id' => $czarne->id,
        ]);

        $this->actingAs($organizator)
            ->patch(route('partie.update', [$turniej, $runda, $partia]), [
                'biale_wynik_punktowy' => WynikPunktowyPartii::Remis->value,
            ])
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $this->assertSame(WynikPunktowyPartii::Remis, $partia->refresh()->biale_wynik_punktowy);
        $this->assertNull($partia->czarne_wynik_punktowy);
        $this->assertSame(0, (int) TurniejZawodnik::where('turniej_id', $turniej->id)->sum('punkty'));
    }

    public function test_only_the_tournament_organizer_or_assigned_referee_can_manage_rounds(): void
    {
        $organizer = User::factory()->create();
        $turniej = $this->createTournament($organizer);
        $otherTurniej = $this->createTournament($organizer);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::NieRozpoczeta,
        ]);
        $players = User::factory()->count(2)->create();

        foreach ($players as $player) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
            ]);
        }

        $referee = User::factory()->create();
        $referee->assignRole(Role::findOrCreate(RoleEnum::Sedzia->value, 'web'));
        TurniejSedzia::create([
            'turniej_id' => $otherTurniej->id,
            'sedzia_id' => $referee->id,
        ]);

        $this->actingAs($referee)
            ->post(route('rundy.store', [$turniej, $runda]))
            ->assertForbidden();

        $this->actingAs($organizer)
            ->post(route('rundy.store', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $this->actingAs($referee)
            ->patch(route('rundy.begin', [$turniej, $runda]))
            ->assertForbidden();

        TurniejSedzia::create([
            'turniej_id' => $turniej->id,
            'sedzia_id' => $referee->id,
        ]);

        $this->actingAs($referee)
            ->patch(route('rundy.begin', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $viewer = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('rundy.show', [$turniej, $runda]))
            ->assertOk()
            ->assertSee('Pary rundy')
            ->assertDontSee('name="biale_wynik_punktowy"', false)
            ->assertDontSee('Zatwierdź wyniki i zakończ rundę');

        $this->actingAs($referee)
            ->get(route('rundy.show', [$turniej, $runda]))
            ->assertOk()
            ->assertSee('Zatwierdź wyniki i zakończ rundę');
    }

    public function test_finishing_a_round_adds_all_game_points_and_marks_the_round_complete(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Trwajaca,
        ]);
        $players = User::factory()->count(4)->create();

        foreach ($players as $player) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
            ]);
        }

        Partia::create([
            'runda_id' => $runda->id,
            'stolik' => '1',
            'biale_zawodnik_id' => $players[0]->id,
            'czarne_zawodnik_id' => $players[1]->id,
            'biale_wynik_punktowy' => WynikPunktowyPartii::Remis,
            'czarne_wynik_punktowy' => WynikPunktowyPartii::Remis,
        ]);
        Partia::create([
            'runda_id' => $runda->id,
            'stolik' => '2',
            'biale_zawodnik_id' => $players[2]->id,
            'czarne_zawodnik_id' => $players[3]->id,
            'biale_wynik_punktowy' => WynikPunktowyPartii::WygranaCzarnych,
            'czarne_wynik_punktowy' => WynikPunktowyPartii::WygranaBialych,
        ]);

        $this->actingAs($organizator)
            ->patch(route('rundy.finish', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]));

        $this->assertSame(RundaStatus::Zakonczona, $runda->refresh()->status);
        $this->assertDatabaseCount('turniej_zawodnik_historia', 4);
        $this->assertDatabaseHas('turniej_zawodnik_historia', [
            'turniej_id' => $turniej->id,
            'zawodnik_id' => $players[0]->id,
            'runda_id' => $runda->id,
            'punkty_historia' => '0.5',
        ]);
        $expectedScores = [0.5, 0.5, 1, 0];
        $getScores = fn () => $players
            ->map(fn (User $player) => (float) TurniejZawodnik::where('turniej_id', $turniej->id)
                ->where('zawodnik_id', $player->id)
                ->value('punkty'))
            ->all();

        $this->assertEquals($expectedScores, $getScores());

        $this->actingAs($organizator)
            ->patch(route('rundy.finish', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]))
            ->assertSessionHas('error');

        $this->assertEquals($expectedScores, $getScores());
    }

    public function test_round_cannot_be_finished_until_every_game_has_both_scores(): void
    {
        $organizator = User::factory()->create();
        $turniej = $this->createTournament($organizator);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::Trwajaca,
        ]);
        [$biale, $czarne] = User::factory()->count(2)->create();

        foreach ([$biale, $czarne] as $player) {
            TurniejZawodnik::create([
                'turniej_id' => $turniej->id,
                'zawodnik_id' => $player->id,
            ]);
        }

        Partia::create([
            'runda_id' => $runda->id,
            'stolik' => '1',
            'biale_zawodnik_id' => $biale->id,
            'czarne_zawodnik_id' => $czarne->id,
            'biale_wynik_punktowy' => WynikPunktowyPartii::WygranaBialych,
        ]);

        $this->actingAs($organizator)
            ->patch(route('rundy.finish', [$turniej, $runda]))
            ->assertRedirect(route('rundy.show', [$turniej, $runda]))
            ->assertSessionHas('error');

        $this->assertSame(RundaStatus::Trwajaca, $runda->refresh()->status);
        $this->assertSame(0, (int) TurniejZawodnik::where('turniej_id', $turniej->id)->sum('punkty'));
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
        $organizator->assignRole(Role::findOrCreate(RoleEnum::Organizator->value, 'web'));
        $status = TurniejStatus::firstOrCreate(['nazwa' => 'Planowany']);

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
