<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turniej extends Model
{
    protected $table = "turnieje";

    protected $fillable = [
        'organizator_id',
        'status_id',
        'nazwa',
        'liczba_rund',
        'miejsce',
        'data_rozpoczecia',
        'data_zakonczenia',
        'tempo_gry',
        'rodzaj_szachow',
        'liczba_zawodnikow',
        'limit_zawodnikow',
        'opis',
        'komunikat_path',
        'limit_plec',
        'limit_wiek',
        'limit_ranking_od',
        'limit_ranking_do',
        'limit_klub',
        'zgloszony_do_pzszach',
        'zgloszony_do_fide'
    ];

    public function organizator()
    {
        return $this->belongsTo(User::class, 'organizator_id');
    }

    public function status()
    {
        return $this->belongsTo(TurniejStatus::class, 'status_id');
    }

    public function zgloszenia()
    {
        return $this->hasMany(Zgloszenie::class, 'turniej_id');
    }
}
