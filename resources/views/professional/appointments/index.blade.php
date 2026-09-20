<x-layouts.app title="Minha Agenda">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Minha Agenda</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Data/Hora</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Utente</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Serviço</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $appointment->user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $appointment->service->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $appointment->status->label() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Não há consultas agendadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</x-layouts.app>
