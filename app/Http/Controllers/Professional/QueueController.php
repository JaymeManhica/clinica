<?php

namespace App\Http\Controllers\Professional;

use App\Enums\PriorityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Professional\CallNextRequest;
use App\Models\PriorityRecord;
use App\Models\QueueTicket;
use App\Models\Service;
use App\Services\QueueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function __construct(private readonly QueueService $queue) {}

    public function index(Request $request): View
    {
        $profile = $request->user()->professionalProfile;
        $services = $profile->services()->orderBy('name')->get();

        $selectedServiceId = $request->integer('service_id') ?: $services->first()?->id;
        $selectedService = $services->firstWhere('id', $selectedServiceId);

        $tickets = $selectedService ? $this->queue->queueForService($selectedService) : collect();

        $pendingPriorities = PriorityRecord::query()
            ->whereHas('queueTicket', fn ($query) => $query->whereIn('service_id', $services->pluck('id')))
            ->where('status', PriorityStatus::Pendente)
            ->with(['queueTicket.user', 'queueTicket.service'])
            ->get();

        return view('professional.filas.index', [
            'services' => $services,
            'selectedService' => $selectedService,
            'tickets' => $tickets,
            'pendingPriorities' => $pendingPriorities,
        ]);
    }

    public function callNext(CallNextRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->integer('service_id'));
        $profile = $request->user()->professionalProfile;

        $ticket = $this->queue->callNext($request->user(), $profile, $service);

        return redirect()
            ->route('profissional.fila.index', ['service_id' => $service->id])
            ->with('status', $ticket ? 'senha-chamada' : 'fila-vazia');
    }

    public function start(Request $request, QueueTicket $ticket): RedirectResponse
    {
        $this->authorize('manage', $ticket);

        $this->queue->startAttendance($request->user(), $ticket);

        return back()->with('status', 'atendimento-iniciado');
    }

    public function finish(Request $request, QueueTicket $ticket): RedirectResponse
    {
        $this->authorize('manage', $ticket);

        $this->queue->finishAttendance($request->user(), $ticket);

        return back()->with('status', 'atendimento-concluido');
    }

    public function noShow(Request $request, QueueTicket $ticket): RedirectResponse
    {
        $this->authorize('manage', $ticket);

        $this->queue->markNoShow($request->user(), $ticket);

        return back()->with('status', 'desistencia-registada');
    }

    public function confirmPriority(Request $request, PriorityRecord $priority): RedirectResponse
    {
        $this->authorize('confirm', $priority);

        $this->queue->confirmPriority($request->user(), $priority, true);

        return back()->with('status', 'prioridade-confirmada');
    }

    public function rejectPriority(Request $request, PriorityRecord $priority): RedirectResponse
    {
        $this->authorize('confirm', $priority);

        $this->queue->confirmPriority($request->user(), $priority, false);

        return back()->with('status', 'prioridade-rejeitada');
    }
}
