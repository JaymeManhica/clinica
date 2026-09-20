@props(['service' => null])

<div>
    <x-input-label for="name" value="Nome do Serviço" />
    <x-text-input id="name" name="name" type="text" value="{{ old('name', $service->name ?? '') }}" required autofocus />
</div>

<div>
    <x-input-label for="description" value="Descrição (opcional)" />
    <textarea id="description" name="description" rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('description', $service->description ?? '') }}</textarea>
</div>

<div>
    <x-input-label for="average_duration_minutes" value="Duração média (minutos)" />
    <x-text-input id="average_duration_minutes" name="average_duration_minutes" type="number" min="5" max="240"
        value="{{ old('average_duration_minutes', $service->average_duration_minutes ?? '') }}" required />
</div>
