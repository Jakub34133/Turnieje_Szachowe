<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;

#[Fillable(
    [
        'name', 'email', 'password', 
        'plec', 'data_urodzenia',
        'fide_ranking_klasyczny', 'fide_ranking_szybkie', 'fide_ranking_blyskawiczne',
        'pzszach_ranking', 'tytul',
        'fide_id', 'pzszach_id', 'klubowicz'
    ]
)]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /* TODO: 

        Tytul(
            zawodnik_id [tu pobrać posiadane tytuły do profilu],
            tytul_typ (kategoria_krajowa, tytul_fide)
            nazwa (np. Kategoria I, IM, GM),
            data_nadania,
            numer_zaswiadczenia (opcjonalnie)
            )

        [Opcjonalne, na później do prowadzenia statystyk]
        Ranking_Historia(
            zawodnik_id,
            federacja (PZSzach, FIDE),
            tempo_gry (standard, szybkie, blitz),
            ranking,
            data_zdobycia
        )
      */
}
