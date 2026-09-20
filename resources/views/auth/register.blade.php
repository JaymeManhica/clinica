<x-layouts.guest title="Criar Conta">
    <div class="card">
        <h2>Criar conta de utente</h2>
        <p class="sub">Preencha os seus dados para começar a agendar consultas.</p>

        <form method="POST" action="{{ route('registo.store') }}">
            @csrf

            <div class="field @error('name') has-error @enderror">
                <label for="name">Nome completo</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="O seu nome" required autofocus>
                </div>
                @error('name')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field @error('email') has-error @enderror">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nome@exemplo.com" required>
                </div>
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field @error('phone') has-error @enderror">
                <label for="phone">Telefone</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.7a2 2 0 0 1-.4 2.1L8 9.9a16 16 0 0 0 6 6l1.4-1.4a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.7.6a2 2 0 0 1 1.8 2.1z"/></svg>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+258 8xx xxx xxx" required>
                </div>
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field @error('password') has-error @enderror">
                <label for="password">Palavra-passe</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                    <button type="button" class="toggle-pass" data-toggle="password" aria-label="Mostrar palavra-passe">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="hint">Use pelo menos 8 caracteres, com letras e números.</div>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmar palavra-passe</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repita a palavra-passe" required>
                    <button type="button" class="toggle-pass" data-toggle="password_confirmation" aria-label="Mostrar palavra-passe">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="submit">Registar</button>
        </form>

        <p class="foot">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
</x-layouts.guest>
