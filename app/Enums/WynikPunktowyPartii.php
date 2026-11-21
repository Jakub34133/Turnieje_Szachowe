<?php

namespace App\Enums;

enum WynikPunktowyPartii: string
{
    case WygranaBialych = "0";
    case Remis = "0.5";
    case WygranaCzarnych = "1";

    public function label(): string
    {
        return match ($this) {
            self::WygranaBialych => '0',
            self::Remis => '½',
            self::WygranaCzarnych => '1',
        };
    }
}
