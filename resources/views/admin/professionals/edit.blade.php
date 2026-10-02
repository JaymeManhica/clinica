<x-layouts.app title="Editar Profissional">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Editar Profissional de Saúde</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('admin.profissionais.update', $professional) }}" class="space-y-4">
            @csrf
            @method('PUT')

            @include('admin.professionals._form', ['services' => $services, 'professional' => $professional])

            <x-primary-button>Guardar Alterações</x-primary-button>
        </form>
    </div>
</x-layouts.app>
