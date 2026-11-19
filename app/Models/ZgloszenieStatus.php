<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZgloszenieStatus extends Model
{
    protected $table = "zgloszenie_statusy";
    protected $fillable = [
        'nazwa'
    ];
}
