<?php

namespace App\Models;

use App\Enums\PriorityLevel;
use App\Enums\QueueTicketOrigin;
use App\Enums\QueueTicketStatus;
use Database\Factories\QueueTicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QueueTicket extends Model
{
    /** @use HasFactory<QueueTicketFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'service_id',
        'professional_profile_id',
        'user_id',
        'appointment_id',
        'origin',
        'status',
        'priority_level',
        'issued_at',
        'called_at',
        'started_at',
        'finished_at',
        'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'origin' => QueueTicketOrigin::class,
            'status' => QueueTicketStatus::class,
            'priority_level' => PriorityLevel::class,
            'issued_at' => 'datetime',
            'called_at' => 'datetime',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function professionalProfile(): BelongsTo
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function priorityRecord(): HasOne
    {
        return $this->hasOne(PriorityRecord::class);
    }
}
