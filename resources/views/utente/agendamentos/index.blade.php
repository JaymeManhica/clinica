<x-layouts.app title="Meus Agendamentos">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Meus Agendamentos</h1>
            <p class="text-gray-500 mt-1">Consultar, fazer check-in ou cancelar as suas consultas.</p>
        </div>
        <a href="{{ route('utente.agendamentos.create') }}"
            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Novo Agendamento
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($appointments as $appointment)
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m9 16 2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $appointment->status->badgeClasses() }}">
                        {{ $appointment->status->label() }}
                    </span>
                </div>

                <p class="text-lg font-bold text-gray-900">{{ $appointment->scheduled_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-500 mb-3">{{ $appointment->scheduled_at->format('H:i') }}</p>

                <p class="text-sm font-medium text-gray-800">{{ $appointment->service->name }}</p>
                <p class="text-sm text-gray-500">{{ $appointment->professionalProfile->user->name }}</p>

                @if ($appointment->canCheckin() || $appointment->isCancelable())
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-4">
                        @if ($appointment->canCheckin())
                            <form method="POST" action="{{ route('utente.agendamentos.checkin', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-emerald-700 hover:underline">Fazer Check-in</button>
                            </form>
                        @endif
                        @if ($appointment->isCancelable())
                            <form method="POST" action="{{ route('utente.agendamentos.cancel', $appointment) }}"
                                onsubmit="return confirm('Tem a certeza que deseja cancelar este agendamento?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Cancelar</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center text-gray-500">
                Ainda não tem agendamentos.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
</x-layouts.app>
