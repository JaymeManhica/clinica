<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUtenteRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterUtenteRequest $request): RedirectResponse
    {
        $user = $this->authService->registerUtente($request->validated());

        Auth::login($user);

        return redirect()->route('utente.dashboard');
    }
}
