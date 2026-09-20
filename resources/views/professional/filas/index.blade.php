<x-layouts.app title="Fila de Atendimento">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Fila de Atendimento</h1>

    @if ($services->count() > 1)
        <form method="GET" action="{{ route('profissional.fila.index') }}" class="mb-6">
            <x-input-label for="service_id" value="Serviço" />
            <select id="service_id" name="service_id" onchange="this.form.submit()"
                class="mt-1 rounded-md border-gray-300 shadow-sm text-sm">
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected($selectedService?->id === $service->id)>{{ $service->name }}</option>
                @endforeach
            </select>
        </form>
    @endif

    @if ($pendingPriorities->isNotEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
            <h2 class="font-semibold text-amber-800 mb-3">Pedidos de Prioridade Pendentes</h2>
            <ul class="space-y-3">
                @foreach ($pendingPriorities as $priority)
                    <li class="text-sm bg-white rounded-md p-3 border border-amber-100">
                        <p><strong>{{ $priority->queueTicket->user->name }}</strong> — {{ $priority->queueTicket->service->name }} ({{ $priority->queueTicket->ticket_number }})</p>
                        <p class="text-gray-600 mt-1">Motivo: {{ $priority->reason }}</p>
                        <div class="mt-2 space-x-3">
                            <form method="POST" action="{{ route('profissional.prioridades.confirmar', $priority) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-emerald-700 hover:underline">Confirmar</button>
                            </form>
                            <form method="POST" action="{{ route('profissional.prioridades.rejeitar', $priority) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-600 hover:underline">Rejeitar</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($selectedService)
        <form method="POST" action="{{ route('profissional.fila.chamar') }}" class="mb-6">
            @csrf
            <input type="hidden" name="service_id" value="{{ $selectedService->id }}">
            <button type="submit" class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                Chamar Próximo
            </button>
        </form>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Senha</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Utente</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Prioridade</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Emitida às</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Acções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-3 text-gray-800 font-semibold">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $ticket->user->name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $ticket->priority_level->label() }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $ticket->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $ticket->issued_at->format('H:i') }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                @if ($ticket->status->value === 'chamada')
                                    <form method="POST" action="{{ route('profissional.senhas.iniciar', $ticket) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-700 hover:underline">Iniciar</button>
                                    </form>
                                    <form method="POST" action="{{ route('profissional.senhas.desistencia', $ticket) }}" class="inline"
                                        onsubmit="return confirm('Registar desistência deste utente?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:underline">Desistência</button>
                                    </form>
                                @elseif ($ticket->status->value === 'em_atendimento')
                                    <form method="POST" action="{{ route('profissional.senhas.concluir', $ticket) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-700 hover:underline">Concluir</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Não há senhas activas nesta fila.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">Não está associado a nenhum serviço.</p>
    @endif
</x-layouts.app>
