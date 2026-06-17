<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zgloszenie extends Model
{
    // (zawodnik_id, turniej_id, status_id, data_wyslania, komentarz)
    protected $table = "zgloszenia";
    protected $fillable = [
        'zawodnik_id',
        'turniej_id',
        'status_id',
        'data_wyslania',
        'komentarz'
    ];

    public function zawodnik()
    {
        return $this->belongsTo(User::class, 'zawodnik_id');
    }
    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }
    public function status() {
        return $this->belongsTo(ZgloszenieStatus::class, 'status_id');
    }
}
