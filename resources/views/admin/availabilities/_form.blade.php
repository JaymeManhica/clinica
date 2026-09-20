@props(['professionalProfiles', 'availability' => null])

<div>
    <x-input-label for="professional_profile_id" value="Profissional" />
    <select id="professional_profile_id" name="professional_profile_id" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
        <option value="">Seleccione...</option>
        @foreach ($professionalProfiles as $profile)
            <option value="{{ $profile->id }}" @selected(old('professional_profile_id', $availability->professional_profile_id ?? '') == $profile->id)>
                {{ $profile->user->name }} ({{ $profile->title }})
            </option>
        @endforeach
    </select>
    @if ($professionalProfiles->isEmpty())
        <p class="mt-1 text-sm text-amber-600">Nenhum profissional está associado a este serviço.</p>
    @endif
</div>

<div>
    <x-input-label for="day_of_week" value="Dia da Semana" />
    <select id="day_of_week" name="day_of_week" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
        @foreach (\App\Models\Availability::diasSemana() as $value => $label)
            <option value="{{ $value }}" @selected((string) old('day_of_week', $availability->day_of_week ?? '') === (string) $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="start_time" value="Hora de Início" />
        <x-text-input id="start_time" name="start_time" type="time"
            value="{{ old('start_time', $availability ? substr($availability->start_time, 0, 5) : '') }}" required />
    </div>

    <div>
        <x-input-label for="end_time" value="Hora de Fim" />
        <x-text-input id="end_time" name="end_time" type="time"
            value="{{ old('end_time', $availability ? substr($availability->end_time, 0, 5) : '') }}" required />
    </div>
</div>
