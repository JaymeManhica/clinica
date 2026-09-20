<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private readonly ServiceCatalogService $serviceCatalog) {}

    public function index(): View
    {
        $services = Service::query()
            ->withCount('availabilities')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.services.index', ['services' => $services]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->serviceCatalog->create($request->user(), $request->validated());

        return redirect()->route('admin.servicos.index')->with('status', 'servico-criado');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->serviceCatalog->update($request->user(), $service, $request->validated());

        return redirect()->route('admin.servicos.index')->with('status', 'servico-actualizado');
    }

    public function toggleActive(Request $request, Service $service): RedirectResponse
    {
        $this->serviceCatalog->toggleActive($request->user(), $service);

        return back()->with('status', 'servico-estado-alterado');
    }
}
