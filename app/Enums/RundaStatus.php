<?php

namespace App\Enums;

enum RundaStatus: string
{
    case NieRozpoczeta = 'NieRozpoczeta';
    case Przygotowana = 'Przygotowana';
    case Trwajaca = 'Trwajaca';
    case Zakonczona = 'Zakonczona';

    public function label(): string
    {
        return match ($this) {
            self::NieRozpoczeta => 'Nie rozpoczęta',
            self::Przygotowana => 'Przygotowana',
            self::Trwajaca => 'Trwająca',
            self::Zakonczona => 'Zakończona',
        };
    }
}
