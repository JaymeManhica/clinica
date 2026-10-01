<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateQueueMetricsRequest;
use App\Models\QueueMetricSnapshot;
use App\Models\Service;
use App\Services\QueueTheoryService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QueueTheoryController extends Controller
{
    public function __construct(private readonly QueueTheoryService $queueTheory) {}

    public function index(): View
    {
        $services = Service::query()->orderBy('name')->get();

        $snapshots = QueueMetricSnapshot::query()
            ->with('service')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('admin.indicadores.index', [
            'services' => $services,
            'snapshots' => $snapshots,
        ]);
    }

    public function store(GenerateQueueMetricsRequest $request): RedirectResponse|View
    {
        $service = Service::findOrFail($request->integer('service_id'));

        $resultado = $this->queueTheory->generateSnapshots(
            $request->user(),
            $service,
            Carbon::parse($request->string('period_start')->toString()),
            Carbon::parse($request->string('period_end')->toString()),
        );

        return view('admin.indicadores.resultado', [
            'service' => $service,
            'observado' => $resultado['observado'],
            'calculado' => $resultado['calculado'],
        ]);
    }
}
