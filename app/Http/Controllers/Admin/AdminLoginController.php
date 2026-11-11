<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->isAdmin() && auth()->user()->is_active) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([...$credentials, 'role' => 'admin'], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'Email atau password admin tidak sesuai.']);
        }

        $request->session()->regenerate();
        if (! $request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages(['email' => 'Akun admin sedang dinonaktifkan.']);
        }

        return redirect()->intended(route('admin.dashboard'));
    }
}
