{{-- Layout khusus untuk Admin Dashboard --}}
{{-- File: resources/views/layouts/admin.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - Reservasi Dokter')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Admin Styling (external CSS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin_bento_extra.css') }}">


    @stack('styles')
</head>
<body>
    <div class="admin-shell">

        <div class="mobile-overlay" id="mobileOverlay"></div>

        <nav class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-inner">
                <div class="sidebar-header">
                    <!-- <div class="sidebar-brand">
                        <span class="admin-brand-icon">
                            <i class="fas fa-hospital-user"></i>
                        </span>
                        <div>
                            <div class="brand-title">Admin Panel</div>
                            <div class="brand-subtitle">Reservasi Dokter</div>
                        </div>
                    </div> -->
                    <button class="sidebar-toggle d-none d-md-inline-flex" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="sidebar-toggle d-inline-flex d-md-none" id="sidebarClose" aria-label="Close sidebar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- <div class="admin-sidebar-profile">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                        <div class="profile-role">Administrator</div>
                    </div>
                </div> -->

                <ul class="nav flex-column admin-nav-list">
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
                            <i class="fas fa-user-md"></i>
                            <span>Kelola Dokter</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.create') }}">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Buat Appointment</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link {{ request()->routeIs('admin.queues.*') ? 'active' : '' }}" href="{{ route('admin.queues.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Manajemen Antrian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.visitation') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Laporan Kunjungan</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link admin-nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="admin-main" id="adminMain">
            <header class="admin-header">
                <div class="admin-header-inner">
                    <div class="admin-header-title-group">
                        <button class="sidebar-toggle d-md-none me-3" id="sidebarOpen" aria-label="Open sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="admin-page-title">@yield('page-title', 'Dashboard')</h1>
                            <p class="admin-page-subtitle">@yield('page-subtitle', 'Ringkasan aktivitas hari ini')</p>
                        </div>
                    </div>
                    <div class="admin-header-actions d-flex align-items-center gap-3">
                        <div class="admin-topbar-time d-none d-sm-flex align-items-center">
                            <i class="far fa-clock me-2"></i>
                            <span>{{ now()->format('H:i') }} WIB</span>
                        </div>

                        <div class="admin-notification-wrapper">
                            @include('components.notification-dropdown')
                        </div>

                        <div class="dropdown admin-user-menu">
                            <button class="btn btn-outline-secondary btn-admin-user dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fas fa-user me-2"></i>
                                {{ auth()->user()->name }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                       href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Admin JavaScript --}}
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const timeString = `${hours}:${minutes} WIB`;

            const timeElement = document.querySelector('.admin-topbar-time span');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        updateClock();
        setInterval(updateClock, 1000);

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const main = document.getElementById('adminMain');
            const overlay = document.getElementById('mobileOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOpen = document.getElementById('sidebarOpen');
            const sidebarClose = document.getElementById('sidebarClose');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('expanded');
                });
            }

            if (sidebarOpen) {
                sidebarOpen.addEventListener('click', function() {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                });
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            const navLinks = sidebar ? sidebar.querySelectorAll('.admin-nav-link') : [];
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>



