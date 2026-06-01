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

    @hasSection('auth_bg_svg')
        <style>
            /* Auth pages: futuristic medical SVG background */
            .auth-root-bg {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1000%26quot%3b)' fill='none'%3e%3crect width='1440' height='560' x='0' y='0' fill='%230e2a47'%3e%3c/rect%3e%3cpath d='M0%2c513.449C94.847%2c533.727%2c193.727%2c479.607%2c268.611%2c417.967C339.429%2c359.674%2c378.636%2c272.125%2c401.186%2c183.216C421.213%2c104.254%2c404.748%2c23.985%2c388.8%2c-55.901C373.665%2c-131.716%2c352.384%2c-204.144%2c310.84%2c-269.344C263.821%2c-343.136%2c218.899%2c-435.268%2c133.884%2c-455.969C48.854%2c-476.673%2c-26.4%2c-400.191%2c-109.112%2c-371.6C-186.94%2c-344.698%2c-274.995%2c-345.907%2c-337.98%2c-292.862C-405.94%2c-235.627%2c-471.942%2c-156.42%2c-470.089%2c-67.589C-468.247%2c20.707%2c-379.023%2c77.529%2c-328.737%2c150.129C-288.346%2c208.443%2c-251.307%2c264.386%2c-203.714%2c316.986C-139.111%2c388.385%2c-94.16%2c493.318%2c0%2c513.449' fill='%230b2239'%3e%3c/path%3e%3cpath d='M1440 864.866C1523.273 871.8679999999999 1600.003 961.1669999999999 1677.113 928.956 1751.687 897.804 1777.266 799.237 1788.652 719.2239999999999 1798.65 648.969 1756.099 585.447 1735.6680000000001 517.489 1718.635 460.836 1714.275 400.44100000000003 1678.619 353.236 1641.512 304.111 1587.268 274.136 1531.75 247.52800000000002 1467.141 216.563 1401.673 180.426 1330.267 186.284 1251.549 192.74200000000002 1168.1770000000001 220.01600000000002 1118.512 281.429 1069.2060000000001 342.398 1083.344 429.51300000000003 1070.955 506.939 1058.33 585.835 1014.976 666.342 1045.077 740.355 1075.3809999999999 814.869 1150.482 866.938 1227.199 891.125 1296.772 913.06 1367.308 858.754 1440 864.866' fill='%23113255'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1000'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3e%3c/defs%3e%3c/svg%3e");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
    @endif

    <link rel="stylesheet" href="{{ asset('css/clinical-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pasien-bento.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pasien-portal.css') }}">


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

        .brand-subtitle {
            display: block;
            font-size: 0.75rem;
            color: #64748B;
            font-weight: 500;
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

    <nav class="ds-navbar navbar navbar-expand-lg px-4">
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
                <ul class="navbar-nav ms-auto align-items-center" style="z-index: 1050;">
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

                    {{-- Right-side CTA: Login/Daftar only on Welcome (guest) --}}
                    @guest
                        @if(request()->is('/') || request()->is('welcome') || request()->routeIs('welcome'))
                            <li class="nav-item ms-lg-3">
                                <a class="btn btn-outline-primary btn-lg" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>
                            <li class="nav-item ms-3">
                                <a class="btn btn-primary btn-lg" href="{{ route('register') }}">
                                    Daftar
                                </a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-3">
                                <a class="btn btn-primary btn-lg" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>
                        @endif
                    @endguest

                   
                </ul>

                @auth
                    <div class="d-flex align-items-center ms-4 gap-3">
                        @include('components.notification-dropdown')

                        <span class="me-3 text-dark" style="font-weight: 650;">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm-ds border border-slate-200 text-slate-700 font-bold hover:bg-rose-50">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="ds-main  @hasSection('fullwidth') ds-main-fullwidth @endif">
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

