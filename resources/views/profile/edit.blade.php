<x-layouts.app title="Meu Perfil">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Meu Perfil</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-lg mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Dados Pessoais</h2>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="name" value="Nome completo" />
                <x-text-input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
            </div>

            <div>
                <x-input-label for="phone" value="Telefone" />
                <x-text-input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" required />
            </div>

            <x-primary-button>Guardar Alterações</x-primary-button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6 max-w-lg">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Alterar Password</h2>

        <form method="POST" action="{{ route('perfil.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="current_password" value="Password Actual" />
                <x-password-input id="current_password" name="current_password" required />
            </div>

            <div>
                <x-input-label for="password" value="Nova Password" />
                <x-password-input id="password" name="password" required />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar Nova Password" />
                <x-password-input id="password_confirmation" name="password_confirmation" required />
            </div>

            <x-primary-button>Alterar Password</x-primary-button>
        </form>
    </div>
</x-layouts.app>
