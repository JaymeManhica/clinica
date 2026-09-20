<?php

namespace App\Policies;

use App\Models\QueueTicket;
use App\Models\User;

class QueueTicketPolicy
{
    public function view(User $user, QueueTicket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }

    public function cancel(User $user, QueueTicket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }

    public function manage(User $user, QueueTicket $ticket): bool
    {
        return $user->professionalProfile?->id === $ticket->professional_profile_id;
    }
}
