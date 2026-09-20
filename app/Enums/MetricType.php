<?php

namespace App\Enums;

enum MetricType: string
{
    case Calculado = 'calculado';
    case Observado = 'observado';

    public function label(): string
    {
        return match ($this) {
            self::Calculado => 'Calculado (Teoria das Filas)',
            self::Observado => 'Observado (Dados Reais)',
        };
    }
}
