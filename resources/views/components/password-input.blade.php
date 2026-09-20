@props(['id'])

<div class="relative">
    <x-text-input :id="$id" type="password" class="pr-10" {{ $attributes }} />
    <button type="button" class="toggle-password-visibility absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600" data-target="{{ $id }}" tabindex="-1" aria-label="Mostrar/ocultar password">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
        </svg>
    </button>
</div>
