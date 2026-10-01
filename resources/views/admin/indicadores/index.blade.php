<x-layouts.app title="Indicadores de Teoria das Filas">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Indicadores de Teoria das Filas</h1>
        <p class="text-gray-500 mt-1">
            Modelo M/M/1 — calcula λ, μ, ρ, L, Lq, W e Wq para um serviço num determinado período,
            comparando sempre os valores <strong>observados</strong> (medidos nos dados reais) com os
            valores <strong>calculados</strong> (fórmulas teóricas do modelo M/M/1).
        </p>
    </div>

    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-8 max-w-2xl">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('admin.indicadores.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="service_id" value="Serviço" />
                <select id="service_id" name="service_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Seleccione...</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="period_start" value="Início do Período" />
                    <x-text-input id="period_start" name="period_start" type="datetime-local"
                        value="{{ old('period_start', now()->startOfDay()->format('Y-m-d\TH:i')) }}" required />
                </div>
                <div>
                    <x-input-label for="period_end" value="Fim do Período" />
                    <x-text-input id="period_end" name="period_end" type="datetime-local"
                        value="{{ old('period_end', now()->format('Y-m-d\TH:i')) }}" required />
                </div>
            </div>

            <x-primary-button>Calcular Indicadores</x-primary-button>
        </form>
    </div>

    <h2 class="font-semibold text-gray-800 mb-3">Últimos Cálculos</h2>

    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Serviço</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Período</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tipo</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">ρ</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">L</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Lq</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">W (min)</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Wq (min)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($snapshots as $snapshot)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $snapshot->service->name }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $snapshot->period_start->format('d/m H:i') }} — {{ $snapshot->period_end->format('d/m H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $snapshot->type->value === 'calculado' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $snapshot->type->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($snapshot->rho, 3) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($snapshot->l, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($snapshot->lq, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($snapshot->w, 1) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ number_format($snapshot->wq, 1) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Ainda não foram calculados indicadores.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
