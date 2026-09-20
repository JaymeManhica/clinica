<x-layouts.app title="Agendamentos">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Agendamentos</h1>

    <form method="GET" action="{{ route('admin.agendamentos.index') }}" class="bg-white shadow rounded-lg p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <x-input-label for="service_id" value="Serviço" />
            <select id="service_id" name="service_id" class="mt-1 rounded-md border-gray-300 shadow-sm text-sm">
                <option value="">Todos</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="status" value="Estado" />
            <select id="status" name="status" class="mt-1 rounded-md border-gray-300 shadow-sm text-sm">
                <option value="">Todos</option>
                @foreach (\App\Enums\AppointmentStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="date" value="Data" />
            <x-text-input id="date" name="date" type="date" value="{{ request('date') }}" />
        </div>

        <div>
            <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                Filtrar
            </button>
            <a href="{{ route('admin.agendamentos.index') }}" class="text-sm text-gray-500 hover:underline ml-2">Limpar</a>
        </div>
    </form>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Data/Hora</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Utente</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Serviço</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Profissional</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $appointment->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $appointment->service->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $appointment->professionalProfile->user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $appointment->status->label() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Nenhum agendamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</x-layouts.app>
