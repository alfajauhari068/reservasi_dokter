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

    {{-- Admin Styling --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.12), transparent 28%),
                        radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.12), transparent 24%),
                        #eff3f8;
            color: #1f2937;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            overflow-y: auto;
            padding-top: 1rem;
            z-index: 1000;
            transition: transform 0.3s ease, width 0.3s ease;
        }

        .admin-sidebar.collapsed {
            width: 70px;
        }

        .admin-sidebar.collapsed .sidebar-toggle {
            transform: rotate(180deg);
        }

        .admin-sidebar p, .admin-sidebar span {
            transition: opacity 0.3s ease;
        }

        .admin-sidebar.collapsed span:not(.nav-link i) {
            opacity: 0;
            display: none;
        }

        .admin-sidebar.collapsed .nav-link {
            justify-content: center;
            padding-left: 0.8rem;
            padding-right: 0.8rem;
        }

        .admin-sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .admin-sidebar.collapsed .text-center {
            display: none;
        }

        .admin-sidebar.collapsed .admin-sidebar .nav-link {
            text-align: center;
        }

        .admin-sidebar.collapsed .fw-semibold,
        .admin-sidebar.collapsed .opacity-75,
        .admin-sidebar.collapsed .nav-link span,
        .admin-sidebar.collapsed .text-center p,
        .admin-sidebar.collapsed .text-center small {
            display: none !important;
            opacity: 0 !important;
        }

        .admin-sidebar.collapsed .admin-sidebar .nav-link {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .admin-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .admin-sidebar.collapsed .nav-link span {
            display: none;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-width: 0;
            overflow-x: hidden;
            transition: margin-left 0.3s ease, width 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-main.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 30px 90px rgba(15, 23, 42, 0.08);
            border-radius: 24px;
        }

        .admin-content {
            flex: 1;
            padding: 2rem;
            max-width: 100%;
            overflow-x: hidden;
        }

        .card {
            width: 100%;
            max-width: 100%;
            border: none;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.05);
            border-radius: 22px;
            margin-bottom: 1.5rem;
        }

        .btn {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.75rem 1.4rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(15, 23, 42, 0.08);
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-outline-secondary {
            color: #374151;
            border-color: #d1d5db;
            background-color: rgba(255, 255, 255, 0.92);
        }

        .btn-outline-secondary:hover {
            background-color: #f8fafc;
        }

        .btn-info {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
            color: #fff;
        }

        .btn-success {
            background-color: #16a34a;
            border-color: #16a34a;
            color: #fff;
        }

        .btn-warning {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: #1f2937;
        }

        .form-control,
        .form-select,
        .form-check-input {
            border-radius: 16px;
            border: 1px solid #d1d5db;
            box-shadow: none;
            padding: 0.9rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.18);
        }

        .form-label {
            font-weight: 700;
            color: #111827;
        }

        .table thead th {
            background-color: rgba(248, 250, 252, 0.9);
            border-bottom: 1px solid #e5e7eb;
        }

        .table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .badge {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.6rem 0.9rem;
        }

        .badge.bg-primary {
            background-color: rgba(37, 99, 235, 0.12) !important;
            color: #1d4ed8 !important;
        }

        .badge.bg-success {
            background-color: rgba(22, 163, 74, 0.12) !important;
            color: #166534 !important;
        }

        .badge.bg-info {
            background-color: rgba(14, 165, 233, 0.12) !important;
            color: #0ea5e9 !important;
        }

        .badge.bg-warning {
            background-color: rgba(245, 158, 11, 0.12) !important;
            color: #b45309 !important;
        }

        .admin-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Time Display Styling */
        .admin-header .text-end {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-header .text-end .fw-semibold {
            font-size: 0.95rem;
            color: #374151;
            font-weight: 600;
        }

        .admin-header .text-end .text-muted {
            font-size: 0.9rem;
            color: #1e40af !important;
            font-weight: 500;
        }

        .sidebar-toggle {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .admin-sidebar .sidebar-toggle {
            color: white;
        }

        .sidebar-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .mobile-overlay.show {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                width: 260px;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-main.expanded {
                margin-left: 0;
            }

            .admin-header {
                padding: 1rem 1.5rem;
            }

            .admin-header h1 {
                font-size: 1.25rem !important;
            }

            .admin-content {
                padding: 1rem;
            }
        }

        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f9fafb;
        }

        .table thead th {
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        /* Badge Styling */
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge.bg-success {
            background-color: #dcfce7 !important;
            color: #166534;
        }

        .badge.bg-warning {
            background-color: #fef3c7 !important;
            color: #92400e;
        }

        .badge.bg-danger {
            background-color: #fee2e2 !important;
            color: #991b1b;
        }

        .badge.bg-info {
            background-color: #cffafe !important;
            color: #164e63;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-outline-secondary {
            color: #6b7280;
            border-color: #d1d5db;
        }

        .btn-outline-secondary:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        /* Form Styling */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: 1px solid;
            padding: 1rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #dcfce7;
            color: #166534;
        }

        .alert-warning {
            background-color: #fffbeb;
            border-color: #fef3c7;
            color: #92400e;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fee2e2;
            color: #991b1b;
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Mobile Overlay --}}
    <div class="mobile-overlay" id="mobileOverlay"></div>

    {{-- Admin Sidebar --}}
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="p-4">
            {{-- Logo & Toggle --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-hospital-user text-white me-3" style="font-size: 24px;"></i>
                    <span class="fw-bold text-white fs-5">Admin Panel</span>
                </div>
                <button class="sidebar-toggle d-none d-md-block" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="sidebar-toggle d-md-none" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- User Info --}}
            <div class="text-center mb-4 pb-3 border-bottom border-white border-opacity-25">
                <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="text-white mt-2">
                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                    <div class="opacity-75 small">Administrator</div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
                       href="{{ route('admin.doctors.index') }}">
                        <i class="fas fa-user-md"></i>
                        <span>Kelola Dokter</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
                       href="{{ route('admin.appointments.create') }}">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Buat Appointment Pasien</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.queues.*') ? 'active' : '' }}"
                       href="{{ route('admin.queues.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Antrian</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                       href="{{ route('admin.reports.visitation') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan Kunjungan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

    {{-- Main Content --}}
    <div class="admin-main" id="adminMain">
        {{-- Header --}}
        <header class="admin-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="sidebar-toggle d-md-none me-3" id="sidebarOpen">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="h4 mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-muted mb-0 small">@yield('page-subtitle', 'Ringkasan aktivitas hari ini')</p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="text-end me-3 d-none d-sm-block">
                        <div class="fw-semibold small">{{ now()->format('l, d F Y') }}</div>
                        <!-- <div class="text-muted small">{{ now()->format('H:i') }} WIB</div> -->
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
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

        {{-- Page Content --}}
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Admin JavaScript --}}
    <script>
        // Real-time Clock Update
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const timeString = `${hours}:${minutes} WIB`;
            
            const timeElement = document.querySelector('.admin-header .text-muted.small');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);

        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const main = document.getElementById('adminMain');
            const overlay = document.getElementById('mobileOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOpen = document.getElementById('sidebarOpen');
            const sidebarClose = document.getElementById('sidebarClose');

            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('expanded');
                });
            }

            // Mobile sidebar open
            if (sidebarOpen) {
                sidebarOpen.addEventListener('click', function() {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                });
            }

            // Mobile sidebar close
            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Mobile overlay click
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Auto-hide sidebar on mobile when clicking menu items
            const navLinks = sidebar.querySelectorAll('.nav-link');
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