<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @param  array{name: string, email: string, phone: string, password: string}  $data
     */
    public function registerUtente(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'role' => UserRole::Utente,
                'active' => true,
            ]);

            $this->auditService->log($user, 'user.registered', $user, [], [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);

            return $user;
        });
    }

    public function attemptLogin(string $identifier, string $password): void
    {
        $field = Str::contains($identifier, '@') ? 'email' : 'phone';

        if (! Auth::attempt([$field => $identifier, 'password' => $password, 'active' => true])) {
            throw ValidationException::withMessages([
                'identifier' => 'As credenciais fornecidas não correspondem a uma conta activa.',
            ]);
        }
    }

    /**
     * @param  array{name: string, email: string, phone: string}  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $old = $user->only(['name', 'email', 'phone']);

            $user->update($data);

            $this->auditService->log($user, 'user.profile_updated', $user, $old, $data);

            return $user;
        });
    }

    public function changePassword(User $user, string $newPassword): void
    {
        DB::transaction(function () use ($user, $newPassword) {
            $user->update(['password' => $newPassword]);

            $this->auditService->log($user, 'user.password_changed', $user);
        });
    }
}
