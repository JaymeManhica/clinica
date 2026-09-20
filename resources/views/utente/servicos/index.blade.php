<x-layouts.app title="Serviços Disponíveis">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Serviços Disponíveis</h1>
        <p class="text-gray-500 mt-1">Consultar os serviços de saúde e os respectivos horários de atendimento.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($services as $service)
            <a href="{{ route('utente.servicos.show', $service) }}"
                class="group bg-white border border-gray-100 shadow-sm rounded-2xl p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-200 transition-all">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0f6b4f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                        <path d="M12 21s-7-4.6-9.5-9.1C.7 8.1 2.4 4.5 6 4c2-.3 3.6.7 4.9 2.3C12.2 4.7 13.8 3.7 15.8 4c3.6.5 5.3 4.1 3.5 7.9C16.8 16.4 12 21 12 21z"/>
                        <path d="M9 12h2l1-2 1 4 1-2h1"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-gray-900 flex items-center justify-between">
                    {{ $service->name }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </h2>
                @if ($service->description)
                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">{{ $service->description }}</p>
                @endif
                <p class="text-sm text-emerald-700 mt-3 font-medium">Duração média: {{ $service->average_duration_minutes }} min</p>
            </a>
        @empty
            <div class="col-span-full bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center text-gray-500">
                Não há serviços disponíveis de momento.
            </div>
        @endforelse
    </div>
</x-layouts.app>
