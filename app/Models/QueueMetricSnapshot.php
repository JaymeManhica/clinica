<?php

namespace App\Models;

use App\Enums\MetricType;
use Database\Factories\QueueMetricSnapshotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueMetricSnapshot extends Model
{
    /** @use HasFactory<QueueMetricSnapshotFactory> */
    use HasFactory;

    protected $fillable = [
        'service_id',
        'period_start',
        'period_end',
        'lambda',
        'mu',
        'rho',
        'l',
        'lq',
        'w',
        'wq',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'lambda' => 'decimal:4',
            'mu' => 'decimal:4',
            'rho' => 'decimal:4',
            'l' => 'decimal:4',
            'lq' => 'decimal:4',
            'w' => 'decimal:4',
            'wq' => 'decimal:4',
            'type' => MetricType::class,
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
