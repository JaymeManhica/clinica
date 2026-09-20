<?php

namespace App\Services;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ServiceCatalogService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @param  array{name: string, description: ?string, average_duration_minutes: int}  $data
     */
    public function create(User $actor, array $data): Service
    {
        return DB::transaction(function () use ($actor, $data) {
            $service = Service::query()->create([...$data, 'active' => true]);

            $this->auditService->log($actor, 'service.created', $service, [], $service->only([
                'name', 'description', 'average_duration_minutes',
            ]));

            return $service;
        });
    }

    /**
     * @param  array{name: string, description: ?string, average_duration_minutes: int}  $data
     */
    public function update(User $actor, Service $service, array $data): Service
    {
        return DB::transaction(function () use ($actor, $service, $data) {
            $old = $service->only(['name', 'description', 'average_duration_minutes']);

            $service->update($data);

            $this->auditService->log($actor, 'service.updated', $service, $old, $data);

            return $service;
        });
    }

    public function toggleActive(User $actor, Service $service): Service
    {
        return DB::transaction(function () use ($actor, $service) {
            $old = ['active' => $service->active];

            $service->update(['active' => ! $service->active]);

            $this->auditService->log($actor, 'service.toggled', $service, $old, ['active' => $service->active]);

            return $service;
        });
    }
}
