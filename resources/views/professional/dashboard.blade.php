<x-layouts.app title="Painel do Profissional">
    <h1 class="text-xl font-semibold text-gray-800 mb-2">Bem-vindo(a), {{ auth()->user()->name }}</h1>
    <p class="text-gray-600 mb-6">
        Este é o seu painel de profissional de saúde.
    </p>

    <div class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('profissional.agenda.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Minha Agenda</h2>
            <p class="text-sm text-gray-500 mt-1">Consultar as suas próximas consultas agendadas.</p>
        </a>

        <a href="{{ route('profissional.fila.index') }}" class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
            <h2 class="font-semibold text-gray-800">Fila de Atendimento</h2>
            <p class="text-sm text-gray-500 mt-1">Chamar o próximo utente, gerir o atendimento e confirmar pedidos de prioridade.</p>
        </a>
    </div>
</x-layouts.app>
