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
    @vite('resources/css/app.css')
</head>
<body>
<div class="ds-app-shell">
    <nav class="ds-navbar navbar navbar-expand-lg">
        <div class="container ds-container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{-- Prefer asset logo if exists; fallback text-only handled by layout --}}
                <img
                    class="brand-logo"
                    src="{{ asset('assets/dokter1.jpg') }}"
                    alt="Reservasi Dokter"
                    width="36"
                    height="36"
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
                            <a class="btn btn-primary btn-lg" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-secondary btn-lg" href="{{ url('/') }}">
                                Reservasi
                            </a>
                        </li>
                        @include('components.notification-dropdown')
                    @endauth
                </ul>

                @auth
                    <div class="d-flex align-items-center ms-4">
                        <span class="me-3 text-dark" style="font-weight: 650;">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm-ds">
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
