<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // 3. Regenerasi session (penting untuk keamanan) [web:38][web:57]
            $request->session()->regenerate();

            // 4. Arahkan sesuai role user
            $user = Auth::user();

            // Jika kamu sudah punya route ini, tinggal sesuaikan namanya
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'dokter') {
                return redirect()->route('dokter.dashboard');
            }

            // default pasien
            return redirect()->route('pasien.dashboard');
        }

        // 5. Jika gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // keluarkan user yang sedang login [web:57][web:38]

        // invalidate & regenerate session (praktik standar di Laravel baru) [web:57][web:70]
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
