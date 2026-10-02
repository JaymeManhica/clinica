<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\ProfessionalProfile;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProfessionalService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @param  array{name: string, email: string, phone: string, password: string, title: string, registration_number: string, services?: array<int>}  $data
     */
    public function create(User $actor, array $data): ProfessionalProfile
    {
        return DB::transaction(function () use ($actor, $data) {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'role' => UserRole::Profissional,
                'active' => true,
            ]);

            $profile = ProfessionalProfile::query()->create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'registration_number' => $data['registration_number'],
            ]);

            $profile->services()->sync($data['services'] ?? []);

            $this->auditService->log($actor, 'professional.created', $profile, [], [
                ...Arr::only($data, ['name', 'email', 'phone', 'title', 'registration_number']),
                'services' => $data['services'] ?? [],
            ]);

            return $profile;
        });
    }

    /**
     * @param  array{name: string, email: string, phone: string, password?: ?string, title: string, registration_number: string, services?: array<int>}  $data
     */
    public function update(User $actor, ProfessionalProfile $profile, array $data): ProfessionalProfile
    {
        return DB::transaction(function () use ($actor, $profile, $data) {
            $user = $profile->user;

            $old = [
                ...$user->only(['name', 'email', 'phone']),
                ...$profile->only(['title', 'registration_number']),
                'services' => $profile->services()->pluck('services.id')->all(),
            ];

            $user->update(Arr::only($data, ['name', 'email', 'phone']));

            if (! empty($data['password'])) {
                $user->update(['password' => $data['password']]);
            }

            $profile->update(Arr::only($data, ['title', 'registration_number']));
            $profile->services()->sync($data['services'] ?? []);

            $this->auditService->log($actor, 'professional.updated', $profile, $old, [
                ...Arr::only($data, ['name', 'email', 'phone', 'title', 'registration_number']),
                'services' => $data['services'] ?? [],
                'password_changed' => ! empty($data['password']),
            ]);

            return $profile;
        });
    }

    public function toggleActive(User $actor, ProfessionalProfile $profile): ProfessionalProfile
    {
        return DB::transaction(function () use ($actor, $profile) {
            $user = $profile->user;
            $old = ['active' => $user->active];

            $user->update(['active' => ! $user->active]);

            $this->auditService->log($actor, 'professional.toggled', $profile, $old, ['active' => $user->active]);

            return $profile;
        });
    }
}
