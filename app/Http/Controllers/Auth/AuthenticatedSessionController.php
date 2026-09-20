<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $this->authService->attemptLogin(
            $request->string('identifier')->toString(),
            $request->string('password')->toString(),
        );

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathFor(Auth::user()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectPathFor(User $user): string
    {
        return match ($user->role) {
            UserRole::Utente => route('utente.dashboard'),
            UserRole::Profissional => route('profissional.dashboard'),
            UserRole::Administrador => route('admin.dashboard'),
        };
    }
}
