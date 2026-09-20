<?php

namespace App\Models;

use Database\Factories\AvailabilityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    /** @use HasFactory<AvailabilityFactory> */
    use HasFactory;

    protected $fillable = [
        'professional_profile_id',
        'service_id',
        'day_of_week',
        'start_time',
        'end_time',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function professionalProfile(): BelongsTo
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return array<int, string>
     */
    public static function diasSemana(): array
    {
        return [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];
    }

    public function diaSemanaLabel(): string
    {
        return self::diasSemana()[$this->day_of_week];
    }

    public function isToday(): bool
    {
        return $this->day_of_week === now()->dayOfWeek;
    }

    public function isOpenNow(): bool
    {
        if (! $this->isToday()) {
            return false;
        }

        $now = now()->format('H:i:s');

        return $now >= $this->start_time && $now <= $this->end_time;
    }
}
