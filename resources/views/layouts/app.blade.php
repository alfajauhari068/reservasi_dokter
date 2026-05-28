<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reservasi Dokter')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/fluid-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/clinical-dashboard.css') }}">


    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .bg-soft-blue {
            background-color: #F8FAFC;
        }

        .rounded-3xl {
            border-radius: 1.5rem !important;
        }

        .rounded-4xl {
            border-radius: 2rem !important;
        }
    </style>
</head>

<body>
<div class="ds-app-shell">
    {{-- Background effect layer: purely decorative --}}
    <div class="ds-background">
    <div class="ds-gradient"></div>
    <div class="ds-grid"></div>
    <div class="ds-orb ds-orb-1"></div>
    <div class="ds-orb ds-orb-2"></div>
    <!-- Opsional: Tambahkan overlay noise untuk kesan tekstur halus jika diinginkan -->
</div>

    <nav class="ds-navbar navbar navbar-expand-lg">
        <div class="@hasSection('fullwidth') container-fluid px-0 @else container ds-container @endif">
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

            <!-- <button
                class="navbar-toggler border-0 text-primary d-inline-flex d-lg-none"
                type="button"
                id="mobileSidebarToggle"
                aria-label="Toggle sidebar"
            >
                <i class="fas fa-bars"></i>
            </button> -->

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

                   
                </ul>

                @auth
                    <div class="d-flex align-items-center ms-4 gap-3">
                        @include('components.notification-dropdown')

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

    <main class="ds-main @hasSection('fullwidth') ds-main-fullwidth @endif">
        @hasSection('fullwidth')
            @yield('content')
        @else
            <div class="container-fluid ds-container fluid-dashboard-container">

                <div class="ds-content-card">

                    @yield('content')
                </div>
            </div>
        @endif
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle helper for mobile phone view.
    // If admin sidebar exists on the page (admin layout), toggle it.
    // Otherwise do nothing.
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobileSidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('mobileOverlay');

        if (!btn || !sidebar) return;

        btn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        });

        const navLinks = sidebar.querySelectorAll('.admin-nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('show');
                }
            });
        });
    });
</script>
</body>
</html>
</body>
</html>

