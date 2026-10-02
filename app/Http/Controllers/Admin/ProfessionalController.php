<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProfessionalRequest;
use App\Http\Requests\Admin\UpdateProfessionalRequest;
use App\Models\ProfessionalProfile;
use App\Models\Service;
use App\Services\ProfessionalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalController extends Controller
{
    public function __construct(private readonly ProfessionalService $professionalService) {}

    public function index(): View
    {
        $professionals = ProfessionalProfile::query()
            ->with(['user', 'services'])
            ->join('users', 'users.id', '=', 'professional_profiles.user_id')
            ->orderBy('users.name')
            ->select('professional_profiles.*')
            ->paginate(15);

        return view('admin.professionals.index', ['professionals' => $professionals]);
    }

    public function create(): View
    {
        return view('admin.professionals.create', [
            'services' => Service::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProfessionalRequest $request): RedirectResponse
    {
        $this->professionalService->create($request->user(), $request->validated());

        return redirect()->route('admin.profissionais.index')->with('status', 'profissional-criado');
    }

    public function edit(ProfessionalProfile $professional): View
    {
        return view('admin.professionals.edit', [
            'professional' => $professional->load(['user', 'services']),
            'services' => Service::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProfessionalRequest $request, ProfessionalProfile $professional): RedirectResponse
    {
        $this->professionalService->update($request->user(), $professional, $request->validated());

        return redirect()->route('admin.profissionais.index')->with('status', 'profissional-actualizado');
    }

    public function toggleActive(Request $request, ProfessionalProfile $professional): RedirectResponse
    {
        $this->professionalService->toggleActive($request->user(), $professional);

        return back()->with('status', 'profissional-estado-alterado');
    }
}
