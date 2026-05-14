<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Reservasi Dokter</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --aqua-50: #EBF5FF;
            --aqua-100: #D6EBFF;
            --aqua-200: #ADD8FF;
            --aqua-primary: #23d1c7;
            --aqua-ink: #083a3a;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(180deg, #ffffff 0%, #EAF6FF 30%, #D9FCF8 65%, #ffffff 100%);
            min-height: 100vh;
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 920px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(14, 165, 233, 0.16);
            border-radius: 28px;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .login-form-wrap {
            padding: 2rem;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .login-logo {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: rgba(35, 209, 199, 0.18);
            border: 1px solid rgba(35, 209, 199, 0.28);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--aqua-primary);
            flex: 0 0 auto;
        }

        .login-input {
            background: rgba(15, 23, 42, 0.035);
            border: 1px solid rgba(14, 165, 233, 0.18);
            border-radius: 16px;
            padding: 0.85rem 1rem;
            width: 100%;
            outline: none;
            transition: box-shadow 180ms ease, border-color 180ms ease, background 180ms ease;
        }

        .login-input:focus {
            border-color: rgba(14, 165, 233, 0.45);
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.16);
            background: rgba(255, 255, 255, 0.95);
        }

        .login-label {
            font-weight: 700;
            color: rgba(15, 23, 42, 0.72);
            font-size: 0.92rem;
            margin-bottom: 0.35rem;
        }

        .login-help {
            color: rgba(15, 23, 42, 0.65);
            font-size: 0.92rem;
        }

        .login-divider {
            height: 1px;
            background: rgba(14, 165, 233, 0.18);
            margin: 1.5rem 0;
        }

        .login-illustration {
            padding: 2rem;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.22), transparent 55%),
                radial-gradient(circle at bottom right, rgba(35, 209, 199, 0.18), transparent 45%);
            border-left: 1px solid rgba(14, 165, 233, 0.12);
        }

        @media (max-width: 991.98px) {
            .login-card { border-radius: 22px; }
            .login-illustration { display: none; }
            .login-form-wrap { padding: 1.6rem; }
        }

        /* keep "name"/"id" unchanged as requested */
        .login-icon-input {
            position: relative;
        }

        .login-icon-input .icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(15, 23, 42, 0.45);
        }

        .login-icon-input .icon-right {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: rgba(15, 23, 42, 0.45);
            padding: .25rem;
        }

        .login-icon-input input {
            padding-left: 46px;
        }

        .login-icon-input input[type="password"] {
            padding-right: 46px;
        }

        .login-alert {
            background: rgba(239, 68, 68, 0.10);
            border: 1px solid rgba(239, 68, 68, 0.20);
            border-radius: 16px;
            padding: 0.9rem 1rem;
            color: rgba(185, 28, 28, 1);
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .login-link {
            color: rgba(13, 110, 253, 0.95);
            text-decoration: none;
            font-weight: 700;
        }
        .login-link:hover { text-decoration: underline; }

        .login-btn {
            border-radius: 16px;
            font-weight: 800;
            padding: 0.95rem 1.1rem;
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-card">
            <div class="row g-0">
                {{-- Left: Login form --}}
                <div class="col-12 col-lg-6">
                    <div class="login-form-wrap">
                        <div class="login-brand">
                            <div class="login-logo">
                                <i class="fa-solid fa-stethoscope"></i>
                            </div>
                            <div>
                                <div style="font-weight: 850; font-size: 1.1rem;">Reservasi Dokter</div>
                                <div class="login-help" style="font-size: .9rem;">Masuk untuk melanjutkan</div>
                            </div>
                        </div>

                        {{-- Error Alert (keep @error directive block unchanged) --}}
                        @if(session('error'))
                            <div class="login-alert">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span style="font-weight: 700;">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{-- Login Form (keep method/action, @csrf, @error, name/id unchanged) --}}
                        <form method="POST" action="{{ route('login.perform') }}" class="space-y-5">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="login-label">Email</label>
                                <div class="login-icon-input">
                                    <span class="icon-left"><i class="fa-regular fa-envelope"></i></span>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="masukkan email terdaftar"
                                        class="login-input"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <p class="mt-2" style="color: #dc2626; font-weight: 700; display:flex; gap:8px; align-items:center;">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="login-label">Kata Sandi</label>
                                <div class="login-icon-input">
                                    <span class="icon-left"><i class="fa-solid fa-lock"></i></span>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="masukkan kata sandi"
                                        class="login-input"
                                        required
                                    >
                                    <button
                                        type="button"
                                        id="togglePassword"
                                        class="icon-right"
                                        aria-label="toggle password"
                                    >
                                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2" style="color: #dc2626; font-weight: 700; display:flex; gap:8px; align-items:center;">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            {{-- Remember me + forgot password (no directive/route changes) --}}
                            <div class="d-flex align-items-center justify-content-between mb-4" style="gap: 1rem; flex-wrap: wrap;">
                                <label class="d-flex align-items-center gap-2" style="cursor:pointer; user-select:none;">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        style="width: 18px; height: 18px; accent-color: #23d1c7;"
                                    >
                                    <span class="login-help" style="font-weight:700;">Ingat saya</span>
                                </label>

                                <a href="#" class="login-link">Lupa kata sandi?</a>
                            </div>

                            <button
                                type="submit"
                                class="w-100 login-btn btn-primary btn"
                            >
                                <span class="d-inline-flex align-items-center justify-content-center gap-3">
                                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                    Masuk
                                </span>
                            </button>

                            <div class="login-divider"></div>

                            <div class="text-center">
                                <span class="login-help">Belum punya akun? </span>
                                <a href="{{ route('register') }}" class="login-link">Daftar sebagai pasien</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right: Illustration (desktop only) --}}
                <div class="col-12 col-lg-6">
                    <div class="login-illustration h-100">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <img
                                src="{{ asset('assets/dokter-dokter.jpg') }}"
                                alt="Ilustrasi"
                                loading="lazy"
                                style="width: 52px; height: 52px; object-fit: cover; border-radius: 18px; border: 1px solid rgba(14,165,233,0.18); box-shadow: 0 18px 46px rgba(15,23,42,0.06);"
                                onerror="this.style.display='none'"
                            >
                            <div>
                                <div style="color: rgba(15, 23, 42, 0.92); font-weight: 900; font-size: 1.3rem;">Reservasi Dokter Online</div>
                                <div class="login-help" style="color: rgba(15, 23, 42, 0.68);">Cepat, jelas, dan tanpa antre lama.</div>
                            </div>
                        </div>

                        <div style="display:grid; gap: 18px;">
                            <div class="d-flex align-items-center gap-3" style="padding: 16px; background: rgba(255,255,255,0.55); border: 1px solid rgba(14,165,233,0.14); border-radius: 22px;">
                                <div style="width:46px; height:46px; border-radius: 18px; background: rgba(35,209,199,0.16); border: 1px solid rgba(35,209,199,0.20); display:flex; align-items:center; justify-content:center; color: #0b76ac;">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 900;">Booking Mudah</div>
                                    <div class="login-help">Jadwalkan kunjungan dalam hitungan menit</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3" style="padding: 16px; background: rgba(255,255,255,0.55); border: 1px solid rgba(14,165,233,0.14); border-radius: 22px;">
                                <div style="width:46px; height:46px; border-radius: 18px; background: rgba(35,209,199,0.16); border: 1px solid rgba(35,209,199,0.20); display:flex; align-items:center; justify-content:center; color: #0b76ac;">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 900;">Tidak Perlu Antri</div>
                                    <div class="login-help">Datang sesuai waktu yang ditentukan</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3" style="padding: 16px; background: rgba(255,255,255,0.55); border: 1px solid rgba(14,165,233,0.14); border-radius: 22px;">
                                <div style="width:46px; height:46px; border-radius: 18px; background: rgba(35,209,199,0.16); border: 1px solid rgba(35,209,199,0.20); display:flex; align-items:center; justify-content:center; color: #0b76ac;">
                                    <i class="fa-solid fa-file-medical"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 900;">Rekam Medis Digital</div>
                                    <div class="login-help">Riwayat kesehatan tersimpan aman</div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1.4rem; padding-top: 1rem; border-top: 1px solid rgba(14,165,233,0.16);">
                            <div style="font-style: italic; color: rgba(15, 23, 42, 0.70); font-weight: 700;">"Kesehatan adalah kekayaan yang sebenarnya."</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Password Toggle (kept IDs: togglePassword, eyeIcon, password) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'text') {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>

