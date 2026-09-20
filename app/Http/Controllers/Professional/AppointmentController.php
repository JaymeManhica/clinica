<?php

namespace App\Http\Controllers\Professional;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->professionalProfile;

        $appointments = $profile->appointments()
            ->with(['user', 'service'])
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->where('status', '!=', AppointmentStatus::Cancelado)
            ->orderBy('scheduled_at')
            ->paginate(20);

        return view('professional.appointments.index', ['appointments' => $appointments]);
    }
}
