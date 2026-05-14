@extends('layouts.app')

@section('title', 'Reservasi Dokter - Pesan Jadwal Online')

@section('content')
<style>
    /* Bright turquoise–aqua landing helper overrides (keep isolated in this view) */
    .ds-landing-bg {
        position: relative;
        overflow: hidden;
    }

    .ds-landing-bg::before {
        content: "";
        position: absolute;
        inset: -220px -140px auto -140px;
        height: 620px;
        background:
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.20), transparent 58%),
            radial-gradient(circle at bottom right, rgba(20, 184, 166, 0.18), transparent 48%);
        pointer-events: none;
        z-index: 0;
    }

    .ds-landing-bg::after {
        content: "";
        position: absolute;
        left: -10%;
        right: -10%;
        top: 52%;
        height: 420px;
        background: radial-gradient(circle at 50% 0%, rgba(11, 184, 178, 0.16), transparent 55%);
        pointer-events: none;
        z-index: 0;
    }

    .ds-landing-section {
        position: relative;
        z-index: 1;
    }

    .ds-hero-illustration img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 28px;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.10);
        border: 1px solid rgba(14, 165, 233, 0.16);
        background: rgba(255,255,255,0.8);
    }

    .ds-glass-panel {
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(14, 165, 233, 0.14);
        border-radius: 28px;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .ds-hero-badge {
        background: rgba(11, 184, 178, 0.12);
        border: 1px solid rgba(11, 184, 178, 0.22);
        color: #0b766e;
        font-weight: 750;
        border-radius: 999px;
        padding: .55rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }

    .ds-feature-card {
        border-radius: 24px;
        padding: 1.6rem;
        box-shadow: 0 18px 46px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(14, 165, 233, 0.12);
        background: rgba(255, 255, 255, 0.95);
        transition: transform 180ms ease, box-shadow 180ms ease;
        height: 100%;
    }

    .ds-feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 22px 52px rgba(15, 23, 42, 0.10);
    }

    .ds-feature-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: rgba(14, 165, 233, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0b76ac;
        margin-bottom: 1rem;
        border: 1px solid rgba(14, 165, 233, 0.12);
    }

    .ds-section-heading h2 {
        font-weight: 850;
        letter-spacing: -0.02em;
        color: rgba(15, 23, 42, 0.96);
        font-size: clamp(1.8rem, 2.6vw, 2.6rem);
    }

    .ds-section-heading p {
        color: rgba(15, 23, 42, 0.70);
        max-width: 760px;
    }

    .ds-muted {
        color: rgba(15, 23, 42, 0.65) !important;
    }

    .ds-rounded-xxl { border-radius: 28px; }
    .ds-rounded-xl { border-radius: 24px; }

    .ds-icon-pill {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: rgba(14, 165, 233, 0.12);
        border: 1px solid rgba(14, 165, 233, 0.14);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0b76ac;
        flex: 0 0 auto;
    }

    .ds-consult-card {
        border-radius: 28px;
        border: 1px solid rgba(14, 165, 233, 0.14);
        background: linear-gradient(180deg, rgba(255,255,255,0.90), rgba(255,255,255,0.75));
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .ds-consult-hero {
        background: radial-gradient(circle at top left, rgba(14, 165, 233, 0.22), transparent 55%),
                    radial-gradient(circle at bottom right, rgba(11, 184, 178, 0.16), transparent 45%);
        border-right: 1px solid rgba(14, 165, 233, 0.12);
    }

    @media (max-width: 991.98px) {
        .ds-consult-hero { border-right: none; border-bottom: 1px solid rgba(14, 165, 233, 0.12); }
    }

    .ds-avatar {
        width: 72px;
        height: 72px;
        border-radius: 999px;
        background: rgba(14, 165, 233, 0.10);
        border: 1px solid rgba(14, 165, 233, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b76ac;
        font-size: 2.2rem;
        box-shadow: 0 12px 35px rgba(2, 6, 23, 0.06);
        margin-bottom: 1rem;
    }

    .ds-footer-link {
        text-decoration: none;
        color: rgba(15, 23, 42, 0.60);
    }

    .ds-footer-link:hover { text-decoration: underline; }
</style>

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

                    <h1 class="fw-bold mb-3" style="font-size: clamp(2rem, 3.2vw, 3rem); line-height: 1.05; letter-spacing: -0.02em;">
                        Reservasi Dokter Online,<br class="d-none d-md-block" />
                        Cepat dan Mudah
                    </h1>

                    <p class="ds-muted mb-4" style="max-width: 520px;">
                        Pilih dokter terverifikasi, atur jadwal kunjungan, dan dapatkan pengingat otomatis.
                        Semua dirancang agar Anda tidak perlu antre.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        {{-- CTA 1: tetap mengarah ke bagian booking --}}
                        <a href="#booking" class="btn btn-primary btn-lg px-4" style="border-radius: 999px;">
                            Reservasi Sekarang
                        </a>
                        {{-- CTA 2: scroll ke fitur/daftar dokter --}}
                        <a href="#facilities" class="btn btn-secondary btn-lg px-4" style="border-radius: 999px;">
                            Lihat Layanan
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge badge-soft-primary rounded-pill" style="background-color: rgba(14, 165, 233, 0.14); color:#0d6efd; border: 1px solid rgba(14,165,233,0.16);">
                            <i class="fa-solid fa-clock me-1"></i> 24/7 Online Consultation
                        </span>
                        <span class="badge badge-soft-success rounded-pill" style="background-color: rgba(20, 184, 166, 0.14); color:#0f766e; border: 1px solid rgba(20,184,166,0.18);">
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
                                        <div class="p-3 ds-rounded-xl" style="background: rgba(255,255,255,0.65); border: 1px solid rgba(14,165,233,0.12);">
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
                                        <div class="p-3 ds-rounded-xl" style="background: rgba(255,255,255,0.65); border: 1px solid rgba(14,165,233,0.12);">
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
                <span class="badge bg-primary-subtle text-primary mb-2" style="border-radius: 999px;">
                    Easily Book Your Doctor
                </span>
                <h2 class="mb-2">Reservasi mudah dengan pengalaman yang nyaman</h2>
                <p class="mx-auto mb-0">Langkah cepat, dokter terverifikasi, dan komunikasi yang jelas—untuk layanan kesehatan yang lebih baik.</p>
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
                    <span class="badge bg-primary-subtle text-primary mb-2" style="border-radius: 999px;">Facilities and Services</span>
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
                            <div class="ds-rounded-xxl p-4" style="background: radial-gradient(circle at top left, rgba(14,165,233,0.16), transparent 55%), rgba(255,255,255,0.95); border: 1px solid rgba(14,165,233,0.14); box-shadow: 0 18px 46px rgba(15, 23, 42, 0.06);">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill" style="width:48px;height:48px; border-radius:16px;"><i class="fa-solid fa-heart-pulse"></i></span>
                                    <div>
                                        <div class="fw-bold">Pelayanan Medis Terpadu</div>
                                        <div class="ds-muted small mt-1">Booking, konsultasi, dan pengingat jadwal.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ds-rounded-xxl p-4" style="background: radial-gradient(circle at top left, rgba(20,184,166,0.14), transparent 55%), rgba(255,255,255,0.95); border: 1px solid rgba(20,184,166,0.18); box-shadow: 0 18px 46px rgba(15, 23, 42, 0.06);">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="ds-icon-pill" style="width:48px;height:48px; border-radius:16px;"><i class="fa-solid fa-file-medical"></i></span>
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
                                                style="max-width: 420px; width:100%; border-radius: 24px; border: 1px solid rgba(14,165,233,0.14); box-shadow: 0 18px 46px rgba(15, 23, 42, 0.06);"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {{-- hidden link target booking for smooth UX --}}
                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <a href="#booking" class="btn btn-primary" style="border-radius: 999px;">Pelajari Lebih Lanjut</a>
                                    <a href="#team" class="btn btn-secondary" style="border-radius: 999px;">Lihat Tim Dokter</a>
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
                <span class="badge bg-primary-subtle text-primary mb-2" style="border-radius: 999px;">24/7 Online Consultation</span>
                <h2 class="mb-2">Konsultasi online kapan saja, di mana saja</h2>
                <p class="mb-0">Butuh saran medis ringan terlebih dahulu? Kami bantu mengarahkan Anda untuk langkah berikutnya.</p>
            </div>

            <div class="ds-consult-card">
                <div class="row g-0">
                    <div class="col-lg-4 ds-consult-hero p-4 p-md-5 text-center">
                        <div class="mb-3">
                            <div class="ds-avatar" style="margin: 0 auto;"> <i class="fa-solid fa-headset"></i></div>
                        </div>
                        <div class="fw-bold" style="font-size: 1.1rem;">Tim Klinis Online</div>
                        <div class="ds-muted small mt-1">Respon cepat dan alur yang jelas.</div>
                    </div>
                    <div class="col-lg-8 p-4 p-md-5">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl" style="background: rgba(255,255,255,0.75); border: 1px solid rgba(14,165,233,0.12);">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-clock"></i></span>
                                        <div class="fw-bold">Buka 24/7</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Tidak perlu menunggu jam klinik.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl" style="background: rgba(255,255,255,0.75); border: 1px solid rgba(14,165,233,0.12);">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-truck-medical"></i></span>
                                        <div class="fw-bold">Panduan Langkah</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Arahkan tindakan selanjutnya.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 ds-rounded-xl" style="background: rgba(255,255,255,0.75); border: 1px solid rgba(14,165,233,0.12);">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ds-icon-pill"><i class="fa-solid fa-user-doctor"></i></span>
                                        <div class="fw-bold">Tersambung Dokter</div>
                                    </div>
                                    <div class="ds-muted small mt-2">Konsultasi terarah ke dokter.</div>
                                </div>
                            </div>

                            {{-- Optional Insurance/Billing (tanpa ubah logic route) --}}
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between" style="margin-top: 0.25rem;">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge badge-soft-primary rounded-pill">
                                            <i class="fa-solid fa-shield-heart me-1"></i> Opsional: Insurance / Billing
                                        </span>
                                        <span class="ds-muted small">Dukungan penjelasan biaya yang transparan.</span>
                                    </div>
                                    <a href="#booking" class="btn btn-primary" style="border-radius: 999px;">Mulai Reservasi</a>
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
                <span class="badge bg-primary-subtle text-primary mb-2" style="border-radius: 999px;">Patient&apos;s Testimonial</span>
                <h2 class="mb-2">Pasien merasa lebih mudah dan aman</h2>
                <p class="mb-0">Feedback nyata dari pasien yang sudah menggunakan layanan reservasi dokter.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary-subtle text-primary rounded-circle p-3 me-3" style="border: 1px solid rgba(14,165,233,0.16);">
                                <i class="fa-solid fa-user-doctor fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Michael S.</div>
                                <div class="ds-muted small">Pasien reguler</div>
                            </div>
                        </div>
                        <p class="ds-muted mb-0">“Reservasi online sangat mudah, tim medis responsif, dan waktu tunggu jauh lebih pendek. Saya merasa lebih tenang datang ke klinik.”</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="badge bg-primary-subtle text-primary rounded-circle p-3 me-3" style="border: 1px solid rgba(14,165,233,0.16);">
                                <i class="fa-solid fa-user-doctor fa-lg"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Sarah H.</div>
                                <div class="ds-muted small">Pengguna baru</div>
                            </div>
                        </div>
                        <p class="ds-muted mb-0">“Aplikasi ini membantu saya menemukan jadwal dokter yang cocok tanpa antre panjang. Notifikasi pengingat juga sangat berguna.”</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TEAM DOKTER --}}
    <section id="team" class="ds-landing-section py-5">
        <div class="container">
            <div class="ds-section-heading text-center mb-4 mb-lg-5">
                <span class="badge bg-primary-subtle text-primary mb-2" style="border-radius: 999px;">Meet Our Team</span>
                <h2 class="mb-2">Tim dokter profesional kami</h2>
                <p class="mb-0">Dokter ahli yang siap membantu pasien dengan layanan terbaik dan terpercaya.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <div class="col">
                    <div class="team-card text-center" style="border: 1px solid rgba(14,165,233,0.12);">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Maya Firda</div>
                        <div class="ds-muted small mt-1">Spesialis Anak</div>
                    </div>
                </div>
                <div class="col">
                    <div class="team-card text-center" style="border: 1px solid rgba(14,165,233,0.12);">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Ryan Putra</div>
                        <div class="ds-muted small mt-1">Spesialis Bedah</div>
                    </div>
                </div>
                <div class="col">
                    <div class="team-card text-center" style="border: 1px solid rgba(14,165,233,0.12);">
                        <div class="ds-avatar mx-auto" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></div>
                        <div class="fw-bold">Dr. Indah Sari</div>
                        <div class="ds-muted small mt-1">Spesialis Umum</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BOOKING (WAJIB: logic @auth & route tidak boleh diubah) --}}
    <section id="booking" class="ds-landing-section py-5" style="background: rgba(255,255,255,0.65);">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-2">Siap untuk membuat reservasi dokter pertama Anda?</h2>
                    <p class="ds-muted mb-4">
                        Mulai dengan melakukan pendaftaran, kemudian pilih dokter dan jadwal yang paling sesuai.
                        Sistem kami akan membantu Anda mengatur semuanya secara otomatis.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg" style="border-radius: 999px;">
                            Daftar Akun Pasien
                        </a>

                        @auth
                            @if(auth()->user() && auth()->user()->role === 'pasien')
                                <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-outline-primary btn-lg" style="border-radius: 999px;">
                                    Reservasi Sekarang
                                </a>
                            @else
                                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg" style="border-radius: 999px;">
                                    Lihat Beranda
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg" style="border-radius: 999px;">
                                Login untuk Reservasi
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer id="contact" class="pt-5 pb-4" style="background: rgba(255,255,255,0.85); border-top: 1px solid rgba(14,165,233,0.12);">
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

            <hr class="my-4" style="border-color: rgba(14,165,233,0.12);" />

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

