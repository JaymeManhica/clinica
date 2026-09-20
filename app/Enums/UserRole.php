<?php

namespace App\Enums;

enum UserRole: string
{
    case Utente = 'utente';
    case Profissional = 'profissional';
    case Administrador = 'administrador';

    public function label(): string
    {
        return match ($this) {
            self::Utente => 'Utente',
            self::Profissional => 'Profissional de Saúde',
            self::Administrador => 'Administrador',
        };
    }
}
