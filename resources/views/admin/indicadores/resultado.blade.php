<x-layouts.app title="Resultado dos Indicadores">
    <p class="text-sm text-gray-500 mb-4">
        <a href="{{ route('admin.indicadores.index') }}" class="hover:underline">&larr; Voltar</a>
    </p>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
        <p class="text-gray-500 mt-1">
            Período: {{ $observado->period_start->format('d/m/Y H:i') }} — {{ $observado->period_end->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        @foreach (['observado' => $observado, 'calculado' => $calculado] as $tipo => $snapshot)
            <div class="bg-white border rounded-2xl p-6 shadow-sm {{ $tipo === 'calculado' ? 'border-blue-200' : 'border-emerald-200' }}">
                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $tipo === 'calculado' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                    {{ $snapshot->type->label() }}
                </span>

                <p class="text-xs text-gray-500 mt-3 mb-4">
                    @if ($tipo === 'observado')
                        Medido directamente a partir dos tempos reais de espera e atendimento registados no período (Lei de Little).
                    @else
                        Fórmulas teóricas do modelo M/M/1, aplicadas às taxas λ e μ observadas neste período.
                    @endif
                </p>

                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-gray-500">λ (chegadas/hora)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->lambda, 3) }}</dd>

                    <dt class="text-gray-500">μ (atendimentos/hora)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->mu, 3) }}</dd>

                    <dt class="text-gray-500">ρ (utilização)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->rho, 3) }}</dd>

                    <dt class="text-gray-500">L (nº médio no sistema)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->l, 2) }}</dd>

                    <dt class="text-gray-500">Lq (nº médio na fila)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->lq, 2) }}</dd>

                    <dt class="text-gray-500">W (tempo no sistema)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->w, 1) }} min</dd>

                    <dt class="text-gray-500">Wq (tempo de espera)</dt>
                    <dd class="font-semibold text-gray-900">{{ number_format($snapshot->wq, 1) }} min</dd>
                </dl>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
        <strong>Nota de leitura:</strong> quanto mais próximos os valores "Observado" e "Calculado", melhor o modelo
        M/M/1 aproxima o comportamento real da fila deste serviço. Diferenças grandes sugerem que as chegadas ou os
        tempos de atendimento não seguem as distribuições assumidas pelo modelo (Poisson / exponencial).
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.indicadores.index') }}"
            class="inline-flex items-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 transition">
            Calcular Outro Período
        </a>
    </div>
</x-layouts.app>
