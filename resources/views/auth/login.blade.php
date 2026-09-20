<x-layouts.guest title="Entrar">
    <div class="card">
        <h2>Entrar na sua conta</h2>
        <p class="sub">Aceda com o email ou telefone associado à sua conta.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="field @error('identifier') has-error @enderror">
                <label for="identifier">Email ou Telefone</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" placeholder="nome@exemplo.com ou telefone" required autofocus>
                </div>
                @error('identifier')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field @error('password') has-error @enderror">
                <label for="password">Palavra-passe</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password" placeholder="A sua palavra-passe" required>
                    <button type="button" class="toggle-pass" data-toggle="password" aria-label="Mostrar palavra-passe">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="submit">Entrar</button>
        </form>

        <p class="foot">Ainda não tem conta? <a href="{{ route('registo') }}">Registe-se</a></p>
    </div>
</x-layouts.guest>
