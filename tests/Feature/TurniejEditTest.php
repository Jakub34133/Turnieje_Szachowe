<?php

namespace Tests\Feature;

use App\Enums\RodzajSzachow;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TurniejEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_tournament_edit_displays_and_saves_all_editable_fields(): void
    {
        $user = User::factory()->create();
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);
        $turniej = $this->createTournament($user, $status);

        $this->actingAs($user)
            ->get(route('turnieje.edit', $turniej))
            ->assertOk()
            ->assertSee('name="rodzaj_szachow"', false)
            ->assertSee('name="limit_plec"', false)
            ->assertSee('name="limit_ranking_od"', false)
            ->assertSee('name="limit_klub"', false)
            ->assertSee('value="0" selected', false);

        $this->actingAs($user)
            ->put(route('turnieje.update', $turniej), [
                'nazwa' => 'Turniej po zmianie',
                'miejsce' => 'Kalisz',
                'data_rozpoczecia' => '2026-12-01',
                'data_zakonczenia' => '2026-12-02',
                'liczba_rund' => 7,
                'limit_zawodnikow' => 80,
                'tempo_gry' => '10+5',
                'rodzaj_szachow' => 'Szybkie',
                'status_id' => $status->id,
                'limit_plec' => 'K',
                'limit_wiek' => 16,
                'limit_ranking_od' => 1400,
                'limit_ranking_do' => 1900,
                'limit_klub' => '0',
                'zgloszony_do_pzszach' => '1',
                'zgloszony_do_fide' => '0',
                'opis' => 'Opis turnieju',
            ])
            ->assertRedirect(route('turnieje.show', $turniej));

        $turniej->refresh();

        $this->assertSame(RodzajSzachow::Szybkie, $turniej->rodzaj_szachow);
        $this->assertSame(7, Runda::where('turniej_id', $turniej->id)->count());
        $this->assertSame('K', $turniej->limit_plec);
        $this->assertSame(16, $turniej->limit_wiek);
        $this->assertSame(1400, $turniej->limit_ranking_od);
        $this->assertSame(1900, $turniej->limit_ranking_do);
        $this->assertFalse((bool) $turniej->limit_klub);
        $this->assertTrue((bool) $turniej->zgloszony_do_pzszach);
        $this->assertFalse((bool) $turniej->zgloszony_do_fide);

        $this->actingAs($user)
            ->get(route('turnieje.show', $turniej))
            ->assertOk()
            ->assertSee('Miejsce')
            ->assertSee('Kobiety')
            ->assertSee('1400 - 1900');
    }

    public function test_tournament_edit_rejects_reversed_ranking_limits(): void
    {
        $user = User::factory()->create();
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);
        $turniej = $this->createTournament($user, $status);

        $this->actingAs($user)
            ->from(route('turnieje.edit', $turniej))
            ->put(route('turnieje.update', $turniej), [
                'nazwa' => $turniej->nazwa,
                'miejsce' => $turniej->miejsce,
                'data_rozpoczecia' => $turniej->data_rozpoczecia,
                'data_zakonczenia' => $turniej->data_zakonczenia,
                'liczba_rund' => $turniej->liczba_rund,
                'limit_zawodnikow' => $turniej->limit_zawodnikow,
                'tempo_gry' => $turniej->tempo_gry,
                'rodzaj_szachow' => $turniej->rodzaj_szachow->value,
                'status_id' => $status->id,
                'limit_ranking_od' => 1900,
                'limit_ranking_do' => 1400,
                'limit_klub' => '1',
                'zgloszony_do_pzszach' => '0',
                'zgloszony_do_fide' => '0',
            ])
            ->assertRedirect(route('turnieje.edit', $turniej))
            ->assertSessionHasErrors('limit_ranking_od');

        $this->assertNull($turniej->refresh()->limit_ranking_od);
    }

    private function createTournament(User $user, TurniejStatus $status): Turniej
    {
        return Turniej::create([
            'organizator_id' => $user->id,
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
