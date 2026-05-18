@extends('layouts.app')

@section('title', 'Reservasi Dokter - Pesan Jadwal Online')

@section('content')
<div class="ds-landing-bg">
    {{-- HERO: 2 kolom + 2 CTA (tanpa ubah logic reservasi/route) --}}
    <section id="hero" class="ds-landing-section py-5 py-lg-5">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="ds-hero-badge mb-3">
                        <i class="fa-solid fa-stethoscope"></i>
                        <span>Reservasi Dokter Online, Cepat dan Mudah</span>
                    </div>

                    <h1 class="fw-bold mb-3 ds-hero-title">
                        Reservasi Dokter Online,<br class="d-none d-md-block" />
                        Cepat dan Mudah
                    </h1>

                    <p class="ds-muted mb-4 ds-hero-copy">
                        Pilih dokter terverifikasi, atur jadwal kunjungan, dan dapatkan pengingat otomatis.
                        Semua dirancang agar Anda tidak perlu antre.
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

                <div class="col-lg-6">
                    <div class="ds-glass-panel p-3 p-md-4">
                        <div class="row g-3 align-items-stretch">
                            <div class="col-12">
                                <div class="ds-hero-illustration">
                                    <img
                                        src="{{ asset('assets/dokter1.jpg') }}"
                                        alt="Ilustrasi reservasi dokter"
                                        loading="lazy"
                                    />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 ds-rounded-xl ds-info-card">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="ds-icon-pill"><i class="fa-solid fa-calendar-days"></i></span>
                                                <div>
                                                    <div class="fw-bold">Jadwal Terstruktur</div>
                                                    <div class="ds-muted small">Slot tersedia real-time</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 ds-rounded-xl ds-info-card">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="ds-icon-pill"><i class="fa-solid fa-bell"></i></span>
                                                <div>
                                                    <div class="fw-bold">Pengingat Otomatis</div>
                                                    <div class="ds-muted small">Anda selalu ingat jadwal</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURE: Easily Book Your Doctor --}}
    <section id="features" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">
                    Easily Book Your Doctor
                </span>
                <h2 class="mb-2">Reservasi mudah dengan pengalaman yang nyaman</h2>
                <p class="mx-auto mb-0">Langkah cepat, dokter terverifikasi, dan komunikasi yang jelasâ€”untuk layanan kesehatan yang lebih baik.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="ds-feature-card">
                        <div class="ds-feature-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                        <h5 class="fw-bold">Booking Online</h5>
                        <p class="ds-muted mb-0">Atur jadwal kunjungan hanya dalam beberapa klik dari perangkat Anda.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ds-feature-card">
                        <div class="ds-feature-icon"><i class="fa-solid fa-user-shield"></i></div>
                        <h5 class="fw-bold">Dokter Terverifikasi</h5>
                        <p class="ds-muted mb-0">Bekerja sama dengan klinik/mitra sehingga kualitas layanan lebih terjamin.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ds-feature-card">
                        <div class="ds-feature-icon"><i class="fa-solid fa-bell"></i></div>
                        <h5 class="fw-bold">Notifikasi Otomatis</h5>
                        <p class="ds-muted mb-0">Pengingat jadwal membantu Anda datang tepat waktu.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="ds-feature-card">
                        <div class="ds-feature-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <h5 class="fw-bold">Lebih Nyaman & Efisien</h5>
                        <p class="ds-muted mb-0">Kurangi antrean dengan alur reservasi yang terstruktur.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FACILITIES AND SERVICES --}}
    <section id="facilities" class="ds-landing-section py-5 bg-white">
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

    {{-- TEAM DOKTER --}}
    <section id="team" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary ds-pill-badge mb-2">Meet Our Team</span>
                <h2 class="mb-2">Tim dokter profesional kami</h2>
                <p class="mb-0">Dokter ahli yang siap membantu pasien dengan layanan terbaik dan terpercaya.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div class="col">
                    <div class="team-card text-center ds-panel-border">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Maya Firda</div>
                        <div class="ds-muted small mt-1">Spesialis Anak</div>
                    </div>
                </div>
                <div class="col">
                    <div class="team-card text-center ds-panel-border">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Ryan Putra</div>
                        <div class="ds-muted small mt-1">Spesialis Bedah</div>
                    </div>
                </div>
                <div class="col">
                    <div class="team-card text-center ds-panel-border">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Indah Sari</div>
                        <div class="ds-muted small mt-1">Spesialis Umum</div>
                    </div>
                </div>
            </div>
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

    {{-- FOOTER --}}
    <footer id="contact" class="pt-5 pb-4 ds-footer-panel">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="ds-icon-pill"><i class="fa-solid fa-stethoscope"></i></span>
                        <h5 class="fw-bold mb-0">ReservasiDokter</h5>
                    </div>
                    <p class="ds-muted mb-0">
                        Platform reservasi dokter online untuk klinik dan rumah sakit yang membantu mengurangi antrean
                        dan memudahkan pengelolaan jadwal kunjungan.
                    </p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-semibold">Informasi Klinik</h6>
                    <p class="ds-muted mb-1">
                        Jl. Contoh Sehat No. 123<br>
                        Tulungagung, Jawa Timur
                    </p>
                    <p class="ds-muted mb-1">Telp/WA: 0812-0000-0000</p>
                    <p class="ds-muted mb-0">Email: info@reservasidokter.test</p>
                </div>

                <div class="col-md-4">
                    <h6 class="fw-semibold">Tautan Penting</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#hero" class="ds-footer-link">Beranda</a></li>
                        <li><a href="#facilities" class="ds-footer-link">Facilities</a></li>
                        <li><a href="#consultation" class="ds-footer-link">Konsultasi 24/7</a></li>
                        <li><a href="#" class="ds-footer-link">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 ds-divider" />

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="ds-muted">&copy; {{ date('Y') }} ReservasiDokter. All rights reserved.</small>
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


