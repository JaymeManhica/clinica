<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\CancelAppointmentRequest;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Services\AppointmentService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentService $appointments) {}

    public function index(Request $request): View
    {
        $appointments = $request->user()->appointments()
            ->with(['service', 'professionalProfile.user', 'queueTicket'])
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        return view('utente.agendamentos.index', ['appointments' => $appointments]);
    }

    public function create(): View
    {
        $services = Service::query()
            ->where('active', true)
            ->with('professionalProfiles.user')
            ->orderBy('name')
            ->get();

        $professionalsByService = [];

        foreach ($services as $service) {
            $professionalsByService[$service->id] = $service->professionalProfiles
                ->map(fn (ProfessionalProfile $profile) => ['id' => $profile->id, 'name' => $profile->user->name])
                ->values();
        }

        return view('utente.agendamentos.create', [
            'services' => $services,
            'professionalsByService' => $professionalsByService,
        ]);
    }

    public function horarios(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'professional_profile_id' => ['required', 'integer', 'exists:professional_profiles,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $professional = ProfessionalProfile::findOrFail($validated['professional_profile_id']);

        $slots = $this->appointments->availableSlots($service, $professional, CarbonImmutable::parse($validated['date']));

        return response()->json(['slots' => $slots]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->integer('service_id'));
        $professional = ProfessionalProfile::findOrFail($request->integer('professional_profile_id'));

        $this->appointments->book(
            $request->user(),
            $service,
            $professional,
            $request->string('date')->toString(),
            $request->string('time')->toString(),
        );

        return redirect()->route('utente.agendamentos.index')->with('status', 'agendamento-criado');
    }

    public function cancel(CancelAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->appointments->cancel($request->user(), $appointment, $request->input('reason'));

        return back()->with('status', 'agendamento-cancelado');
    }
}
