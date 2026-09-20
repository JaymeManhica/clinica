<x-layouts.app :title="$service->name">
    <nav aria-label="Navegação">
        <a href="{{ route('utente.servicos.index') }}"
            class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-emerald-700 transition mb-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 rounded">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5" aria-hidden="true">
                <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
            </svg>
            Voltar aos serviços
        </a>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>

            @if ($service->description)
                <p class="text-gray-600 mt-1.5 max-w-xl">{{ $service->description }}</p>
            @endif

            <p class="inline-flex items-center gap-1.5 text-sm text-emerald-700 font-medium mt-3">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14.5"/>
                </svg>
                Duração média: {{ $service->average_duration_minutes }} minutos
            </p>
        </div>

        <a href="{{ route('utente.agendamentos.create', ['service_id' => $service->id]) }}"
            aria-label="Agendar consulta para {{ $service->name }}"
            class="shrink-0 inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Agendar Consulta
        </a>
    </div>

    <h2 class="font-semibold text-gray-800 mb-3">Horários de Atendimento</h2>

    @if ($availabilities->isEmpty())
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center text-gray-500">
            Ainda não há horários definidos para este serviço. Volte a consultar mais tarde.
        </div>
    @else
        {{-- Desktop: tabela --}}
        <div class="hidden md:block bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Dia da Semana</th>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Início</th>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Fim</th>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Profissional</th>
                        <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Hoje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($availabilities as $availability)
                        <tr class="{{ $availability->isToday() ? 'bg-emerald-50/60' : '' }}">
                            <td class="px-4 py-3 text-gray-800 font-medium">
                                {{ $availability->diaSemanaLabel() }}
                                @if ($availability->isToday())
                                    <span class="ml-1 text-xs text-emerald-700 font-normal">(hoje)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ substr($availability->start_time, 0, 5) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ substr($availability->end_time, 0, 5) }}</td>
                            <td class="px-4 py-3">
                                <x-profissional-card :name="$availability->professionalProfile->user->name" :title="$availability->professionalProfile->title" />
                            </td>
                            <td class="px-4 py-3">
                                @if ($availability->isToday())
                                    @if ($availability->isOpenNow())
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aberto agora</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Fechado</span>
                                    @endif
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile: cartões empilhados --}}
        <div class="md:hidden space-y-3">
            @foreach ($availabilities as $availability)
                <div class="bg-white border rounded-2xl p-4 shadow-sm {{ $availability->isToday() ? 'border-emerald-300 bg-emerald-50/60' : 'border-gray-100' }}">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-gray-800">
                            {{ $availability->diaSemanaLabel() }}
                            @if ($availability->isToday())
                                <span class="text-xs text-emerald-700 font-normal">(hoje)</span>
                            @endif
                        </p>
                        @if ($availability->isToday())
                            @if ($availability->isOpenNow())
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aberto agora</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Fechado</span>
                            @endif
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mb-3">{{ substr($availability->start_time, 0, 5) }} — {{ substr($availability->end_time, 0, 5) }}</p>
                    <x-profissional-card :name="$availability->professionalProfile->user->name" :title="$availability->professionalProfile->title" />
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.app>
