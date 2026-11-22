<?php

namespace Tests\Feature;

use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TurniejAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_organizers_can_create_tournaments(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('turnieje.create'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('turnieje.store'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('turnieje.index'))
            ->assertOk()
            ->assertDontSee('href="'.route('turnieje.create').'"', false);
    }

    public function test_only_the_creator_organizer_can_edit_or_delete_a_tournament(): void
    {
        $owner = $this->createOrganizer();
        $otherOrganizer = $this->createOrganizer();
        $turniej = $this->createTournament($owner);

        $this->actingAs($owner)
            ->get(route('turnieje.show', $turniej))
            ->assertOk()
            ->assertSee('href="'.route('turnieje.edit', $turniej).'"', false);

        $this->actingAs($otherOrganizer)
            ->get(route('turnieje.edit', $turniej))
            ->assertForbidden();

        $this->actingAs($otherOrganizer)
            ->put(route('turnieje.update', $turniej))
            ->assertForbidden();

        $this->actingAs($otherOrganizer)
            ->delete(route('turnieje.destroy', $turniej))
            ->assertForbidden();

        $this->assertDatabaseHas('turnieje', ['id' => $turniej->id]);

        $this->actingAs($owner)
            ->delete(route('turnieje.destroy', $turniej))
            ->assertRedirect(route('turnieje.index'));

        $this->assertDatabaseMissing('turnieje', ['id' => $turniej->id]);
    }

    public function test_tournament_owner_without_organizer_role_cannot_edit_or_delete(): void
    {
        $owner = User::factory()->create();
        $turniej = $this->createTournament($owner);

        $this->actingAs($owner)
            ->get(route('turnieje.edit', $turniej))
            ->assertForbidden();

        $this->actingAs($owner)
            ->delete(route('turnieje.destroy', $turniej))
            ->assertForbidden();

        $this->assertDatabaseHas('turnieje', ['id' => $turniej->id]);
    }

    private function createOrganizer(): User
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Organizator', 'web'));

        return $user;
    }

    private function createTournament(User $owner): Turniej
    {
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);

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
