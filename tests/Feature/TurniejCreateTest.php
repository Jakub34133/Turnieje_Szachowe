<?php

namespace Tests\Feature;

use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurniejCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form_contains_and_saves_tournament_fields(): void
    {
        $user = User::factory()->create();
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);

        $this->actingAs($user)
            ->get(route('turnieje.create'))
            ->assertOk()
            ->assertSee('name="rodzaj_szachow"', false)
            ->assertSee('name="limit_plec"', false)
            ->assertSee('name="limit_ranking_od"', false)
            ->assertSee('name="limit_klub"', false)
            ->assertSee('name="zgloszony_do_pzszach"', false)
            ->assertSee('name="zgloszony_do_fide"', false);

        $this->actingAs($user)
            ->post(route('turnieje.store'), [
                'nazwa' => 'Nowy turniej',
                'miejsce' => 'Kalisz',
                'data_rozpoczecia' => '2026-12-01',
                'data_zakonczenia' => '2026-12-02',
                'liczba_rund' => 7,
                'limit_zawodnikow' => 80,
                'tempo_gry' => '10+5',
                'rodzaj_szachow' => 'Szybkie',
                'status_id' => $status->id,
                'limit_plec' => 'M',
                'limit_wiek' => 16,
                'limit_ranking_od' => 1400,
                'limit_ranking_do' => 1900,
                'limit_klub' => '0',
                'zgloszony_do_pzszach' => '1',
                'zgloszony_do_fide' => '0',
                'opis' => 'Opis turnieju',
            ])
            ->assertRedirect(route('turnieje.index'));

        $turniej = Turniej::query()->firstOrFail();

        $this->assertSame($user->id, $turniej->organizator_id);
        $this->assertSame('Szybkie', $turniej->rodzaj_szachow);
        $this->assertSame(0, $turniej->liczba_zawodnikow);
        $this->assertSame('M', $turniej->limit_plec);
        $this->assertSame(16, $turniej->limit_wiek);
        $this->assertSame(1400, $turniej->limit_ranking_od);
        $this->assertSame(1900, $turniej->limit_ranking_do);
        $this->assertFalse((bool) $turniej->limit_klub);
        $this->assertTrue((bool) $turniej->zgloszony_do_pzszach);
        $this->assertFalse((bool) $turniej->zgloszony_do_fide);
    }

    public function test_create_rejects_reversed_ranking_limits(): void
    {
        $user = User::factory()->create();
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);

        $this->actingAs($user)
            ->from(route('turnieje.create'))
            ->post(route('turnieje.store'), [
                'nazwa' => 'Nieprawidłowy turniej',
                'miejsce' => 'Kalisz',
                'data_rozpoczecia' => '2026-12-01',
                'data_zakonczenia' => '2026-12-02',
                'liczba_rund' => 7,
                'limit_zawodnikow' => 80,
                'tempo_gry' => '10+5',
                'rodzaj_szachow' => 'Szybkie',
                'status_id' => $status->id,
                'limit_ranking_od' => 1900,
                'limit_ranking_do' => 1400,
                'limit_klub' => '1',
                'zgloszony_do_pzszach' => '0',
                'zgloszony_do_fide' => '0',
            ])
            ->assertRedirect(route('turnieje.create'))
            ->assertSessionHasErrors('limit_ranking_od');

        $this->assertDatabaseCount('turnieje', 0);
    }
}
