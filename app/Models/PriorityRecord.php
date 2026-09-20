<?php

namespace App\Models;

use App\Enums\PriorityLevel;
use App\Enums\PriorityStatus;
use Database\Factories\PriorityRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriorityRecord extends Model
{
    /** @use HasFactory<PriorityRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'queue_ticket_id',
        'requested_level',
        'confirmed_level',
        'reason',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_level' => PriorityLevel::class,
            'confirmed_level' => PriorityLevel::class,
            'status' => PriorityStatus::class,
            'confirmed_at' => 'datetime',
        ];
    }

    public function queueTicket(): BelongsTo
    {
        return $this->belongsTo(QueueTicket::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
