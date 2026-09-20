<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::query()->with(['user', 'service', 'professionalProfile.user']);

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->integer('service_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date('date'));
        }

        $appointments = $query->orderByDesc('scheduled_at')->paginate(20)->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'services' => Service::query()->orderBy('name')->get(),
        ]);
    }
}
