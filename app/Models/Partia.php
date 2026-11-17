<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partia extends Model
{
    protected $table = "partie";

    protected $fillable = [
        'runda_id',
        'stolik',
        'biale_zawodnik_id',
        'czarne_zawodnik_id',
        'biale_wynik_punktowy',
        'czarne_wynik_punktowy'
    ];

    public function runda()
    {
        return $this->belongsTo(Runda::class, 'runda_id');
    }

    public function biale_zawodnik_id()
    {
        return $this->belongsTo(User::class, 'biale_zawodnik_id');
    }

    public function czarne_zawodnik_id()
    {
        return $this->belongsTo(User::class, 'czarne_zawodnik_id');
    }
}
