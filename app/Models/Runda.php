<?php

namespace App\Models;

use App\Enums\RundaStatus;
use Illuminate\Database\Eloquent\Model;

class Runda extends Model
{
    protected $table = 'rundy';

    protected $fillable = [
        'turniej_id',
        'numer',
        'status', // 'planowana', 'trwajaca', 'zakonczona'
        'data_rozpoczecia',
    ];

    protected function casts(): array
    {
        return [
            'status' => RundaStatus::class,
            'data_rozpoczecia' => 'datetime',
        ];
    }

    public function turniej()
    {
        return $this->belongsTo(Turniej::class, 'turniej_id');
    }

    public function partie()
    {
        return $this->hasMany(Partia::class, 'runda_id');
    }
}
