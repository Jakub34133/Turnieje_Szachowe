<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurniejStatus extends Model
{
    protected $table = "turniej_statusy";
    protected $fillable = [
        'nazwa'
    ];
}
