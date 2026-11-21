<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurniejSedzia extends Model
{
    protected $table = "turniej_sedzia";

    protected $fillable = [
        'turniej_id',
        'sedzia_id'
    ];

    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }

    public function sedzia()
    {
        return $this->belongsTo(User::class, 'sedzia_id');
    }
}
