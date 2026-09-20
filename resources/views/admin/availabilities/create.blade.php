<x-layouts.app :title="'Novo Horário — '.$service->name">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Novo Horário — {{ $service->name }}</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-lg">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('admin.servicos.horarios.store', $service) }}" class="space-y-4">
            @csrf

            @include('admin.availabilities._form', ['professionalProfiles' => $professionalProfiles])

            <x-primary-button>Criar Horário</x-primary-button>
        </form>
    </div>
</x-layouts.app>
