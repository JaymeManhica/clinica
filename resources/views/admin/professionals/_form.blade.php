@props(['services', 'professional' => null])

<div>
    <x-input-label for="name" value="Nome Completo" />
    <x-text-input id="name" name="name" type="text" value="{{ old('name', $professional?->user->name ?? '') }}" required autofocus />
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" value="{{ old('email', $professional?->user->email ?? '') }}" required />
    </div>

    <div>
        <x-input-label for="phone" value="Telefone" />
        <x-text-input id="phone" name="phone" type="text" value="{{ old('phone', $professional?->user->phone ?? '') }}" required />
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="title" value="Categoria / Especialidade" />
        <x-text-input id="title" name="title" type="text" placeholder="Ex.: Enfermeiro, Médico de Clínica Geral"
            value="{{ old('title', $professional->title ?? '') }}" required />
    </div>

    <div>
        <x-input-label for="registration_number" value="Nº de Registo Profissional" />
        <x-text-input id="registration_number" name="registration_number" type="text"
            value="{{ old('registration_number', $professional->registration_number ?? '') }}" required />
    </div>
</div>

<div>
    <x-input-label value="Serviços que presta" />
    @php($selected = collect(old('services', $professional?->services->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id))
    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
        @forelse ($services as $service)
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="services[]" value="{{ $service->id }}" @checked($selected->contains($service->id))
                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                {{ $service->name }}
                @unless ($service->active)
                    <span class="text-xs text-gray-400">(inactivo)</span>
                @endunless
            </label>
        @empty
            <p class="text-sm text-amber-600">Ainda não existem serviços registados.</p>
        @endforelse
    </div>
</div>

<div class="border-t border-gray-100 pt-4">
    @if ($professional)
        <p class="text-sm text-gray-500 mb-2">Deixe em branco para manter a password actual.</p>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label for="password" value="{{ $professional ? 'Nova Password' : 'Password' }}" />
            <x-password-input id="password" name="password" autocomplete="new-password" :required="! $professional" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar Password" />
            <x-password-input id="password_confirmation" name="password_confirmation" autocomplete="new-password" :required="! $professional" />
        </div>
    </div>
</div>
