<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reservasi Dokter')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Landing-bright wrapper styles (no backend/route changes) */
        body {
            background: linear-gradient(180deg, #f4fbff 0%, #f8ffff 55%, #ffffff 100%);
            color: #0f172a;
            font-family: 'Figtree', sans-serif;
        }

        .ds-app-shell {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .ds-app-shell::before {
            content: "";
            position: absolute;
            inset: -200px -100px auto -100px;
            height: 520px;
            background: radial-gradient(circle at top left, rgba(14, 165, 233, 0.22), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(20, 184, 166, 0.20), transparent 50%);
            pointer-events: none;
        }

        .ds-navbar {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            min-height: 76px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .ds-navbar .navbar-brand {
            color: #0f172a;
            font-weight: 800;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ds-navbar .brand-subtitle {
            display: block;
            font-size: 0.85rem;
            color: rgba(15, 23, 42, 0.6);
            font-weight: 600;
            margin-top: -2px;
        }

        .ds-navbar .nav-link {
            color: rgba(15, 23, 42, 0.78);
            font-weight: 650;
        }

        .ds-navbar .nav-link:hover {
            color: #0f172a;
        }

        .ds-main {
            padding: 2rem 1rem 4rem;
            position: relative;
            z-index: 1;
        }

        .ds-container {
            max-width: 1100px;
        }

        .ds-content-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(14, 165, 233, 0.12);
            border-radius: 28px;
            box-shadow: 0 26px 60px rgba(15, 23, 42, 0.06);
            padding: 1.75rem;
        }

        @media (max-width: 768px) {
            .ds-content-card { padding: 1.25rem; }
        }
    </style>
</head>
<body>
<div class="ds-app-shell">
    <nav class="ds-navbar navbar navbar-expand-lg">
        <div class="container ds-container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{-- Prefer asset logo if exists; fallback text-only handled by layout --}}
                <img
                    src="{{ asset('assets/dokter1.jpg') }}"
                    alt="Reservasi Dokter"
                    width="36"
                    height="36"
                    style="object-fit: cover; border-radius: 14px; border: 1px solid rgba(14,165,233,0.18);"
                    onerror="this.style.display='none'"
                >
                <span>
                    Reservasi Dokter
                    <span class="brand-subtitle">Platform reservasi kesehatan modern</span>
                </span>
            </a>

            <button class="navbar-toggler border-0 text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        @auth
                            @if (auth()->user()->role === 'admin')
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Beranda</a>
                            @elseif (auth()->user()->role === 'dokter')
                                <a class="nav-link" href="{{ route('dokter.dashboard') }}">Beranda</a>
                            @else
                                <a class="nav-link" href="{{ route('pasien.dashboard') }}">Beranda</a>
                            @endif
                        @endauth
                    </li>

                    {{-- Right-side CTA: show one action for guests, keep existing logout logic for authenticated users --}}
                    @guest
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-primary" style="border-radius: 999px; padding: .7rem 1.2rem;" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-secondary" style="border-radius: 999px; padding: .7rem 1.2rem;" href="{{ url('/') }}">
                                Reservasi
                            </a>
                        </li>
                    @endauth
                </ul>

                @auth
                    <div class="d-flex align-items-center ms-4">
                        <span class="me-3 text-dark" style="font-weight: 650;">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm-ds" style="border-radius: 999px;">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="ds-main">
        <div class="container ds-container">
            <div class="ds-content-card">
                @yield('content')
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
