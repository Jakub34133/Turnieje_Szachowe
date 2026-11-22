<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use App\Models\Zgloszenie;
use App\Models\ZgloszenieStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ZgloszenieAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_players_can_create_applications_and_route_tournament_is_used(): void
    {
        $turniej = $this->createTournament();
        $otherTurniej = $this->createTournament();
        $userWithoutRole = User::factory()->create();

        $this->actingAs($userWithoutRole)
            ->get(route('turnieje.zgloszenia.create', $turniej))
            ->assertForbidden();

        $this->actingAs($userWithoutRole)
            ->post(route('turnieje.zgloszenia.store', $turniej))
            ->assertForbidden();

        $this->actingAs($userWithoutRole)
            ->get(route('turnieje.show', $turniej))
            ->assertOk()
            ->assertDontSee(route('turnieje.zgloszenia.store', $turniej), false);

        $player = User::factory()->create();
        $player->assignRole(Role::findOrCreate(RoleEnum::Zawodnik->value, 'web'));

        $this->actingAs($player)
            ->get(route('turnieje.zgloszenia.create', $turniej))
            ->assertOk();

        $status = ZgloszenieStatus::create(['nazwa' => 'wysłane']);

        $this->actingAs($player)
            ->post(route('turnieje.zgloszenia.store', $turniej), [
                'turniej' => $otherTurniej->id,
                'komentarz' => 'Zgłoszenie',
            ])
            ->assertRedirect(route('turnieje.show', $turniej));

        $this->assertDatabaseHas('zgloszenia', [
            'zawodnik_id' => $player->id,
            'turniej_id' => $turniej->id,
            'status_id' => $status->id,
        ]);
        $this->assertDatabaseMissing('zgloszenia', [
            'zawodnik_id' => $player->id,
            'turniej_id' => $otherTurniej->id,
        ]);
    }

    public function test_an_application_cannot_be_approved_through_another_tournament(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole(Role::findOrCreate(RoleEnum::Organizator->value, 'web'));
        $turniej = $this->createTournament($owner);
        $otherTurniej = $this->createTournament($owner);
        $status = ZgloszenieStatus::create(['nazwa' => 'wysłane']);
        $player = User::factory()->create();
        $zgloszenie = Zgloszenie::create([
            'zawodnik_id' => $player->id,
            'turniej_id' => $otherTurniej->id,
            'status_id' => $status->id,
            'data_wyslania' => now(),
        ]);

        $this->actingAs($owner)
            ->post(route('turnieje.zgloszenia.approve', [$turniej, $zgloszenie]))
            ->assertForbidden();

        $this->assertSame('wysłane', $zgloszenie->refresh()->status->nazwa);
    }

    private function createTournament(?User $owner = null): Turniej
    {
        $owner ??= User::factory()->create();
        $status = TurniejStatus::firstOrCreate(['nazwa' => 'Planowany']);

        return Turniej::create([
            'organizator_id' => $owner->id,
            'status_id' => $status->id,
            'nazwa' => 'Turniej testowy',
            'miejsce' => 'Kalisz',
            'data_rozpoczecia' => '2026-12-01',
            'data_zakonczenia' => '2026-12-01',
            'liczba_rund' => 5,
            'tempo_gry' => '10+5',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
        ]);
    }
}
