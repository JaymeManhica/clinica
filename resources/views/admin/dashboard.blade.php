<x-layouts.app title="Painel do Administrador">
    <h1 class="text-xl font-semibold text-gray-800 mb-2">Bem-vindo(a), {{ auth()->user()->name }}</h1>
    <p class="text-gray-600 mb-6">
        Este é o painel de administração. A gestão de utilizadores, profissionais e indicadores
        será implementada nas próximas partes do sistema.
    </p>

    <div class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('admin.servicos.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Serviços de Saúde</h2>
            <p class="text-sm text-gray-500 mt-1">Gerir o catálogo de serviços e os horários de atendimento.</p>
        </a>

        <a href="{{ route('admin.agendamentos.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Agendamentos</h2>
            <p class="text-sm text-gray-500 mt-1">Consultar todos os agendamentos da unidade, com filtros por serviço, estado e data.</p>
        </a>

        <a href="{{ route('admin.filas.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Filas em Tempo Real</h2>
            <p class="text-sm text-gray-500 mt-1">Acompanhar o estado das filas de todos os serviços.</p>
        </a>

        <a href="{{ route('admin.indicadores.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Indicadores (Teoria das Filas)</h2>
            <p class="text-sm text-gray-500 mt-1">Calcular λ, μ, ρ, L, Lq, W e Wq — modelo M/M/1, observado vs calculado.</p>
        </a>
    </div>
</x-layouts.app>
