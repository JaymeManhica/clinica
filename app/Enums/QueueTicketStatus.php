<?php

namespace App\Enums;

enum QueueTicketStatus: string
{
    case Emitida = 'emitida';
    case Chamada = 'chamada';
    case EmAtendimento = 'em_atendimento';
    case Concluida = 'concluida';
    case Cancelada = 'cancelada';
    case Desistencia = 'desistencia';

    public function label(): string
    {
        return match ($this) {
            self::Emitida => 'Emitida',
            self::Chamada => 'Chamada',
            self::EmAtendimento => 'Em Atendimento',
            self::Concluida => 'Concluída',
            self::Cancelada => 'Cancelada',
            self::Desistencia => 'Desistência',
        };
    }

    /**
     * Estados que ainda contam como presentes/activos na fila.
     *
     * @return array<int, self>
     */
    public static function activos(): array
    {
        return [self::Emitida, self::Chamada, self::EmAtendimento];
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Emitida => 'bg-amber-50 text-amber-700',
            self::Chamada => 'bg-blue-50 text-blue-700',
            self::EmAtendimento => 'bg-indigo-50 text-indigo-700',
            self::Concluida => 'bg-emerald-100 text-emerald-800',
            self::Cancelada => 'bg-gray-100 text-gray-500',
            self::Desistencia => 'bg-red-50 text-red-700',
        };
    }
}
