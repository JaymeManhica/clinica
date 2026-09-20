<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QueueTicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->where('active', true)->orderBy('name')->get();

        $summary = $services->map(fn (Service $service) => [
            'service' => $service,
            'aguardando' => $service->queueTickets()->where('status', QueueTicketStatus::Emitida)->count(),
            'chamadas' => $service->queueTickets()->where('status', QueueTicketStatus::Chamada)->count(),
            'em_atendimento' => $service->queueTickets()->where('status', QueueTicketStatus::EmAtendimento)->count(),
            'concluidas_hoje' => $service->queueTickets()->where('status', QueueTicketStatus::Concluida)->whereDate('finished_at', today())->count(),
        ]);

        return view('admin.filas.index', ['summary' => $summary]);
    }
}
