<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Pasien')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/fluid-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pasien-bento.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pasien-portal.css') }}">
</head>
<body class="bg-[#F8FAFC]">

    <div class="min-h-screen">
        @include('layouts.navbar-patient')

        <main class="pt-4">
            {{-- Kontainer sejajar (mengurangi padding horizontal yang berlebih) --}}
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-8 lg:px-10">
                @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Navbar interactions are bundled in resources/js/navbar.js and imported by Vite/app.js --}}

</body>
</html>

