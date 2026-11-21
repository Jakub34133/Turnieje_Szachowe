<?php

namespace App\Models;

use App\Enums\WynikPunktowyPartii;
use Illuminate\Database\Eloquent\Model;

class Partia extends Model
{
    protected $table = 'partie';

    protected $fillable = [
        'runda_id',
        'stolik',
        'biale_zawodnik_id',
        'czarne_zawodnik_id',
        'biale_wynik_punktowy',
        'czarne_wynik_punktowy',
    ];

    protected function casts(): array
    {
        return [
            'biale_wynik_punktowy' => WynikPunktowyPartii::class,
            'czarne_wynik_punktowy' => WynikPunktowyPartii::class,
        ];
    }

    public function runda()
    {
        return $this->belongsTo(Runda::class, 'runda_id');
    }

    public function bialeZawodnik()
    {
        return $this->belongsTo(User::class, 'biale_zawodnik_id');
    }

    public function czarneZawodnik()
    {
        return $this->belongsTo(User::class, 'czarne_zawodnik_id');
    }
}
