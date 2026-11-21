<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurniejZawodnikHistoria extends Model
{
    protected $table = 'turniej_zawodnik_historia';

    protected $fillable = [
        'turniej_id',
        'zawodnik_id',
        'runda_id',
        'punkty_historia',
    ];

    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }

    public function runda()
    {
        return $this->belongsTo(Runda::class, 'runda_id');
    }

    public function zawodnik()
    {
        return $this->belongsTo(User::class, 'zawodnik_id');
    }
}
