<x-layouts.app title="Filas em Tempo Real">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Filas em Tempo Real</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Serviço</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">A Aguardar</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Chamadas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Em Atendimento</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Concluídas Hoje</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($summary as $row)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $row['service']->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $row['aguardando'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $row['chamadas'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $row['em_atendimento'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $row['concluidas_hoje'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Não há serviços activos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="text-xs text-gray-400 mt-4">A página não é actualizada automaticamente — recarregue para ver o estado mais recente.</p>
</x-layouts.app>
