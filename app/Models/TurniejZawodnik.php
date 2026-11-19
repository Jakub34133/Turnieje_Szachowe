<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurniejZawodnik extends Model
{
    protected $table = "turniej_zawodnik";

    protected $fillable = [
        'turniej_id',
        'zawodnik_id',
        'punkty',
    ];

    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }

    public function zawodnik()
    {
        return $this->belongsTo(User::class, 'zawodnik_id');
    }
}
