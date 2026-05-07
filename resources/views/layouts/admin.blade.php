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

    {{-- Custom Admin Styles --}}
    <style>
        :root {
            --admin-primary: #2563eb;
            --admin-secondary: #64748b;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-dark: #1f2937;
            --admin-light: #f8fafc;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--admin-light);
        }

        .admin-sidebar {
            background: linear-gradient(135deg, var(--admin-primary), #1d4ed8);
            min-height: 100vh;
            position: fixed;
            width: 280px;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .admin-sidebar.collapsed {
            width: 70px;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .admin-sidebar.collapsed .nav-link span {
            display: none;
        }

        .admin-sidebar.collapsed .nav-link i {
            margin-right: 0;
            text-align: center;
        }

        .admin-main {
            margin-left: 280px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .admin-main.expanded {
            margin-left: 70px;
        }

        .admin-header {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .admin-content {
            padding: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .stats-label {
            font-size: 0.9rem;
            color: var(--admin-secondary);
            font-weight: 500;
        }

        .table-modern {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        .table-modern thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: var(--admin-dark);
            padding: 15px;
        }

        .table-modern tbody td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-approved { background: #dbeafe; color: #2563eb; }
        .status-done { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .queue-status-waiting { background: #fef3c7; color: #d97706; }
        .queue-status-called { background: #dbeafe; color: #2563eb; }
        .queue-status-served { background: #d1fae5; color: #065f46; }
        .queue-status-skipped { background: #f3f4f6; color: #6b7280; }

        .btn-admin {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-admin:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .mobile-overlay.show {
                display: block;
            }
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
                        <div class="text-muted small">{{ now()->format('H:i') }} WIB</div>
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