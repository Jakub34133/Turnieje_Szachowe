<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Administrator = "Administrator";
    case Organizator = "Organizator";
    case Sedzia = "Sedzia";
    case Zawodnik = "Zawodnik";

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Organizator => 'Organizator',
            self::Sedzia => 'Sędzia',
            self::Zawodnik => 'Zawodnik',
        };
    }

    
}
