<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function edit(Request $request): View
    {
        $this->authorize('update', $request->user());

        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->authService->updateProfile($request->user(), $request->validated());

        return back()->with('status', 'perfil-actualizado');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->authService->changePassword($request->user(), $request->validated('password'));

        return back()->with('status', 'password-actualizada');
    }
}
