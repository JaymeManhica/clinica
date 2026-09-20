<x-layouts.app title="Novo Serviço">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Novo Serviço</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-lg">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('admin.servicos.store') }}" class="space-y-4">
            @csrf

            @include('admin.services._form')

            <x-primary-button>Criar Serviço</x-primary-button>
        </form>
    </div>
</x-layouts.app>
