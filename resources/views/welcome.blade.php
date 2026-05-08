@extends('layouts.app')

@section('title', 'Reservasi Dokter - Pesan Jadwal Online')

@section('content')
<style>
    /* Background SVG / shape abstrak di hero */
    .hero-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 40%, #e0f7fa 100%);
    }

    .hero-shape {
        position: absolute;
        top: -120px;
        right: -100px;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle at 30% 30%, #b3e5fc 0, #4dd0e1 40%, transparent 70%);
        opacity: 0.7;
        filter: blur(4px);
        z-index: 0;
    }

    .hero-shape-bottom {
        position: absolute;
        bottom: -140px;
        left: -120px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle at 70% 70%, #e1f5fe 0, #b2ebf2 40%, transparent 70%);
        opacity: 0.8;
        filter: blur(3px);
        z-index: 0;
    }

    .hero-content,
    .hero-illustration {
        position: relative;
        z-index: 1;
    }

    .badge-soft-success {
        background-color: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .badge-soft-primary {
        background-color: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
    }

    .feature-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background-color: #e3f2fd;
        color: #0d6efd;
        font-size: 1.2rem;
    }

    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #0d6efd;
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .doctor-card {
        border-radius: 24px;
        background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
        padding: 2rem;
    }

    .doctor-avatar {
        width: 72px;
        height: 72px;
        border-radius: 999px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.4rem;
        color: #0d6efd;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
    }

    .doctor-meta small {
        display: block;
        color: #6c757d;
    }

    .hero-search {
        background-color: #ffffff;
        border-radius: 999px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        padding: 0.75rem 1rem;
    }

    @media (max-width: 767.98px) {
        .hero-search {
            border-radius: 18px;
        }
    }

    .hero-search .form-control,
    .hero-search .form-select {
        border: none;
        box-shadow: none !important;
    }

    .hero-search .form-control:focus,
    .hero-search .form-select:focus {
        border: none;
    }

    footer a {
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }
</style>

<header class="sticky-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="me-2 text-primary">
                    <i class="fa-solid fa-stethoscope fa-lg"></i>
                </div>
                <div>
                    <span class="fw-bold">ReservasiDokter</span><br>
                    <small class="text-muted" style="font-size: 0.7rem;">Klinik & Rumah Sakit Partner</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#doctors">Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<section id="hero" class="hero-section py-5 py-lg-5">
    <div class="hero-shape"></div>
    <div class="hero-shape-bottom"></div>

    <div class="container">
        <div class="row align-items-center gy-4 gy-lg-0">
            <div class="col-lg-6 hero-content">
                <div class="mb-3 d-inline-flex align-items-center gap-2">
                    <span class="badge badge-soft-success rounded-pill">
                        <i class="fa-solid fa-check-circle me-1"></i> Reservasi terjadwal & tanpa antre panjang
                    </span>
                </div>

                <h1 class="fw-bold mb-3" style="letter-spacing: -0.02em;">
                    Pesan Jadwal Dokter Secara Online<br class="d-none d-md-block">
                    Tanpa Antre Panjang
                </h1>

                <p class="text-muted mb-3">
                    Pilih dokter, atur jadwal kunjungan, dan terima notifikasi otomatis langsung dari rumah Anda.
                    Semua dalam satu sistem reservasi yang praktis, cepat, dan terjadwal.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="#booking" class="btn btn-primary btn-lg px-4">
                        Reservasi Sekarang
                    </a>
                    <a href="#doctors" class="btn btn-outline-primary btn-lg px-4">
                        Lihat Jadwal Dokter
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="text-muted small">
                        Praktis • Cepat • Terjadwal
                    </span>
                </div>

                <div class="hero-search mt-3">
                    <form action="#" method="GET">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="Cari dokter atau spesialisasi">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-calendar-days text-muted me-2"></i>
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Cek ketersediaan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                        <span class="badge badge-soft-primary">
                            <i class="fa-solid fa-clock me-1"></i> Tersedia hari ini
                        </span>
                        <span class="badge bg-light text-muted border">
                            Buka sampai 21.00
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hero-illustration">
                <div class="doctor-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="doctor-avatar me-3">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-meta">
                            <strong>Dr. Ananda Putri, Sp.PD</strong>
                            <small>Penyakit Dalam • 8 tahun pengalaman</small>
                            <small class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Dokter terverifikasi</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Slot jadwal hari ini</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                3 slot tersisa
                            </span>
                        </div>

                        <div class="row g-2">
                            <div class="col-4">
                                <div class="border rounded-3 py-2 px-2 text-center bg-white">
                                    <small class="text-muted d-block">Sesi Pagi</small>
                                    <span class="fw-semibold d-block" style="font-size: 0.9rem;">09.00 - 11.00</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 py-2 px-2 text-center bg-primary text-white">
                                    <small class="d-block">Sesi Siang</small>
                                    <span class="fw-semibold d-block" style="font-size: 0.9rem;">13.00 - 15.00</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded-3 py-2 px-2 text-center bg-white">
                                    <small class="text-muted d-block">Sesi Sore</small>
                                    <span class="fw-semibold d-block" style="font-size: 0.9rem;">17.00 - 19.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-shield-heart text-primary"></i>
                            <small class="text-muted">Terintegrasi dengan rekam medis klinik</small>
                        </div>
                        <a href="#booking" class="btn btn-outline-primary btn-sm">
                            Buat reservasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-primary-subtle text-primary mb-2">
                Kenapa menggunakan ReservasiDokter
            </span>
            <h2 class="fw-bold mb-2">Reservasi Mudah, Dokter Terverifikasi</h2>
            <p class="text-muted mb-0">
                Nikmati pengalaman reservasi dokter yang praktis, transparan, dan bebas antre panjang.
            </p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                        </div>
                        <h5 class="card-title">Reservasi Mudah</h5>
                        <p class="card-text text-muted">
                            Buat janji temu dokter kapan saja dan di mana saja hanya dalam beberapa klik.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h5 class="card-title">Dokter Terverifikasi</h5>
                        <p class="card-text text-muted">
                            Semua dokter telah melalui proses verifikasi dan bekerja sama dengan klinik resmi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <h5 class="card-title">Notifikasi Otomatis</h5>
                        <p class="card-text text-muted">
                            Dapatkan pengingat jadwal melalui SMS/email sehingga Anda tidak melewatkan kunjungan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="fa-solid fa-people-line"></i>
                        </div>
                        <h5 class="card-title">Antrian Lebih Singkat</h5>
                        <p class="card-text text-muted">
                            Datang sesuai jadwal sehingga waktu tunggu di klinik menjadi lebih singkat dan terukur.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="py-5">
    <div class="container">
        <div class="row align-items-start gy-4">
            <div class="col-lg-5">
                <span class="badge bg-primary-subtle text-primary mb-2">
                    Cara Kerja
                </span>
                <h2 class="fw-bold mb-3">
                    3 langkah sederhana untuk reservasi dokter
                </h2>
                <p class="text-muted">
                    Kami merancang alur reservasi yang sederhana agar pasien dapat fokus pada proses penyembuhan,
                    bukan antrean.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <div class="h-100 p-3 border rounded-3 bg-light">
                            <div class="step-number mb-2">1</div>
                            <h6 class="fw-semibold">Daftar atau Login</h6>
                            <p class="small text-muted mb-0">
                                Buat akun baru atau login menggunakan email/nomor HP untuk menyimpan riwayat reservasi.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="h-100 p-3 border rounded-3 bg-light">
                            <div class="step-number mb-2">2</div>
                            <h6 class="fw-semibold">Pilih Dokter & Jadwal</h6>
                            <p class="small text-muted mb-0">
                                Cari dokter berdasarkan nama atau spesialisasi, lalu pilih jadwal yang masih tersedia.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="h-100 p-3 border rounded-3 bg-light">
                            <div class="step-number mb-2">3</div>
                            <h6 class="fw-semibold">Datang Sesuai Jadwal</h6>
                            <p class="small text-muted mb-0">
                                Datang tepat waktu ke klinik dengan menunjukkan bukti reservasi yang dikirim ke Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="booking" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Siap untuk membuat reservasi dokter pertama Anda?</h2>
                <p class="text-muted mb-4">
                    Mulai dengan melakukan pendaftaran, kemudian pilih dokter dan jadwal yang paling sesuai.
                    Sistem kami akan membantu Anda mengatur semuanya secara otomatis.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        Daftar Akun Pasien
                    </a>
                    <a href="#hero" class="btn btn-outline-primary btn-lg">
                        Coba cari dokter sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer id="contact" class="pt-5 pb-4 bg-white border-top">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h5 class="fw-bold mb-2">ReservasiDokter</h5>
                <p class="text-muted">
                    Platform reservasi dokter online untuk klinik dan rumah sakit yang membantu mengurangi antrean
                    dan memudahkan pengelolaan jadwal kunjungan.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold">Informasi Klinik</h6>
                <p class="text-muted mb-1">
                    Jl. Contoh Sehat No. 123<br>
                    Tulungagung, Jawa Timur
                </p>
                <p class="text-muted mb-1">
                    Telp/WA: 0812-0000-0000
                </p>
                <p class="text-muted mb-0">
                    Email: info@reservasidokter.test
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold">Tautan Penting</h6>
                <ul class="list-unstyled mb-0">
                    <li><a href="#hero" class="text-muted">Beranda</a></li>
                    <li><a href="#how-it-works" class="text-muted">Cara Kerja</a></li>
                    <li><a href="#" class="text-muted">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-muted">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                &copy; {{ date('Y') }} ReservasiDokter. All rights reserved.
            </small>
            <div class="d-flex gap-3">
                <a href="#" class="text-muted"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="text-muted"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-muted"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </div>
</footer>
@endsection