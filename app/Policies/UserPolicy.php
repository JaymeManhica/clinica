<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->isAdministrador();
    }

    public function update(User $authUser, User $user): bool
    {
        if ($authUser->isAdministrador()) {
            return true;
        }

        return $authUser->id === $user->id && $authUser->isUtente();
    }
}
