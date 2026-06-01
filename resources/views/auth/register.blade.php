@extends('layouts.app')

@section('title', 'Daftar Akun Pasien - Reservasi Dokter')

@section('auth_bg_svg', '1')

@section('content')
    <style>

        :root {
            --teal-premium: #0D9488;
            --teal-premium-2: #0F766E;
        }

        .register-page {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            max-width: 1100px;
            border-radius: 2.5rem;
        }

        .register-gradient {
            background: linear-gradient(135deg, #2563EB 0%, #22C55E 120%);
        }

        .input-pill {
            border: 0;
            background: #F3F4F6;
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 0.75rem;
        }

        .input-pill:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(29, 155, 240, 0.18);
            background: #fff;
        }

        .btn-teal {
            background: var(--teal-premium);
            color: #fff;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 28px rgba(13, 148, 136, 0.18);
        }

        .btn-teal:hover {
            background: var(--teal-premium-2);
            color: #fff;
        }

        .icon-left {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1rem;
            color: #6b7280;
        }

        .toggle-eye {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 0.75rem;
            border: 0;
            background: transparent;
            color: #6b7280;
        }

        .field-icon-input {
            position: relative;
        }

        .field-icon-input input {
            padding-left: 2.8rem !important;
            padding-right: 2.8rem !important;
        }

        .field-icon-input .toggle-eye {
            padding: 0;
            width: 2.2rem;
            height: 2.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .radio-tile {
            /* border-radius: 999px;
            padding: 0.55rem 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: #F9FAFB;
            transition: all 0.15s ease; */
        }

        .radio-tile input {
            transform: scale(1.1);
        }

        .radio-tile:has(input:checked) {
            /* border-color: rgba(13, 148, 136, 0.55);
            background: rgba(13, 148, 136, 0.08); */
        }

        /* Bento cards right */
        .bento-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 0.9rem;
        }

        .bg-transparent-compass {
            position: absolute;
            inset: -60px -60px auto auto;
            opacity: 0.1;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 2px solid #fff;
            pointer-events: none;
        }
    </style>

<div class="register-page bg-light p-2 p-md-4 auth-root-bg">
        <div class="card border-0 shadow-lg register-card overflow-hidden bg-white">
            <div class="row g-0">
                {{-- Left: Register Form --}}
                <div class="col-12 col-lg-6 p-4 p-md-5 bg-white">
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-heartbeat text-danger me-2" style="font-size: 1.1rem;"></i>
                            <div class="fw-bold">MediReservasi</div>
                        </div>

                        <h2 class="fw-extrabold mb-1">Daftar Akun Pasien</h2>
                        <div class="text-muted small">Buat akun untuk mulai reservasi dokter</div>
                    </div>

                    {{-- Error Alert --}}
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-4 mb-4" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <span class="fw-bold">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.perform') }}">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="field-icon-input">
                                <i class="fas fa-user icon-left"></i>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap sesuai KTP"
                                    class="form-control input-pill"
                                    required
                                >
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="field-icon-input">
                                <i class="fas fa-envelope icon-left"></i>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="email@example.com"
                                    class="form-control input-pill"
                                    required
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password & Konfirmasi (side-by-side) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                                <div class="field-icon-input">
                                    <i class="fas fa-lock icon-left"></i>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="Minimal 8 karakter"
                                        class="form-control input-pill"
                                        required
                                    >
                                    <button type="button" id="togglePassword" class="toggle-eye" aria-label="Toggle password">
                                        <i class="far fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                                <div class="field-icon-input">
                                    <i class="fas fa-lock icon-left"></i>
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Ulangi kata sandi"
                                        class="form-control input-pill"
                                        required
                                    >
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone & Date of Birth --}}
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <div class="field-icon-input">
                                    <i class="fas fa-phone icon-left"></i>
                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="081234567890"
                                        class="form-control input-pill"
                                        required
                                    >
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="date_of_birth" class="form-label fw-semibold">Tanggal Lahir</label>
                                <div class="field-icon-input">
                                    <i class="fas fa-cake-candles icon-left"></i>
                                    <input
                                        id="date_of_birth"
                                        type="date"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth') }}"
                                        class="form-control input-pill"
                                        required
                                    >
                                </div>
                                @error('date_of_birth')
                                    <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-tile d-flex align-items-center gap-2">
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="male"
                                        {{ old('gender') == 'male' ? 'checked' : '' }}
                                    >
                                    <span class="fw-semibold">Laki-laki</span>
                                </label>
                                <label class="radio-tile d-flex align-items-center gap-2">
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="female"
                                        {{ old('gender') == 'female' ? 'checked' : '' }}
                                    >
                                    <span class="fw-semibold">Perempuan</span>
                                </label>
                            </div>
                            @error('gender')
                                <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Address (Opsional) --}}
                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat (Opsional)</label>
                            <div class="position-relative">
                                <i class="fas fa-map-marker-alt" style="position:absolute; left: 1rem; top: 0.9rem; color:#6b7280;"></i>
                                <textarea
                                    id="address"
                                    name="address"
                                    rows="2"
                                    placeholder="Alamat lengkap domisili"
                                    class="form-control input-pill"
                                    style="padding-left: 2.8rem;"
                                >{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-teal w-100 py-3 rounded-4 fw-bold shadow-md">
                            <i class="fas fa-user-plus me-2"></i> Daftar Akun
                        </button>
                    </form>

                    <div class="mt-4 pt-3 text-center border-top">
                        <div class="text-muted small">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="fw-bold" style="color:#2563EB; text-decoration:none;">Masuk sekarang</a>
                        </div>
                    </div>
                </div>

                {{-- Right: Promo (Desktop only) --}}
                <div class="col-12 col-lg-6 d-none d-lg-flex register-gradient text-white p-4 p-md-5 position-relative">
                    <div class="bg-transparent-compass"></div>
                    <div class="d-flex flex-column h-100 justify-content-between w-100">
                        <div>
                            <h2 class="fw-extrabold">Mulai Reservasi Mudah</h2>
                            <div class="text-white-50" style="max-width: 52ch; line-height:1.6;">
                                Daftarkan diri Anda sekarang dan nikmati layanan reservasi dokter tanpa antrian panjang, kapan saja dan di mana saja.
                            </div>
                        </div>

                        <div>
                            <div class="bento-card p-3 mb-3 d-flex align-items-start gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:44px; height:44px; background: rgba(255,255,255,0.12);">
                                    <i class="fas fa-calendar-check text-light fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Booking Instan</div>
                                    <div class="text-white-50 small">Pilih dokter & jadwal favorit</div>
                                </div>
                            </div>

                            <div class="bento-card p-3 mb-3 d-flex align-items-start gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:44px; height:44px; background: rgba(255,255,255,0.12);">
                                    <i class="fas fa-comment-dots text-light fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Notifikasi Otomatis</div>
                                    <div class="text-white-50 small">Ingatkan jadwal periksa Anda</div>
                                </div>
                            </div>

                            <div class="bento-card p-3 mb-0 d-flex align-items-start gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:44px; height:44px; background: rgba(255,255,255,0.12);">
                                    <i class="fas fa-file-medical-alt text-light fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Riwayat Lengkap</div>
                                    <div class="text-white-50 small">Rekam medis digital terjaga</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
@endsection

