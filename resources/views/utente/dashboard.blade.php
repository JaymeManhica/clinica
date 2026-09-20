<x-layouts.app title="Painel do Utente">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Bem-vindo(a), {{ auth()->user()->name }}</h1>
        <p class="text-gray-500 mt-1">Este é o seu painel de utente.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

        <a href="{{ route('utente.servicos.index') }}"
            class="group bg-white border border-gray-100 shadow-sm rounded-2xl p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-200 transition-all">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition">
                <svg viewBox="0 0 24 24" fill="none" stroke="#0f6b4f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 flex items-center justify-between">
                Serviços Disponíveis
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </h2>
            <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">Consultar os serviços de saúde e os respectivos horários de atendimento.</p>
        </a>

        <a href="{{ route('utente.agendamentos.index') }}"
            class="group bg-white border border-gray-100 shadow-sm rounded-2xl p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-200 transition-all">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                <svg viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m9 16 2 2 4-4"/>
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 flex items-center justify-between">
                Meus Agendamentos
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </h2>
            <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">Agendar uma consulta ou consultar/cancelar os seus agendamentos.</p>
        </a>

        <a href="{{ route('utente.senhas.index') }}"
            class="group bg-white border border-gray-100 shadow-sm rounded-2xl p-5 hover:shadow-lg hover:-translate-y-0.5 hover:border-emerald-200 transition-all">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                <svg viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 11v2M13 17v2"/>
                </svg>
            </div>
            <h2 class="font-semibold text-gray-900 flex items-center justify-between">
                Minhas Senhas
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </h2>
            <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">Fazer check-in, tirar senha, acompanhar a fila e pedir prioridade.</p>
        </a>

    </div>
</x-layouts.app>
