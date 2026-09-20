<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceCatalogController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('utente.servicos.index', ['services' => $services]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->active, 404);

        $availabilities = $service->availabilities()
            ->where('active', true)
            ->with('professionalProfile.user')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('utente.servicos.show', ['service' => $service, 'availabilities' => $availabilities]);
    }
}
