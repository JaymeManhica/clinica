<x-layouts.app title="Tirar Senha">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Tirar Senha</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-lg">
        <p class="text-sm text-gray-500 mb-4">
            Use esta opção se se dirigiu directamente ao posto de saúde, sem agendamento prévio.
        </p>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('utente.senhas.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="service_id" value="Serviço" />
                <select id="service_id" name="service_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Seleccione...</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Tirar Senha</x-primary-button>
        </form>
    </div>
</x-layouts.app>
