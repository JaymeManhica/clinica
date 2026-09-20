<?php

namespace App\Enums;

enum PriorityLevel: string
{
    case Normal = 'normal';
    case Prioritario = 'prioritario';

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Prioritario => 'Prioritário',
        };
    }
}
