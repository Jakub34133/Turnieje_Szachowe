<?php

namespace Tests\Feature;

use App\Enums\RundaStatus;
use App\Models\Runda;
use App\Models\Turniej;
use App\Models\TurniejStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_browse_tournaments_rounds_results_and_public_profiles(): void
    {
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);
        $owner = User::factory()->create();
        $turniej = Turniej::create([
            'organizator_id' => $owner->id,
            'status_id' => $status->id,
            'nazwa' => 'Turniej publiczny',
            'miejsce' => 'Kalisz',
            'data_rozpoczecia' => '2026-12-01',
            'data_zakonczenia' => '2026-12-01',
            'liczba_rund' => 1,
            'tempo_gry' => '10+5',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
        ]);
        $runda = Runda::create([
            'turniej_id' => $turniej->id,
            'numer' => 1,
            'status' => RundaStatus::NieRozpoczeta,
        ]);
        $zawodnik = User::factory()->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Turnieje Szachowe')
            ->assertSee('Zaloguj się')
            ->assertSee('href="'.route('login').'"', false)
            ->assertDontSee('data-test="user-menu-button"', false);

        $this->get(route('turnieje.index'))
            ->assertOk()
            ->assertSee('Turnieje Szachowe');

        $this->get(route('turnieje.show', $turniej))
            ->assertOk()
            ->assertSee('Turniej publiczny')
            ->assertSee('Zaloguj się');

        $this->get(route('rundy.show', [$turniej, $runda]))
            ->assertOk()
            ->assertSee('Runda 1')
            ->assertSee('Zaloguj się');

        $this->get(route('zawodnicy.index', $turniej))
            ->assertOk()
            ->assertSee('Lista startowa')
            ->assertSee('Zaloguj się');

        $this->get(route('zawodnicy.show', $zawodnik))
            ->assertOk()
            ->assertSee($zawodnik->name)
            ->assertSee('Zaloguj się');

        $this->get(route('tytuly.index', $zawodnik))
            ->assertOk()
            ->assertSee('Historia uzyskanych tytułów')
            ->assertSee('Zaloguj się');
    }

    public function test_guest_access_to_tournament_management_and_applications_still_requires_login(): void
    {
        $status = TurniejStatus::create(['nazwa' => 'Planowany']);
        $owner = User::factory()->create();
        $turniej = Turniej::create([
            'organizator_id' => $owner->id,
            'status_id' => $status->id,
            'nazwa' => 'Turniej publiczny',
            'miejsce' => 'Kalisz',
            'data_rozpoczecia' => '2026-12-01',
            'data_zakonczenia' => '2026-12-01',
            'liczba_rund' => 1,
            'tempo_gry' => '10+5',
            'rodzaj_szachow' => 'Klasyczne',
            'liczba_zawodnikow' => 0,
            'limit_zawodnikow' => 50,
        ]);

        $this->get(route('turnieje.create'))
            ->assertRedirect(route('login'));

        $this->get(route('turnieje.zgloszenia.index', $turniej))
            ->assertRedirect(route('login'));
    }
}
