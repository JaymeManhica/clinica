<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AvailabilityRequest;
use App\Models\Availability;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    public function index(Service $service): View
    {
        $availabilities = $service->availabilities()
            ->with('professionalProfile.user')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('admin.availabilities.index', ['service' => $service, 'availabilities' => $availabilities]);
    }

    public function create(Service $service): View
    {
        $professionalProfiles = $service->professionalProfiles()->with('user')->get();

        return view('admin.availabilities.create', ['service' => $service, 'professionalProfiles' => $professionalProfiles]);
    }

    public function store(AvailabilityRequest $request, Service $service): RedirectResponse
    {
        $this->availabilityService->create($request->user(), $service, $request->validated());

        return redirect()->route('admin.servicos.horarios.index', $service)->with('status', 'horario-criado');
    }

    public function edit(Service $service, Availability $availability): View
    {
        $professionalProfiles = $service->professionalProfiles()->with('user')->get();

        return view('admin.availabilities.edit', ['service' => $service, 'availability' => $availability, 'professionalProfiles' => $professionalProfiles]);
    }

    public function update(AvailabilityRequest $request, Service $service, Availability $availability): RedirectResponse
    {
        $this->availabilityService->update($request->user(), $availability, $request->validated());

        return redirect()->route('admin.servicos.horarios.index', $service)->with('status', 'horario-actualizado');
    }

    public function destroy(Request $request, Service $service, Availability $availability): RedirectResponse
    {
        $this->availabilityService->delete($request->user(), $availability);

        return redirect()->route('admin.servicos.horarios.index', $service)->with('status', 'horario-removido');
    }
}
