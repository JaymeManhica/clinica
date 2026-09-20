<x-layouts.app :title="'Horários — '.$service->name">
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-xl font-semibold text-gray-800">Horários de {{ $service->name }}</h1>
        <a href="{{ route('admin.servicos.horarios.create', $service) }}"
            class="inline-flex items-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
            Novo Horário
        </a>
    </div>
    <p class="text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.servicos.index') }}" class="hover:underline">&larr; Voltar aos serviços</a>
    </p>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Dia da Semana</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Início</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Fim</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Profissional</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500">Acções</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($availabilities as $availability)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $availability->diaSemanaLabel() }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ substr($availability->start_time, 0, 5) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ substr($availability->end_time, 0, 5) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $availability->professionalProfile->user->name }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.servicos.horarios.edit', [$service, $availability]) }}" class="text-emerald-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.servicos.horarios.destroy', [$service, $availability]) }}" class="inline"
                                onsubmit="return confirm('Remover este horário?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Nenhum horário definido para este serviço.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
