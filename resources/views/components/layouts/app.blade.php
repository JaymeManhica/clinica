<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Minha Saúde EI' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-emerald-800 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                        <path d="M12 21s-7-4.6-9.5-9.1C.7 8.1 2.4 4.5 6 4c2-.3 3.6.7 4.9 2.3C12.2 4.7 13.8 3.7 15.8 4c3.6.5 5.3 4.1 3.5 7.9C16.8 16.4 12 21 12 21z"/>
                    </svg>
                </div>
                <span class="font-semibold tracking-tight">Minha Saúde EI</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold">
                        {{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->map(fn ($n) => $n[0])->take(2)->implode('') }}
                    </div>
                    <span class="text-sm text-emerald-50">{{ auth()->user()->name }} <span class="text-emerald-300">({{ auth()->user()->role->label() }})</span></span>
                </div>

                <a href="{{ route('perfil.edit') }}" class="text-sm text-emerald-100 hover:text-white transition">Meu Perfil</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-emerald-700 hover:bg-emerald-600 transition px-3 py-1.5 rounded-md flex items-center gap-1.5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                {{ match (session('status')) {
                    'perfil-actualizado' => 'Os seus dados foram actualizados com sucesso.',
                    'password-actualizada' => 'A sua password foi actualizada com sucesso.',
                    'servico-criado' => 'Serviço criado com sucesso.',
                    'servico-actualizado' => 'Serviço actualizado com sucesso.',
                    'servico-estado-alterado' => 'Estado do serviço alterado com sucesso.',
                    'profissional-criado' => 'Profissional registado com sucesso.',
                    'profissional-actualizado' => 'Dados do profissional actualizados com sucesso.',
                    'profissional-estado-alterado' => 'Estado do profissional alterado com sucesso.',
                    'horario-criado' => 'Horário criado com sucesso.',
                    'horario-actualizado' => 'Horário actualizado com sucesso.',
                    'horario-removido' => 'Horário removido com sucesso.',
                    'agendamento-criado' => 'Consulta agendada com sucesso.',
                    'agendamento-cancelado' => 'O agendamento foi cancelado.',
                    'senha-emitida' => 'A sua senha foi emitida com sucesso.',
                    'checkin-feito' => 'Check-in efectuado. A sua senha foi emitida.',
                    'senha-cancelada' => 'A senha foi cancelada.',
                    'prioridade-solicitada' => 'O seu pedido de prioridade foi registado e aguarda confirmação.',
                    'senha-chamada' => 'Senha chamada com sucesso.',
                    'fila-vazia' => 'Não há senhas à espera nesta fila.',
                    'atendimento-iniciado' => 'Atendimento iniciado.',
                    'atendimento-concluido' => 'Atendimento concluído.',
                    'desistencia-registada' => 'Desistência registada.',
                    'prioridade-confirmada' => 'Prioridade confirmada com sucesso.',
                    'prioridade-rejeitada' => 'Pedido de prioridade rejeitado.',
                    default => session('status'),
                } }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
        document.addEventListener('click', function (event) {
            const button = event.target.closest('.toggle-password-visibility');

            if (! button) {
                return;
            }

            const input = document.getElementById(button.dataset.target);

            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        });
    </script>
</body>
</html>
