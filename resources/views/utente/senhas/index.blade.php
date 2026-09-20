<x-layouts.app title="Minhas Senhas">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Minhas Senhas</h1>
            <p class="text-gray-500 mt-1">Acompanhar a fila, pedir prioridade ou cancelar uma senha.</p>
        </div>
        <a href="{{ route('utente.senhas.create') }}"
            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tirar Senha
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($tickets as $ticket)
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 11v2M13 17v2"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $ticket->status->badgeClasses() }}">
                        {{ $ticket->status->label() }}
                    </span>
                </div>

                <p class="text-lg font-bold text-gray-900">{{ $ticket->ticket_number }}</p>
                <p class="text-sm text-gray-500 mb-3">{{ $ticket->service->name }}</p>

                @if (isset($positions[$ticket->id]) && $positions[$ticket->id])
                    <p class="text-sm text-gray-700">
                        Posição na fila: <span class="font-semibold text-emerald-700">#{{ $positions[$ticket->id] }}</span>
                    </p>
                @endif

                @if ($ticket->priorityRecord)
                    <span class="inline-block mt-2 px-2.5 py-1 rounded-full text-xs font-medium {{ $ticket->priorityRecord->status->badgeClasses() }}">
                        Prioridade: {{ $ticket->priorityRecord->status->label() }}
                    </span>
                @endif

                @if ($ticket->status->value === 'emitida')
                    <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                        @if (! $ticket->priorityRecord)
                            <details>
                                <summary class="cursor-pointer text-sm font-medium text-emerald-700 hover:underline">Pedir Prioridade</summary>
                                <form method="POST" action="{{ route('utente.senhas.prioridade', $ticket) }}" class="mt-2 space-y-2">
                                    @csrf
                                    <textarea name="reason" rows="2" required placeholder="Motivo do pedido de prioridade"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                                    <button type="submit" class="text-sm bg-emerald-700 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-800">Enviar</button>
                                </form>
                            </details>
                        @endif
                        <form method="POST" action="{{ route('utente.senhas.cancel', $ticket) }}"
                            onsubmit="return confirm('Cancelar esta senha?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Cancelar</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 shadow-sm rounded-2xl p-10 text-center text-gray-500">
                Ainda não tem senhas.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</x-layouts.app>
