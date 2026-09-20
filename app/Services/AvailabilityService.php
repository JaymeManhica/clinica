<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @param  array{professional_profile_id: int, day_of_week: int, start_time: string, end_time: string}  $data
     */
    public function create(User $actor, Service $service, array $data): Availability
    {
        return DB::transaction(function () use ($actor, $service, $data) {
            $availability = $service->availabilities()->create([...$data, 'active' => true]);

            $this->auditService->log($actor, 'availability.created', $availability, [], $data);

            return $availability;
        });
    }

    /**
     * @param  array{professional_profile_id: int, day_of_week: int, start_time: string, end_time: string}  $data
     */
    public function update(User $actor, Availability $availability, array $data): Availability
    {
        return DB::transaction(function () use ($actor, $availability, $data) {
            $old = $availability->only(['professional_profile_id', 'day_of_week', 'start_time', 'end_time']);

            $availability->update($data);

            $this->auditService->log($actor, 'availability.updated', $availability, $old, $data);

            return $availability;
        });
    }

    public function delete(User $actor, Availability $availability): void
    {
        DB::transaction(function () use ($actor, $availability) {
            $old = $availability->only(['professional_profile_id', 'day_of_week', 'start_time', 'end_time']);

            $this->auditService->log($actor, 'availability.deleted', $availability, $old);

            $availability->delete();
        });
    }
}
