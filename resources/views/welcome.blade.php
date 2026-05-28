@extends('layouts.app')

@section('title', 'Reservasi Dokter - Pesan Jadwal Online')
@section('fullwidth', true)

@section('content')
<div class="ds-landing-bg">
    {{-- Custom Welcome Navbar (Bootstrap 5 utility classes) --}}
    
    {{-- HERO: Bento Grid Asimetris --}}
    <section id="hero" class="ds-landing-section  py-lg-2">
        <div class="container-fluid px-0">
            <div class="row g-6 align-items-stretch mx-0">
                {{-- Hero Banner Utama --}}
                <div class="col-lg-8">
                    <div class="position-relative bg-white rounded-4xl p-0 shadow-sm overflow-hidden w-100" style="border-radius: 2rem; box-shadow: 0 10px 30px rgba(2, 6, 23, 0.06) !important;">
                        {{-- Badge --}}
                        <div class="d-inline-flex align-items-center gap-0 px-3 py-2 rounded-pill" style="background: rgba(59, 130, 246, 0.10); border: 1px solid rgba(59, 130, 246, 0.20); color: #1d4ed8; font-weight: 800; letter-spacing: 0.02em;">
                            <span style="font-size: 1.05rem;">🚀</span>
                            <span>Reservasi Dokter Online, Cepat dan Mudah</span>
                        </div>

                        <div class="position-relative" style="z-index: 1;">
                            <h1 class="font-display fw-bold mt-4 mb-3" style="font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05;">
                                Reservasi mudah dengan pengalaman
                                <span style="background: linear-gradient(90deg, #1c69d4 0%, #7c3aed 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">yang nyaman</span>
                            </h1>

                            <p class="ds-muted mb-4" style="max-width: 52ch; font-size: 1.05rem;">
                                Pilih dokter terverifikasi, atur jadwal kunjungan, dan dapatkan pengingat otomatis. Semua dirancang agar Anda tidak perlu antre.
                            </p>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                {{-- CTA 1: tetap mengarah ke bagian booking --}}
                                <a href="#booking" class="btn btn-primary btn-lg px-4">
                                    Reservasi Sekarang
                                </a>
                                {{-- CTA 2: scroll ke fitur/daftar dokter --}}
                                <a href="#facilities" class="btn btn-secondary btn-lg px-4">
                                    Lihat Layanan
                                </a>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge badge-soft-primary rounded-pill">
                                    <i class="fa-solid fa-clock me-1"></i> 24/7 Online Consultation
                                </span>
                                <span class="badge badge-soft-success rounded-pill">
                                    <i class="fa-solid fa-circle-check me-1"></i> Dokter Terverifikasi
                                </span>
                            </div>
                        </div>

                        {{-- Dokter hero image (absolute bottom-right) --}}
                        <div class="position-absolute bottom-0 end-0 d-none d-md-block" style="width: 65%; height: 100%; pointer-events: none; z-index: 0; overflow: hidden;">

                            <img
                                src="{{ asset('assets\doctor_hero_1779793696768.png') }}"
                                alt="Dokter hero"

                                style="width: 100%; height: 100%; object-fit: contain;"
                                loading="lazy"
                            />
                        </div>
                    </div>
                </div>

                {{-- Interactive Sampingan --}}
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-3 h-100">
                        {{-- Kartu 1: Jadwal Terstruktur (gelap) --}}
                        <div class="rounded-4xl p-4 h-50" style="border-radius: 2rem; background: #0f172a; box-shadow: 0 10px 30px rgba(2, 6, 23, 0.10) !important;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 16px; background: rgba(99, 102, 241, 0.18); border: 1px solid rgba(99, 102, 241, 0.28); color: #a5b4fc;">
                                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <div class="text-white" style="font-weight: 800; font-size: 1.05rem;">Jadwal Terstruktur</div>
                                    <div class="text-white-50" style="font-size: 0.95rem;">
                                        Slot tersedia real-time, sehingga Anda bisa memilih waktu kunjungan dengan lebih pasti.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kartu 2: Asisten Pengingat Notifikasi otomatis (biru royal) --}}
                        <div class="rounded-4xl p-4 h-50" style="border-radius: 2rem; background: #1c69d4; box-shadow: 0 10px 30px rgba(28, 105, 212, 0.22) !important;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 16px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.28); color: #ffffff;">
                                    <i class="fa-solid fa-bell" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <div class="text-white" style="font-weight: 800; font-size: 1.05rem;">Notifikasi Otomatis</div>
                                    <div class="text-white-50" style="font-size: 0.95rem;">
                                        Sistem asisten pengingat membantu Anda tidak ketinggalan jadwal.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- Fitur & Layanan Utama --}}
    <section id="features" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-2">
                <span class="badge" style="background:#1c69d4; color:#fff; padding:0.55rem 1rem; border-radius:9999px; font-weight:800; letter-spacing:0.08em; text-transform:uppercase;">
                    Fitur Utama
                </span>
                <h2 class="fw-bold mt-3 mb-2">Reservasi mudah dengan pengalaman yang nyaman</h2>
            </div>

            <div class="row g-2">
                {{-- Card 1 --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="p-4 rounded-4xl h-100" style="background:#ffffff; border:1px solid rgba(148,163,184,0.35); border-radius:1.5rem; box-shadow:0 10px 25px rgba(2,6,23,0.04); transition:transform .2s ease, box-shadow .2s ease;">
                        <div class="ds-feature-icon mb-3" style="width:56px; height:56px; border-radius:1.25rem; display:flex; align-items:center; justify-content:center; background:rgba(28,105,212,0.10); color:#1c69d4; font-size:1.35rem;">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <h5 class="fw-bold">Booking Online</h5>
                        <p class="ds-muted mb-0">Atur jadwal kunjungan hanya dalam beberapa klik.</p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="p-4 rounded-4xl h-100" style="background:#ffffff; border:1px solid rgba(148,163,184,0.35); border-radius:1.5rem; box-shadow:0 10px 25px rgba(2,6,23,0.04); transition:transform .2s ease, box-shadow .2s ease;">
                        <div class="ds-feature-icon mb-3" style="width:56px; height:56px; border-radius:1.25rem; display:flex; align-items:center; justify-content:center; background:rgba(28,105,212,0.10); color:#1c69d4; font-size:1.35rem;">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h5 class="fw-bold">Dokter Terverifikasi</h5>
                        <p class="ds-muted mb-0">Kualitas layanan lebih terjamin dan terpercaya.</p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="p-4 rounded-4xl h-100" style="background:#ffffff; border:1px solid rgba(148,163,184,0.35); border-radius:1.5rem; box-shadow:0 10px 25px rgba(2,6,23,0.04); transition:transform .2s ease, box-shadow .2s ease;">
                        <div class="ds-feature-icon mb-3" style="width:56px; height:56px; border-radius:1.25rem; display:flex; align-items:center; justify-content:center; background:rgba(28,105,212,0.10); color:#1c69d4; font-size:1.35rem;">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <h5 class="fw-bold">Notifikasi Otomatis</h5>
                        <p class="ds-muted mb-0">Pengingat jadwal membantu Anda datang tepat waktu.</p>
                    </div>
                </div>

                {{-- Card 4 --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="p-4 rounded-4xl h-100" style="background:#ffffff; border:1px solid rgba(148,163,184,0.35); border-radius:1.5rem; box-shadow:0 10px 25px rgba(2,6,23,0.04); transition:transform .2s ease, box-shadow .2s ease;">
                        <div class="ds-feature-icon mb-3" style="width:56px; height:56px; border-radius:1.25rem; display:flex; align-items:center; justify-content:center; background:rgba(28,105,212,0.10); color:#1c69d4; font-size:1.35rem;">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h5 class="fw-bold">Efisiensi Waktu</h5>
                        <p class="ds-muted mb-0">Kurangi antrean dengan alur reservasi yang terstruktur.</p>
                    </div>
                </div>
            </div>

            {{-- hover (inline minimal, tidak mengubah CSS global) --}}
            <style>
                #features .rounded-4xl:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 18px 40px rgba(2,6,23,0.08) !important;
                }
            </style>
        </div>
    </section>

    {{-- Fasilitas dan Layanan Kesehatan --}}
    <section id="facilities" class="ds-landing-section py-5 bg-white">
        <div class="container">
            <div class="row align-items-start g-4">
                {{-- Kolom kiri --}}
                <div class="col-lg-4">
                    <div>
                        <span class="badge" style="background:rgba(28,105,212,0.10); color:#1c69d4; padding:0.55rem 1rem; border-radius:9999px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase;">Fasilitas dan Layanan</span>
                        <h2 class="fw-bold mt-3">Klinik lengkap, layanan jelas.</h2>
                        <p class="ds-muted mb-4">Semua layanan dirancang agar kunjungan lebih cepat, nyaman, dan terkontrol.</p>

                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-center mb-2"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i><span class="ms-2 ds-muted">Pelayanan terstruktur</span></li>
                            <li class="d-flex align-items-center mb-2"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i><span class="ms-2 ds-muted">Rekam medis terkelola</span></li>
                            <li class="d-flex align-items-center"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i><span class="ms-2 ds-muted">Notifikasi pengingat jadwal</span></li>
                        </ul>
                    </div>
                </div>

                {{-- Kolom kanan (Bento asimetris) --}}
                <div class="col-lg-8">
                    <div class="row g-2">
                        {{-- Bento kecil 1 --}}
                        <div class="col-md-6">
                            <div class="h-100 p-4 rounded-4xl" style="background:rgba(28,105,212,0.08); border:1px solid rgba(28,105,212,0.16); border-radius:1.5rem;">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill" style="background:rgba(28,105,212,0.14); color:#1c69d4;"> <i class="fa-solid fa-heart-pulse"></i></span>
                                    <div>
                                        <div class="fw-bold" style="font-size:1.05rem;">Pelayanan Medis</div>
                                        <div class="ds-muted small mt-1">Bantuan medis terintegrasi dan terarah.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bento kecil 2 --}}
                        <div class="col-md-6">
                            <div class="h-100 p-4 rounded-4xl" style="background:rgba(28,105,212,0.08); border:1px solid rgba(28,105,212,0.16); border-radius:1.5rem;">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill" style="background:rgba(28,105,212,0.14); color:#1c69d4;"> <i class="fa-solid fa-file-medical"></i></span>
                                    <div>
                                        <div class="fw-bold" style="font-size:1.05rem;">Rekam Medis</div>
                                        <div class="ds-muted small mt-1">Akses informasi pasien lebih cepat.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bento panjang penuh --}}
                        <div class="col-12">
                            <div class="p-5 rounded-4xl" style="background:rgba(28,105,212,0.12); border:1px solid rgba(28,105,212,0.18); border-radius:2rem; position:relative; overflow:hidden;">
                                <div class=" align-items-center">
                                    <div class="corow g-2 l-lg-7">
                                        <div class="d-inline-flex align-items-center gap-2 mb-2">
                                            <span class="ds-icon-pill" style="background:rgba(255,255,255,0.55); border:1px solid rgba(28,105,212,0.18); color:#1c69d4;"> <i class="fa-solid fa-sparkles"></i></span>
                                            <div class="fw-bold">Klinik Lengkap dalam Satu Platform</div>
                                        </div>
                                        <p class="ds-muted mb-0">Booking online, konsultasi, dan pengingat jadwal dalam satu alur yang rapi.</p>
                                    </div>
                                    <div class="col-lg-5 text-lg-end">
                                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4" style="border-radius:1rem;">Daftar Sekarang</a>
                                        <div class="ds-muted small mt-2">Mulai reservasi tanpa ribet.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- existing sections continue --}}

        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-5">
                    <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">Facilities and Services</span>
                    <h2 class="fw-bold mb-3">Fasilitas dan layanan kesehatan terbaik</h2>
                    <p class="ds-muted mb-4">Semua layanan dirancang agar kunjungan lebih cepat, nyaman, dan terkontrol.</p>

                    <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-3">
                            <span class="ds-icon-pill"><i class="fa-solid fa-check"></i></span>
                            <div>
                                <strong>Medical Check Up</strong>
                                <div class="ds-muted small">Pemeriksaan kesehatan dan screening lengkap.</div>
                            </div>
                        </li>
                        <li class="d-flex mb-3">
                            <span class="ds-icon-pill"><i class="fa-solid fa-check"></i></span>
                            <div>
                                <strong>Emergency Care</strong>
                                <div class="ds-muted small">Dukungan cepat dan terintegrasi.</div>
                            </div>
                        </li>
                        <li class="d-flex mb-3">
                            <span class="ds-icon-pill"><i class="fa-solid fa-check"></i></span>
                            <div>
                                <strong>Medical Center</strong>
                                <div class="ds-muted small">Fasilitas klinik profesional dan bersertifikat.</div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <span class="ds-icon-pill"><i class="fa-solid fa-check"></i></span>
                            <div>
                                <strong>Doctor Specialist</strong>
                                <div class="ds-muted small">Spesialis terverifikasi dan berpengalaman.</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="ds-rounded-xxl p-4 ds-panel-glow">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill ds-icon-pill-lg"><i class="fa-solid fa-heart-pulse"></i></span>
                                    <div>
                                        <div class="fw-bold">Pelayanan Medis Terpadu</div>
                                        <div class="ds-muted small mt-1">Booking, konsultasi, dan pengingat jadwal.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ds-rounded-xxl p-4 ds-panel-glow">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill ds-icon-pill-lg"><i class="fa-solid fa-file-medical"></i></span>
                                    <div>
                                        <div class="fw-bold">Rekam Medis Digital</div>
                                        <div class="ds-muted small mt-1">Akses informasi pasien lebih cepat.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="ds-rounded-xxl ds-glass-panel p-4 p-md-5">
                                <div class="row align-items-center g-4">
                                    <div class="col-lg-6">
                                        <div class="d-inline-flex align-items-center gap-2 mb-2">
                                            <span class="ds-icon-pill"><i class="fa-solid fa-sparkles"></i></span>
                                            <div class="fw-bold">Klinik lengkap dalam satu platform</div>
                                        </div>
                                        <p class="ds-muted mb-3">Menghubungkan Anda ke dokter, fasilitas, dan layanan medis tanpa kerumitan.</p>

                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Booking online mudah</li>
                                            <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Notifikasi jadwal otomatis</li>
                                            <li class="mb-0"><i class="fa-solid fa-circle-check text-primary me-2"></i>Manajemen kunjungan lebih rapi</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center">
                                            <img
                                                src="{{ asset('assets/animasi.jpg') }}"
                                                alt="Ilustrasi layanan kesehatan"
                                                loading="lazy"
                                                class="ds-illustration-media"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {{-- hidden link target booking for smooth UX --}}
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <a href="#booking" class="btn btn-primary">Pelajari Lebih Lanjut</a>
                                    <a href="#team" class="btn btn-secondary">Lihat Tim Dokter</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PROMO 24/7 ONLINE CONSULTATION --}}
    <section id="consultation" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">24/7 Online Consultation</span>
                <h2 class="mb-2">Konsultasi online kapan saja, di mana saja</h2>
                <p class="mb-0">Butuh saran medis ringan terlebih dahulu? Kami bantu mengarahkan Anda untuk langkah berikutnya.</p>
            </div>

            <div class="ds-consult-card">
                <div class="row g-0">
                    <div class="col-lg-4 ds-consult-hero p-4 p-md-5 text-center">
                        <div class="mb-3">
                            <div class="ds-avatar mx-auto"> <i class="fa-solid fa-headset"></i></div>
                        </div>
                        <div class="fw-bold ds-subhead">Tim Klinis Online</div>
                        <div class="ds-muted small mt-1">Respon cepat dan alur yang jelas.</div>
                    </div>
                    <div class="col-lg-8 p-4 p-md-5">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl ds-info-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-clock"></i></span>
                                        <div class="fw-bold">Buka 24/7</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Tidak perlu menunggu jam klinik.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl ds-info-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-truck-medical"></i></span>
                                        <div class="fw-bold">Panduan Langkah</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Arahkan tindakan selanjutnya.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl ds-info-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-user-doctor"></i></span>
                                        <div class="fw-bold">Tersambung Dokter</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Konsultasi terarah ke dokter.</div>
                                </div>
                            </div>

                            {{-- Optional Insurance/Billing (tanpa ubah logic route) --}}
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mt-1">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge badge-soft-primary rounded-pill">
                                            <i class="fa-solid fa-shield-heart me-1"></i> Opsional: Insurance / Billing
                                        </span>
                                        <span class="ds-muted small">Dukungan penjelasan biaya yang transparan.</span>
                                    </div>
                                    <a href="#booking" class="btn btn-primary">Mulai Reservasi</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- TESTIMONIAL --}}
    <section id="testimonial" class="ds-landing-section py-5 bg-white">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">Patient&apos;s Testimonial</span>
                <h2 class="mb-2">Pasien merasa lebih mudah dan aman</h2>
                <p class="mb-0">Feedback nyata dari pasien yang sudah menggunakan layanan reservasi dokter.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary-subtle text-primary rounded-circle p-3 me-3 ds-ring">
                                <i class="fa-solid fa-user-doctor fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Michael S.</div>
                                <div class="ds-muted small">Pasien reguler</div>
                            </div>
                        </div>
                        <p class="ds-muted mb-0">â€œReservasi online sangat mudah, tim medis responsif, dan waktu tunggu jauh lebih pendek. Saya merasa lebih tenang datang ke klinik.â€</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary-subtle text-primary rounded-circle p-3 me-3 ds-ring">
                                <i class="fa-solid fa-user-doctor fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Sarah H.</div>
                                <div class="ds-muted small">Pengguna baru</div>
                            </div>
                        </div>
                        <p class="ds-muted mb-0">â€œAplikasi ini membantu saya menemukan jadwal dokter yang cocok tanpa antre panjang. Notifikasi pengingat juga sangat berguna.â€</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DIREKTORI DOKTER --}}
    <section id="doctor-directory" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">Direktori Dokter</span>
                <h2 class="mb-2">Temukan Dokter Rawat Jalan Anda</h2>
                <p class="mb-0">Cari berdasarkan nama atau spesialis. Klik “Daftar Konsultasi” untuk lanjut pendaftaran akun pasien.</p>
            </div>

            {{-- pencarian & klasifikasi sederhana (client-side) --}}
            <div class="row g-3 justify-content-center mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white" style="border-color: rgba(148,163,184,0.35)"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="doctorSearch" type="search" class="form-control" placeholder="Cari dokter..." aria-label="Cari dokter">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select id="doctorSpecialty" class="form-select" style="border-color: rgba(148,163,184,0.35)" aria-label="Filter spesialis">
                        <option value="all" selected>Semua Spesialis</option>
                        <option value="Anak">Anak</option>
                        <option value="Bedah">Bedah</option>
                        <option value="Umum">Umum</option>
                        <option value="Penyakit Dalam">Penyakit Dalam</option>
                    </select>
                </div>
            </div>

            {{-- daftar kartu grid (contoh data statis; siap diganti dinamis dari backend) --}}
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="doctorGrid" style="margin-top: 0.5rem;">

                @php
                    $doctors = [
                        ['name'=>'Dr. Maya Firda','specialty'=>'Anak','rating'=>4.8,'patients'=>1200,'status'=>'Terverifikasi'],
                        ['name'=>'Dr. Ryan Putra','specialty'=>'Bedah','rating'=>4.7,'patients'=>980,'status'=>'Terverifikasi'],
                        ['name'=>'Dr. Indah Sari','specialty'=>'Umum','rating'=>4.6,'patients'=>860,'status'=>'Terverifikasi'],
                        ['name'=>'Dr. Andika Pratama','specialty'=>'Penyakit Dalam','rating'=>4.9,'patients'=>1420,'status'=>'Terverifikasi'],
                        ['name'=>'Dr. Siti Nurhaliza','specialty'=>'Umum','rating'=>4.5,'patients'=>730,'status'=>'Terverifikasi'],
                        ['name'=>'Dr. Raka Wijaya','specialty'=>'Bedah','rating'=>4.6,'patients'=>810,'status'=>'Terverifikasi'],
                    ];
                @endphp

                @foreach($doctors as $d)
                    <div class="col doctor-card" data-specialty="{{ $d['specialty'] }}" data-name="{{ $d['name'] }}">
                        <div class="h-100" style="border-radius: 1.5rem; border: 1px solid rgba(148,163,184,0.35); overflow:hidden; box-shadow: 0 10px 25px rgba(2,6,23,0.04); transition: transform .2s ease, box-shadow .2s ease;">
                            <div style="height: 84px; background: linear-gradient(90deg, rgba(28,105,212,0.95) 0%, rgba(124,58,237,0.85) 100%);"></div>

                            <div class="p-4" style="margin-top: -36px;">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div>
                                        <div class="fw-bold" style="font-size: 1.05rem; color:#0f172a;">{{ $d['name'] }}</div>
                                        <div class="ds-muted small mt-1">{{ $d['specialty'] }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-warning" style="font-size: 0.95rem; font-weight: 800;">
                                            @for($i=1;$i<=5;$i++)
                                                <i class="fa-solid fa-star" style="opacity: {{ ($d['rating'] >= $i) ? 1 : 0.35 }};"></i>
                                            @endfor
                                        </div>
                                        <div class="ds-muted small">{{ number_format($d['rating'],1) }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <span class="ds-icon-pill" style="background: rgba(59,130,246,0.10); color:#1c69d4; border:1px solid rgba(59,130,246,0.18);">
                                        <i class="fa-solid fa-user-injured"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold" style="color:#0f172a;">{{ number_format($d['patients']) }} pasien</div>
                                        <div class="ds-muted small">Ditangani</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <span class="badge" style="background: rgba(34,197,94,0.12); color:#166534; border:1px solid rgba(34,197,94,0.20); font-weight:800;">
                                        <i class="fa-solid fa-circle-check me-1"></i>{{ $d['status'] }}
                                    </span>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('register') }}" class="btn btn-primary w-100" style="border-radius: 1rem; font-weight:800;">
                                        Daftar Konsultasi <i class="fa-solid fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <style>
                #doctor-directory .doctor-card > div:hover{
                    transform: translateY(-4px);
                    box-shadow: 0 18px 40px rgba(2,6,23,0.10) !important;
                }
            </style>

            <script>
                (function(){
                    const search = document.getElementById('doctorSearch');
                    const specialty = document.getElementById('doctorSpecialty');
                    const cards = Array.from(document.querySelectorAll('#doctorGrid .doctor-card'));

                    function apply(){
                        const q = (search.value || '').toLowerCase().trim();
                        const spec = specialty.value;

                        cards.forEach(card => {
                            const name = (card.getAttribute('data-name') || '').toLowerCase();
                            const cardSpec = card.getAttribute('data-specialty');
                            const matchesSpec = (spec === 'all') || (cardSpec === spec);
                            const matchesQ = !q || name.includes(q);
                            card.style.display = (matchesSpec && matchesQ) ? '' : 'none';
                        });
                    }

                    if(search){ search.addEventListener('input', apply); }
                    if(specialty){ specialty.addEventListener('change', apply); }
                })();
            </script>
        </div>
    </section>

    {{-- BOOKING (WAJIB: logic @auth & route tidak boleh diubah) --}}
    <section id="booking" class="ds-landing-section py-5 ds-panel-soft">

        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-2">Siap untuk membuat reservasi dokter pertama Anda?</h2>
                    <p class="ds-muted mb-4">
                        Mulai dengan melakukan pendaftaran, kemudian pilih dokter dan jadwal yang paling sesuai.
                        Sistem kami akan membantu Anda mengatur semuanya secara otomatis.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            Daftar Akun Pasien
                        </a>

                        @auth
                            @if(auth()->user() && auth()->user()->role === 'pasien')
                                <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-outline-primary btn-lg">
                                    Reservasi Sekarang
                                </a>
                            @else
                                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
                                    Lihat Beranda
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                Login untuk Reservasi
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER SISTEM --}}
    <footer id="contact" class="pt-5 pb-4" style="background: linear-gradient(180deg, #ffffff 0%, rgba(28,105,212,0.03) 100%); border-top: 1px solid rgba(148,163,184,0.35); position: relative; overflow: visible;">
        <div aria-hidden="true" style="position:absolute; top:-110px; right:-120px; width:520px; height:520px; background: radial-gradient(circle at 30% 30%, rgba(28,105,212,0.18) 0%, rgba(34,197,94,0.10) 45%, rgba(255,255,255,0) 72%); pointer-events:none;"></div>
        <div class="container">
            <div class="row g-4">
                {{-- Kolom 1 --}}
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="ds-icon-pill"><i class="fa-solid fa-stethoscope"></i></span>
                        <h5 class="fw-bold mb-0">JKN MediReservasi</h5>
                    </div>
                    <p class="ds-muted mb-0">
                        Sistem rujukan JKN <span class="fw-bold">MediReservasi</span> untuk RS Harapan Bangsa.
                        Membantu proses reservasi dan pengelolaan kunjungan agar lebih tertib dan nyaman.
                    </p>
                </div>

                {{-- Kolom 2 --}}
                <div class="col-12 col-md-4">
                    <h6 class="fw-semibold">Bantuan & Kontak</h6>
                    <p class="ds-muted mb-1">Nomor bantuan darurat klinis: <span class="fw-bold">(021) 0000-0000</span></p>
                    <p class="ds-muted mb-1">Alamat Rumah Sakit: Jl. Harapan Bangsa No. 123, Tulungagung, Jawa Timur</p>
                    <p class="ds-muted mb-0">Email terakreditasi IT paripurna: <span class="fw-bold">it@rs-harapanbangsa.id</span></p>
                </div>

                {{-- Kolom 3 --}}
                <div class="col-12 col-md-4">
                    <h6 class="fw-semibold">Navigasi Cepat</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#hero" class="ds-footer-link">Beranda</a></li>
                        <li><a href="#features" class="ds-footer-link">Layanan Utama</a></li>
                        <li><a href="#facilities" class="ds-footer-link">Fasilitas</a></li>
                        <li><a href="#doctor-directory" class="ds-footer-link">Direktori Dokter</a></li>
                        <li>
                            <a href="#" class="ds-footer-link">Kebijakan Privasi</a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(148,163,184,0.35);" />

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="ds-muted">&copy; {{ date('Y') }} JKN MediReservasi RS Harapan Bangsa. All rights reserved.</small>
                <div class="d-flex gap-3">
                    <a href="#" class="ds-footer-link"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="ds-footer-link"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="ds-footer-link"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>

</div>
@endsection


