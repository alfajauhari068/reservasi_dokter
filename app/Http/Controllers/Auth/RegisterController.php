<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi pasien.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pasien baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Buat user dengan role pasien
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => User::ROLE_PASIEN,
                ]);

                // Buat profil pasien
                Patient::create([
                    'user_id' => $user->id,
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    // blood_type & identity_number optional, can be updated later
                ]);
            });

            // Auto-login setelah registrasi berhasil
            $user = User::where('email', $validated['email'])->first();
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended(route('pasien.dashboard'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Registrasi gagal. Silakan coba lagi.',
            ])->onlyInput();
        }
    }
}

