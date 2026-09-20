<x-layouts.app title="Gerir Serviços">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Serviços de Saúde</h1>
        <a href="{{ route('admin.servicos.create') }}"
            class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
            Novo Serviço
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nome</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Duração Média</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Horários</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Acções</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($services as $service)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $service->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $service->average_duration_minutes }} min</td>
                        <td class="px-4 py-3 text-gray-600">{{ $service->availabilities_count }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $service->active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ $service->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.servicos.horarios.index', $service) }}" class="text-emerald-700 hover:underline">Horários</a>
                            <a href="{{ route('admin.servicos.edit', $service) }}" class="text-emerald-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.servicos.estado', $service) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-600 hover:underline">
                                    {{ $service->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</x-layouts.app>
