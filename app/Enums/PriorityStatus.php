<?php

namespace App\Enums;

enum PriorityStatus: string
{
    case Pendente = 'pendente';
    case Confirmada = 'confirmada';
    case Rejeitada = 'rejeitada';

    public function label(): string
    {
        return match ($this) {
            self::Pendente => 'Pendente',
            self::Confirmada => 'Confirmada',
            self::Rejeitada => 'Rejeitada',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pendente => 'bg-amber-50 text-amber-700',
            self::Confirmada => 'bg-emerald-100 text-emerald-800',
            self::Rejeitada => 'bg-red-50 text-red-700',
        };
    }
}
