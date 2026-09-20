<x-layouts.app title="Agendar Consulta">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Agendar Consulta</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-xl">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
                <x-input-error :messages="$errors->all()" />
            </div>
        @endif

        <form method="POST" action="{{ route('utente.agendamentos.store') }}" id="agendamento-form" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="service_id" value="Serviço" />
                <select id="service_id" name="service_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Seleccione...</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->name }} ({{ $service->average_duration_minutes }} min)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="professional_profile_id" value="Profissional" />
                <select id="professional_profile_id" name="professional_profile_id" required disabled
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Seleccione primeiro o serviço</option>
                </select>
            </div>

            <div>
                <x-input-label for="date" value="Data" />
                <x-text-input id="date" name="date" type="date" min="{{ now()->toDateString() }}" required />
            </div>

            <div>
                <x-input-label value="Horários Disponíveis" />
                <div id="slots-container" class="flex flex-wrap gap-2 mt-2">
                    <p class="text-sm text-gray-500">Seleccione o serviço, o profissional e a data.</p>
                </div>
                <input type="hidden" name="time" id="time">
            </div>

            <x-primary-button id="submit-btn" disabled>Confirmar Agendamento</x-primary-button>
        </form>
    </div>

    <script>
        const servicesData = @json($professionalsByService);

        const serviceSelect = document.getElementById('service_id');
        const professionalSelect = document.getElementById('professional_profile_id');
        const dateInput = document.getElementById('date');
        const slotsContainer = document.getElementById('slots-container');
        const timeInput = document.getElementById('time');
        const submitBtn = document.getElementById('submit-btn');

        function resetSlots(message) {
            slotsContainer.innerHTML = `<p class="text-sm text-gray-500">${message}</p>`;
            timeInput.value = '';
            submitBtn.disabled = true;
        }

        function populateProfessionals() {
            const professionals = servicesData[serviceSelect.value] || [];

            professionalSelect.innerHTML = '<option value="">Seleccione...</option>';
            professionals.forEach((profissional) => {
                const option = document.createElement('option');
                option.value = profissional.id;
                option.textContent = profissional.name;
                professionalSelect.appendChild(option);
            });

            professionalSelect.disabled = professionals.length === 0;
            resetSlots('Seleccione o profissional e a data.');
        }

        serviceSelect.addEventListener('change', populateProfessionals);

        if (serviceSelect.value) {
            populateProfessionals();
        }

        async function loadSlots() {
            if (!serviceSelect.value || !professionalSelect.value || !dateInput.value) {
                return;
            }

            resetSlots('A carregar horários...');

            const params = new URLSearchParams({
                service_id: serviceSelect.value,
                professional_profile_id: professionalSelect.value,
                date: dateInput.value,
            });

            const response = await fetch(`{{ route('utente.agendamentos.horarios') }}?${params.toString()}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                resetSlots('Não foi possível carregar os horários. Verifique a data seleccionada.');
                return;
            }

            const data = await response.json();

            if (!data.slots || data.slots.length === 0) {
                resetSlots('Não há horários disponíveis nesta data.');
                return;
            }

            slotsContainer.innerHTML = '';
            data.slots.forEach((slot) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = slot;
                button.className = 'px-3 py-1.5 rounded-md border border-gray-300 text-sm hover:border-emerald-500';
                button.addEventListener('click', () => {
                    document.querySelectorAll('#slots-container button').forEach((b) => {
                        b.classList.remove('bg-emerald-700', 'text-white', 'border-emerald-700');
                    });
                    button.classList.add('bg-emerald-700', 'text-white', 'border-emerald-700');
                    timeInput.value = slot;
                    submitBtn.disabled = false;
                });
                slotsContainer.appendChild(button);
            });
        }

        professionalSelect.addEventListener('change', loadSlots);
        dateInput.addEventListener('change', loadSlots);
    </script>
</x-layouts.app>
