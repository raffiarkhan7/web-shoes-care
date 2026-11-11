<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user instanceof User) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akun Anda sedang dinonaktifkan. Hubungi admin Shaos Care.',
            ]);
        }

        if (! in_array($user->role, [User::ROLE_PEMBELI, User::ROLE_PENJUAL], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Gunakan halaman login sesuai role akun Anda.',
            ]);
        }

        return redirect()
            ->intended(route($user->dashboardRoute()))
            ->with('success', 'Selamat datang kembali, '.$user->name.'!');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in([User::ROLE_PEMBELI, User::ROLE_PENJUAL])],
            'shop_name' => ['required_if:role,'.User::ROLE_PENJUAL, 'nullable', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'shop_name' => $data['shop_name'] ?? null,
            'address' => $data['address'],
            'password' => $data['password'],
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $message = $user->isPenjual()
            ? 'Toko Anda berhasil dibuat. Silakan tambahkan layanan pertama Anda.'
            : 'Akun berhasil dibuat. Yuk, pesan layanan cuci sepatu pertamamu!';

        return redirect()->route($user->dashboardRoute())->with('success', $message);
    }

    public function logout(Request $request): RedirectResponse
    {
        $redirectRoute = $request->user()?->isAdmin() ? 'admin.login' : 'login';
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute)->with('success', 'Anda berhasil keluar.');
    }
}
