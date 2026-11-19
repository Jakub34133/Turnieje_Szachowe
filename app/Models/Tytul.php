<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tytul extends Model
{
    protected $table = "tytuly";
    protected $fillable = [
        'zawodnik_id',
        'tytul_typ',
        'nazwa',
        'data_nadania',
    ];

    public function zawodnik()
    {
        return $this->belongsTo(User::class, 'zawodnik_id');
    }
}
