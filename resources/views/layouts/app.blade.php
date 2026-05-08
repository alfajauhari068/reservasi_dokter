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
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(180deg, #eef2ff 0%, #ffffff 100%);
            color: #0f172a;
        }

        .navbar {
            background: linear-gradient(90deg, #4338ca, #2563eb);
            min-height: 76px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.18);
        }

        .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .navbar-brand small {
            display: block;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .navbar .nav-link {
            color: rgba(255, 255, 255, 0.92);
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #ffffff;
        }

        .main-shell {
            padding: 2rem 1rem 4rem;
        }

        .page-heading {
            padding: 2rem;
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(79, 70, 229, 0.14), rgba(255, 255, 255, 0.8));
            box-shadow: 0 26px 60px rgba(15, 23, 42, 0.08);
            margin-bottom: 1.75rem;
        }

        .page-heading h1 {
            font-size: 2.5rem;
            letter-spacing: -0.03em;
            margin-bottom: 0.5rem;
        }

        .page-heading p {
            color: #475569;
            font-size: 1rem;
            max-width: 660px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 24px;
            backdrop-filter: blur(12px);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            padding: 1.6rem;
        }

        .btn-gradient {
            border-radius: 14px;
            padding: 0.9rem 1.6rem;
            background: linear-gradient(90deg, #2563eb, #0ea5e9);
            color: #ffffff;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(14, 165, 233, 0.25);
            background: linear-gradient(90deg, #1d4ed8, #0284c7);
        }

        .table-modern {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.08);
            background: #ffffff;
        }

        .table-modern thead th {
            background: #f8fafc;
            color: #334155;
            border: none;
            font-weight: 700;
            padding: 1.3rem 1rem;
        }

        .table-modern tbody td {
            border: none;
            padding: 1rem 1rem;
            vertical-align: middle;
            color: #475569;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
        }

        .badge-pill {
            border-radius: 999px;
            padding: 0.55rem 0.95rem;
            font-size: 0.82rem;
            letter-spacing: 0.01em;
            font-weight: 600;
        }

        .badge-pill.available {
            background: #dcfce7;
            color: #166534;
        }

        .badge-pill.unavailable {
            background: #fee2e2;
            color: #b91c1c;
        }

        .stats-pill {
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 18px;
            padding: 1rem 1.2rem;
            color: #334155;
        }

        .stats-pill strong {
            display: block;
            font-size: 1.75rem;
            margin-bottom: 0.35rem;
        }

        .search-box .form-control {
            border-radius: 18px;
            padding: 1rem 1.2rem;
            box-shadow: inset 0 6px 18px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(148, 163, 184, 0.22);
        }

        .search-box .input-group-text {
            background: #ffffff;
            border-radius: 18px 0 0 18px;
            border-right: none;
        }

        .search-box .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.16);
        }

        @media (max-width: 768px) {
            .page-heading {
                padding: 1.5rem;
            }

            .page-heading h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Reservasi Dokter
                <small>Platform reservasi kesehatan modern</small>
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.doctors.index') }}">Dokter</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports.visitation') }}">Laporan</a></li>
                </ul>
                @auth
                    <div class="d-flex align-items-center ms-4">
                        <span class="me-3 text-white">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    <main class="main-shell">
        <div class="container">
            @yield('content')
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>