<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'professional_profile_id',
        'service_id',
        'scheduled_at',
        'status',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => AppointmentStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function professionalProfile(): BelongsTo
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function queueTicket(): HasOne
    {
        return $this->hasOne(QueueTicket::class);
    }

    public function canCheckin(): bool
    {
        return in_array($this->status, [AppointmentStatus::Agendado, AppointmentStatus::Confirmado], true)
            && $this->scheduled_at->isToday()
            && ! $this->queueTicket;
    }

    public function isCancelable(): bool
    {
        if (! in_array($this->status, [AppointmentStatus::Agendado, AppointmentStatus::Confirmado], true)) {
            return false;
        }

        $minNoticeHours = (int) Setting::getValue('cancelamento_antecedencia_minima_horas', '2');

        return now()->addHours($minNoticeHours)->lessThanOrEqualTo($this->scheduled_at);
    }
}
