<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Runda extends Model
{
    protected $table = "rundy";

    protected $fillable = [
        'turniej_id',
        'numer',
        'status', // 'planowana', 'trwajaca', 'zakonczona'
        'data_rozpoczecia',
    ];

    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }

}
