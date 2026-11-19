<?php

namespace App\Enums;

enum RodzajSzachow: string
{
    case Klasyczne = 'Klasyczne';
    case Szybkie = 'Szybkie';
    case Błyskawiczne = 'Błyskawiczne';
    case Bullet = 'Bullet';

    public function label(): string
    {
        return match ($this) {
            self::Klasyczne => 'Klasyczne',
            self::Szybkie => 'Szybkie',
            self::Błyskawiczne => 'Błyskawiczne',
            self::Bullet => 'Bullet',
        };
    }
}
