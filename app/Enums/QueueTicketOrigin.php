<?php

namespace App\Enums;

enum QueueTicketOrigin: string
{
    case Agendamento = 'agendamento';
    case Espontaneo = 'espontaneo';

    public function label(): string
    {
        return match ($this) {
            self::Agendamento => 'Agendamento',
            self::Espontaneo => 'Chegada Espontânea',
        };
    }
}
