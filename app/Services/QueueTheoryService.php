<?php

namespace App\Services;

use App\Enums\MetricType;
use App\Models\QueueMetricSnapshot;
use App\Models\QueueTicket;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Calcula indicadores da Teoria das Filas (modelo M/M/1) para um serviço,
 * num determinado período, distinguindo sempre:
 *
 * - "Observado": medido directamente a partir dos registos reais de
 *   queue_tickets (tempos de espera e de atendimento efectivos), usando a
 *   Lei de Little (L = λW), que é válida para qualquer sistema em regime
 *   estacionário, independentemente da distribuição das chegadas/serviços.
 *
 * - "Calculado": aplica as fórmulas fechadas do modelo M/M/1 (que assume
 *   chegadas de Poisson e tempos de serviço exponenciais) a partir das
 *   taxas λ e μ observadas, para obter os valores teóricos de L, Lq, W e Wq.
 */
class QueueTheoryService
{
    public function __construct(private readonly AuditService $auditService) {}

    /**
     * @return array{observado: QueueMetricSnapshot, calculado: QueueMetricSnapshot}
     */
    public function generateSnapshots(User $actor, Service $service, Carbon $start, Carbon $end): array
    {
        return DB::transaction(function () use ($actor, $service, $start, $end) {
            $observedData = $this->observe($service, $start, $end);
            $theoreticalData = $this->calculateTheoretical($observedData['lambda'], $observedData['mu']);

            $observado = $this->store($service, $start, $end, $observedData, MetricType::Observado);
            $calculado = $this->store($service, $start, $end, $theoreticalData, MetricType::Calculado);

            $this->auditService->log($actor, 'queue_metrics.generated', $calculado, [], [
                'service_id' => $service->id,
                'period_start' => $start->toDateTimeString(),
                'period_end' => $end->toDateTimeString(),
            ]);

            return ['observado' => $observado, 'calculado' => $calculado];
        });
    }

    /**
     * Mede λ, μ, ρ, L, Lq, W e Wq directamente a partir dos dados reais.
     *
     * @return array{lambda: float, mu: float, rho: float, l: float, lq: float, w: float, wq: float}
     */
    private function observe(Service $service, Carbon $start, Carbon $end): array
    {
        $periodHours = max($start->diffInSeconds($end) / 3600, 0.0167); // mínimo de 1 minuto

        $tickets = QueueTicket::query()
            ->where('service_id', $service->id)
            ->whereBetween('issued_at', [$start, $end])
            ->get();

        if ($tickets->isEmpty()) {
            throw ValidationException::withMessages([
                'period' => 'Não existem senhas emitidas para este serviço no período seleccionado.',
            ]);
        }

        $lambda = $tickets->count() / $periodHours;

        $atendidas = $tickets->filter(fn (QueueTicket $t) => $t->started_at !== null && $t->finished_at !== null);

        if ($atendidas->isEmpty()) {
            throw ValidationException::withMessages([
                'period' => 'Não existem atendimentos concluídos para este serviço no período seleccionado, pelo que não é possível estimar o tempo de serviço (μ).',
            ]);
        }

        $avgServiceHours = $atendidas->avg(fn (QueueTicket $t) => $t->started_at->diffInSeconds($t->finished_at) / 3600);
        $mu = 1 / max($avgServiceHours, 0.0001);

        $comChamada = $tickets->filter(fn (QueueTicket $t) => $t->called_at !== null);
        $avgWaitHours = $comChamada->isNotEmpty()
            ? $comChamada->avg(fn (QueueTicket $t) => max($t->issued_at->diffInSeconds($t->called_at), 0) / 3600)
            : 0.0;

        $avgSystemHours = $atendidas->avg(fn (QueueTicket $t) => $t->issued_at->diffInSeconds($t->finished_at) / 3600);

        // Lei de Little (universal, não assume nenhuma distribuição): L = λ * W
        $l = $lambda * $avgSystemHours;
        $lq = $lambda * $avgWaitHours;
        $rho = $lambda / $mu;

        return [
            'lambda' => $lambda,
            'mu' => $mu,
            'rho' => $rho,
            'l' => $l,
            'lq' => $lq,
            'w' => $avgSystemHours,
            'wq' => $avgWaitHours,
        ];
    }

    /**
     * Aplica as fórmulas fechadas do modelo M/M/1 a partir de λ e μ.
     *
     * @return array{lambda: float, mu: float, rho: float, l: float, lq: float, w: float, wq: float}
     */
    private function calculateTheoretical(float $lambda, float $mu): array
    {
        if ($lambda <= 0 || $mu <= 0) {
            throw ValidationException::withMessages([
                'period' => 'Taxas de chegada/atendimento inválidas para aplicar o modelo M/M/1.',
            ]);
        }

        $rho = $lambda / $mu;

        if ($rho >= 1) {
            throw ValidationException::withMessages([
                'period' => sprintf(
                    'O sistema está instável neste período (ρ = %.2f ≥ 1): a taxa de chegada excede a capacidade de atendimento do serviço. O modelo M/M/1 não é aplicável a estas condições.',
                    $rho
                ),
            ]);
        }

        return [
            'lambda' => $lambda,
            'mu' => $mu,
            'rho' => $rho,
            'l' => $rho / (1 - $rho),
            'lq' => ($rho ** 2) / (1 - $rho),
            'w' => 1 / ($mu - $lambda),
            'wq' => $rho / ($mu - $lambda),
        ];
    }

    /**
     * @param  array{lambda: float, mu: float, rho: float, l: float, lq: float, w: float, wq: float}  $data
     */
    private function store(Service $service, Carbon $start, Carbon $end, array $data, MetricType $type): QueueMetricSnapshot
    {
        return QueueMetricSnapshot::query()->create([
            'service_id' => $service->id,
            'period_start' => $start,
            'period_end' => $end,
            'lambda' => round($data['lambda'], 4),
            'mu' => round($data['mu'], 4),
            'rho' => round($data['rho'], 4),
            'l' => round($data['l'], 4),
            'lq' => round($data['lq'], 4),
            'w' => round($data['w'] * 60, 4), // horas -> minutos
            'wq' => round($data['wq'] * 60, 4), // horas -> minutos
            'type' => $type,
        ]);
    }
}
