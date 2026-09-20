<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Agendado = 'agendado';
    case Confirmado = 'confirmado';
    case Cancelado = 'cancelado';
    case Concluido = 'concluido';
    case NaoCompareceu = 'nao_compareceu';

    public function label(): string
    {
        return match ($this) {
            self::Agendado => 'Agendado',
            self::Confirmado => 'Confirmado',
            self::Cancelado => 'Cancelado',
            self::Concluido => 'Concluído',
            self::NaoCompareceu => 'Não Compareceu',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Agendado => 'bg-blue-50 text-blue-700',
            self::Confirmado => 'bg-emerald-50 text-emerald-700',
            self::Cancelado => 'bg-gray-100 text-gray-500',
            self::Concluido => 'bg-emerald-100 text-emerald-800',
            self::NaoCompareceu => 'bg-red-50 text-red-700',
        };
    }
}
