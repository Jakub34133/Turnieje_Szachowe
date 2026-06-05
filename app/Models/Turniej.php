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
        'liczba_zawodnikow',
        'limit_zawodnikow',
        'opis',
        'komunikat_path'
    ];

    public function organizator()
    {
        return $this->belongsTo(User::class, 'organizator_id');
    }

    public function status()
    {
        return $this->belongsTo(TurniejStatus::class, 'status_id');
    }
}
