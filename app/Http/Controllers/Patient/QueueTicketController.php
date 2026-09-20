<?php

namespace App\Http\Controllers\Patient;

use App\Enums\QueueTicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\RequestPriorityRequest;
use App\Http\Requests\Patient\StoreQueueTicketRequest;
use App\Models\Appointment;
use App\Models\QueueTicket;
use App\Models\Service;
use App\Services\QueueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueTicketController extends Controller
{
    public function __construct(private readonly QueueService $queue) {}

    public function index(Request $request): View
    {
        $tickets = $request->user()->queueTickets()
            ->with(['service', 'professionalProfile.user', 'priorityRecord'])
            ->orderByDesc('issued_at')
            ->paginate(10);

        $positions = [];

        foreach ($tickets as $ticket) {
            if ($ticket->status === QueueTicketStatus::Emitida) {
                $positions[$ticket->id] = $this->queue->positionOf($ticket);
            }
        }

        return view('utente.senhas.index', ['tickets' => $tickets, 'positions' => $positions]);
    }

    public function createSpontaneous(): View
    {
        $services = Service::query()->where('active', true)->orderBy('name')->get();

        return view('utente.senhas.create', ['services' => $services]);
    }

    public function storeSpontaneous(StoreQueueTicketRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->integer('service_id'));

        $this->queue->issueSpontaneous($request->user(), $service);

        return redirect()->route('utente.senhas.index')->with('status', 'senha-emitida');
    }

    public function checkin(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('view', $appointment);

        $this->queue->issueFromAppointment($request->user(), $appointment);

        return redirect()->route('utente.senhas.index')->with('status', 'checkin-feito');
    }

    public function requestPriority(RequestPriorityRequest $request, QueueTicket $ticket): RedirectResponse
    {
        $this->queue->requestPriority($request->user(), $ticket, $request->string('reason')->toString());

        return back()->with('status', 'prioridade-solicitada');
    }

    public function cancel(Request $request, QueueTicket $ticket): RedirectResponse
    {
        $this->authorize('cancel', $ticket);

        $this->queue->cancel($request->user(), $ticket);

        return back()->with('status', 'senha-cancelada');
    }
}
