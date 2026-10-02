<x-layouts.app title="Gerir Profissionais">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Profissionais de Saúde</h1>
        <a href="{{ route('admin.profissionais.create') }}"
            class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
            Novo Profissional
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nome</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Categoria</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Nº Registo</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Serviços</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Acções</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($professionals as $professional)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="text-gray-800">{{ $professional->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $professional->user->email }} · {{ $professional->user->phone }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $professional->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $professional->registration_number }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $professional->services->pluck('name')->implode(', ') ?: '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $professional->user->active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ $professional->user->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.profissionais.edit', $professional) }}" class="text-emerald-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.profissionais.estado', $professional) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-600 hover:underline">
                                    {{ $professional->user->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Ainda não existem profissionais registados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $professionals->links() }}
    </div>
</x-layouts.app>
