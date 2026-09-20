<?php

namespace App\Policies;

use App\Models\PriorityRecord;
use App\Models\User;

class PriorityRecordPolicy
{
    public function confirm(User $user, PriorityRecord $record): bool
    {
        $profile = $user->professionalProfile;

        return $profile !== null && $profile->services->contains('id', $record->queueTicket->service_id);
    }
}
