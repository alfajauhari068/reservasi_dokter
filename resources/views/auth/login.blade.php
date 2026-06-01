@extends('layouts.app')

@section('title', 'Masuk - Reservasi Dokter')

@section('auth_bg_svg', '1')

@section('content')
<style>
        /* Modern premium login UI (Blade internal CSS) */
        .login-page-wrapper {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Subtle ambient background + grid */
        .login-page-bg {
            background: #F8FAFC;
            position: relative;
            overflow: hidden;
        }
        .login-page-bg::before {
            content: "";
            position: absolute;
            inset: -60px;
            background:
                radial-gradient(circle at 15% 20%, rgba(14,165,233,0.14), transparent 38%),
                radial-gradient(circle at 85% 30%, rgba(99,102,241,0.12), transparent 42%),
                linear-gradient(180deg, rgba(255,255,255,0.0), rgba(255,255,255,0.55));
            pointer-events: none;
        }
        .login-page-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, rgba(15,23,42,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15,23,42,0.05) 1px, transparent 1px);
            background-size: 44px 44px;
            opacity: 0.35;
            pointer-events: none;
        }
        .login-page-wrapper > * {
            position: relative;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 1024px;
            border-radius: 20px;
            border: 1px solid rgba(148,163,184,0.20);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05);
        }

        .bg-gradient-login {
            background: linear-gradient(135deg, #2563EB, #4F46E5);
        }

        .btn-back {
            transition: color 160ms ease, background-color 160ms ease, border-color 160ms ease, transform 160ms ease;
        }
        .btn-back:hover {
            transform: translateY(-1px);
        }

        /* Input styling for premium feel */
        .login-premium-input .input-group-text {
            background: transparent;
            border: 1px solid rgba(148,163,184,0.25);
            border-right: 0;
            border-radius: 14px 0 0 14px;
            color: #64748b;
        }
        .login-premium-input .form-control {
            border: 1px solid rgba(148,163,184,0.25);
            border-left: 0;
            border-radius: 0 14px 14px 0;
            padding: 12px 14px;
            background: rgba(248,250,252,0.75);
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }
        .login-premium-input .form-control:focus {
            border-color: #0EA5E9;
            box-shadow: 0 0 0 4px rgba(14,165,233,0.18);
            background: #ffffff;
        }

        .login-premium-hint {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Buttons */
        .login-btn {
            min-height: 44px;
            border-radius: 999px !important;
            font-weight: 800;
            letter-spacing: 0.01em;
            transition: transform 160ms ease, box-shadow 160ms ease, background 160ms ease;
            box-shadow: 0 12px 26px rgba(37,99,235,0.20);
        }
        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(37,99,235,0.26);
        }

        @media (max-width: 576px) {
            .login-card {
                border-radius: 16px;
            }
            .login-card .card-body,
            .login-card {
                max-width: 100%;
            }
        }
    </style>

<div class="login-page-wrapper login-page-bg p-2 auth-root-bg">
        <div class="card border-0 shadow-lg login-card">
            <div class="row g-0">
                {{-- Left: Login form (5 grid) --}}
                <div class="col-12 col-lg-5 p-4 p-sm-5 bg-white">
                    <div class="h-500 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-4 gap-3 flex-wrap">
                            <a href="{{ url('/') }}" class="btn btn-sm btn-light border btn-back" style="color:#6b7280; border-radius: 999px;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>

                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-heartbeat text-primary animate-pulse"></i>
                                <div class="fw-bold" style="letter-spacing: .2px;">MediReservasi</div>
                            </div>
                        </div>

                        {{-- Error Alert --}}
                        @if(session('error'))
                            <div class="alert alert-danger border-0 rounded-4 mb-4" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                <span style="font-weight: 700;">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('login') }}" class="mt-1">
                            @csrf

                            <div class="mb-3 login-premium-input">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="masukkan email terdaftar"
                                        class="form-control"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 login-premium-input">
                                <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="masukkan kata sandi"
                                        class="form-control"
                                        required
                                    >
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3 mb-4 flex-wrap gap-3">
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer; user-select:none;">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input" />
                                    <span class="fw-semibold" style="font-size: 0.95rem;">Ingat Saya</span>
                                </label>

                                <a href="#" class="text-decoration-none fw-semibold" style="color:#2563EB; font-size: 0.92rem;">
                                    Lupa kata sandi?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold" style="border-radius: 1.25rem;">
                                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>
                                Masuk
                            </button>

                            <div class="mt-4 text-center">
                                <div class="fw-semibold" style="color:#6b7280;">Belum punya akun?</div>
                                <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color:#2563EB;">
                                    Daftar sebagai pasien
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right: Bento Clinic Highlights (desktop only) --}}
                <div class="col-12 col-lg-7 p-4 p-sm-5 d-none d-lg-flex bg-gradient-login position-relative overflow-hidden">
                    <div class="w-100 h-100 d-flex flex-column justify-content-center position-relative" style="z-index: 1;">
                        <div class="mb-4">
                            <div class="text-white-50 fw-semibold" style="letter-spacing:.12em; text-transform:uppercase; font-size:.85rem;">
                                Clinic Highlights
                            </div>
                            <div class="text-white fw-bold" style="font-size: 1.8rem; line-height:1.1;">
                                Reservasi Dokter Online
                            </div>
                            <div class="text-white-50 mt-2" style="max-width: 48ch;">
                                Cepat, jelas, dan tanpa antre lama.
                            </div>
                        </div>

                        <div class="w-100">
                            <div class="mb-3 bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3" style="width:48px; height:48px; background: rgba(255,255,255,0.16); display:flex; align-items:center; justify-content:center;">
                                        <i class="fas fa-calendar-alt text-white" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="font-size: 1.15rem;">Kalender Terstruktur</div>
                                        <div class="text-white-50" style="margin-top: .2rem;">Pilih jadwal yang tersedia secara real-time.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3" style="width:48px; height:48px; background: rgba(255,255,255,0.16); display:flex; align-items:center; justify-content:center;">
                                        <i class="fas fa-clock text-white" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="font-size: 1.15rem;">Jam Otomatis</div>
                                        <div class="text-white-50" style="margin-top: .2rem;">Pengingat membantu Anda datang tepat waktu.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3" style="width:48px; height:48px; background: rgba(255,255,255,0.16); display:flex; align-items:center; justify-content:center;">
                                        <i class="fas fa-file-medical text-white" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="font-size: 1.15rem;">Rekam Medis Digital</div>
                                        <div class="text-white-50" style="margin-top: .2rem;">Data tersimpan lebih rapi dan mudah diakses.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-white-50 fw-semibold" style="font-size: .95rem;">
                                Mulai reservasi sekarang dan rasakan pengalaman yang lebih nyaman.
                            </div>
                        </div>
                    </div>

                    <img
                        src="{{ asset('assets/hero_pasien.jpg') }}"
                        alt="Doctor hero"
                        style="position: absolute; bottom: -6%; right: -10%; height: 100%; width: auto; object-fit: contain; max-width: 80%; opacity: 0.1; pointer-events: none; z-index: 0;"
                        onerror="this.style.display='none'"
                    >
                </div>
            </div>
        </div>
    </div>

    <script>
        // Optional Client-side Role Autodetect Preview
        // Jika email mengandung 'dokter' atau 'dr', maka beri hint UI (tanpa mengubah backend role)
        (function () {
            const emailInput = document.getElementById('email');
            if (!emailInput) return;

            const hintId = 'role-autodetect-hint';
            function ensureHint() {
                let el = document.getElementById(hintId);
                if (el) return el;

                el = document.createElement('div');
                el.id = hintId;
                el.className = 'mt-2 text-end';
                el.style.color = '#2563EB';
                el.style.fontWeight = '700';
                el.style.fontSize = '.9rem';
                emailInput.parentElement.parentElement.appendChild(el);
                return el;
            }

            function applyHint() {
                const hint = ensureHint();
                const v = (emailInput.value || '').toLowerCase();

                if (!v) {
                    hint.textContent = '';
                    return;
                }

                const isDoctor = v.includes('dokter') || v.includes('@dr') || v.includes(' dr ') || v.includes('dr.');
                hint.textContent = isDoctor ? 'Hint: kemungkinan akun role Dokter' : 'Hint: kemungkinan akun role Pasien';
            }

            emailInput.addEventListener('input', applyHint);
            applyHint();
        })();
    </script>
@endsection


