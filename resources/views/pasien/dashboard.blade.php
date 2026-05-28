@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    <!-- Header Greeting & Status -->
    <div class="row g-4 mb-4">
        <!-- Greetings -->
        <div class="col-lg-8 col-md-12">
            <div class="card clinical-card h-100 p-4 d-flex justify-content-between">
                <div>
                    <span class="badge-soft-primary px-3 py-1 text-xs-caps">PATIENT DASHBOARD</span>
                    <h1 class="display-6 font-sans fw-bold text-dark mt-2 mb-1">
                        Halo, {{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}
                    </h1>
                    <p class="text-secondary mb-4 col-xl-10">
                        Pantau janji temu aktif, kemajuan antrean, dan riwayat pemeriksaan Anda dalam satu tempat terpadu.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary px-4 py-2-5 rounded-3 fw-semibold shadow-sm text-white">
                        <i class="bi bi-calendar-plus me-2"></i> Buat Reservasi
                    </a>
                    <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-primary px-4 py-2-5 rounded-3 fw-semibold bg-white border-light-subtle">
                        <i class="bi bi-clock-history me-2"></i> Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Info Widget -->
        <div class="col-lg-4 col-md-12">
            <div class="card clinical-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                    <button type="button"
                            class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle border-0"
                            style="width: 52px; height: 52px; cursor:pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#patientProfileModal"
                            aria-label="Lihat profil pasien">
                        <i class="bi bi-person-badge fs-4"></i>
                    </button>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold font-sans text-dark">{{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}</h6>
                        <span class="text-xs-caps text-secondary font-mono">ID PASIEN: {{ $patient->id ?? '-' }}</span>
                    </div>
                </div>


                <hr class="my-3 border-light">

                <div class="row text-center mt-2">
                    <div class="col-6 border-end border-light-subtle">
                        <div class="text-xs-caps text-secondary mb-1">Reservasi Aktif</div>
                        <h2 class="fw-bold text-primary font-mono m-0 mb-1">{{ $activeCount ?? 0 }}</h2>
                        <span class="text-xs text-muted">Aktif</span>
                    </div>
                    <div class="col-6">
                        <div class="text-xs-caps text-secondary mb-1">Reservasi Selesai</div>
                        <h2 class="fw-bold text-success font-mono m-0 mb-1">{{ $completedCount ?? 0 }}</h2>
                        <span class="text-xs text-muted">Selesai</span>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('pasien.profile.edit') }}" class="btn btn-outline-primary w-full py-1-5 rounded-3 fs-7 fw-semibold">
                        <i class="bi bi-gear-fill me-1"></i> Atur Profil & BPJS
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="ps-hero-left">
<!-- <div class="ps-poster" style="position:relative; overflow:hidden; padding:24px; border-radius:24px; background:linear-gradient(135deg, rgba(0,100,224,0.10), rgba(255,255,255,0.96)); border:1px solid rgba(14,165,233,0.18); box-shadow:0 24px 60px rgba(15,23,42,0.08);">
    <div style="position:absolute; inset:-40px; background:radial-gradient(circle at 20% 10%, rgba(14,165,233,0.25), transparent 35%), radial-gradient(circle at 90% 60%, rgba(34,197,94,0.18), transparent 40%); pointer-events:none;"></div>

    <div style="position:relative; z-index:1;">
        <div class="ps-label-badge" style="background:rgba(255,255,255,0.85); border:1px solid rgba(14,165,233,0.14); color:rgba(0,100,224,0.95);">POSTER DASHBOARD PASIEN</div>

        <h1 class="ps-hero-title" style="margin-top:12px; font-size:clamp(1.6rem,3vw,2.4rem); line-height:1.05;">Halo, {{ auth()->user()->name }}</h1>

        <p class="ps-hero-description" style="max-width:44rem; margin-bottom:18px;">
            Pantau janji temu, status reservasi, dan riwayat pemeriksaan Anda dalam satu poster ringkas yang informatif.
        </p>

        <div class="d-flex flex-wrap gap-2" style="gap:12px;">
            <a href="{{ route('pasien.reservasi.create') }}" class="ps-btn-primary" style="text-decoration:none;">Buat Reservasi</a>
            <a href="{{ route('pasien.reservasi.history') }}" class="ps-btn-secondary" style="text-decoration:none;">Lihat Riwayat</a>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:14px; margin-top:18px;">
            <div style="flex:1 1 180px; background:rgba(255,255,255,0.9); border:1px solid rgba(14,165,233,0.14); border-radius:18px; padding:14px 16px;">
                <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:rgba(15,23,42,0.6);">Reservasi Aktif</div>
                <div style="font-size:1.9rem; font-weight:900; color:rgba(0,100,224,0.95); margin-top:4px;">{{ $activeCount ?? 0 }}</div>
            </div>
            <div style="flex:1 1 180px; background:rgba(255,255,255,0.9); border:1px solid rgba(34,197,94,0.14); border-radius:18px; padding:14px 16px;">
                <div style="font-size:0.78rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:rgba(15,23,42,0.6);">Reservasi Selesai</div>
                <div style="font-size:1.9rem; font-weight:900; color:rgba(22,101,52,0.95); margin-top:4px;">{{ $completedCount ?? 0 }}</div>
            </div>
        </div>
    </div>
</div> -->
                </div>
            </div>
            <!-- <div class="col-lg-4, pb-4">
                <div class="ps-patient-summary-card">
                    <div class="ps-patient-summary-header">
                        <div class="ps-patient-avatar">
                            @if(optional($patient)->photo)
                                <img src="{{ asset('storage/' . $patient->photo) }}" alt="Foto Pasien">
                            @else
                                <i class="fas fa-user-injured"></i>
                            @endif
                        </div>
                        <div>
                            <div class="ps-patient-name">{{ auth()->user()->name }}</div>
                            <div class="ps-patient-id">ID Pasien: {{ optional($patient)->id ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="ps-summary-metrics">
                        <div class="ps-summary-item">
                            <span class="ps-summary-label">Reservasi aktif</span>
                            <span class="ps-summary-value">{{ $activeCount ?? 0 }}</span>
                        </div>
                        <div class="ps-summary-item">
                            <span class="ps-summary-label">Selesai</span>
                            <span class="ps-summary-value">{{ $completedCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</section>

@if(session('error'))
    <div class="ps-alert ps-alert-critical mb-4">
        <div class="ps-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            {{ session('error') }}
        </div>
    </div>
@endif

@if(! isset($patient) || ! $patient)
    <div class="ps-alert ps-alert-warning mb-4">
        <div class="ps-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.
        </div>
        <a href="{{ route('pasien.profile.edit') }}" class="ps-text-link">Lengkapi Profil</a>
    </div>
@endif

<!-- <section class="mb-4">
    <div class="container-fluid">
        <div class="row g-3 ps-stat-row">
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-primary"></div>
                    <div class="ps-stat-value">{{ $activeCount ?? 0 }}</div>
                    <div class="ps-stat-label">Reservasi Aktif</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-success"></div>
                    <div class="ps-stat-value">{{ $completedCount ?? 0 }}</div>
                    <div class="ps-stat-label">Reservasi Selesai</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-ink"></div>
                    <div class="ps-stat-value">{{ $totalCount ?? 0 }}</div>
                    <div class="ps-stat-label">Total Reservasi</div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="mb-4">
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="ps-table-wrapper">
                    <div class="ps-table-header">
                        <h5 class="ps-table-title">Reservasi Terbaru</h5>
                        <p class="ps-table-subtitle">Daftar reservasi terbaru Anda dengan dokter.</p>
                    </div>

                    @if(empty($appointments) || $appointments->isEmpty())
                        <div class="ps-empty-state">
                            <div class="ps-empty-title">Belum ada reservasi</div>
                            <div class="ps-empty-description">Buat reservasi baru untuk mulai berkonsultasi dengan dokter.</div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="ps-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th>Spesialisasi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $reservation)
                                        <tr>
                                            <td>{{ $reservation->appointment_date->format('d-m-Y') }}</td>
                                            <td>{{ optional($reservation->doctor->user)->name ?? '-' }}</td>
                                            <td>{{ optional($reservation->doctor->specialization)->name ?? '-' }}</td>
                                            <td>
                                                <span class="ps-badge-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('pasien.reservasi.show', $reservation) }}" class="ps-text-link">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4">
                <div class="ps-upcoming-card">
                    <h5 class="ps-card-title">Reservasi Mendatang</h5>
                    @php
                        $upcomingReservations = $appointments->whereIn('status', ['pending', 'in_progress'])->take(3);
                    @endphp
                    @if($upcomingReservations->isEmpty())
                        <div class="ps-empty-state">
                            <div class="ps-empty-title">Tidak ada jadwal berikutnya</div>
                            <div class="ps-empty-description">Reservasi berikutnya akan muncul di sini.</div>
                        </div>
                    @else
                        @foreach($upcomingReservations as $reservation)
                            <div class="ps-upcoming-item">
                                <div>
                                    <div class="ps-upcoming-doctor">{{ optional($reservation->doctor->user)->name ?? '-' }}</div>
                                    <div class="ps-upcoming-meta">{{ $reservation->appointment_date->format('d-m-Y') }} • {{ optional($reservation->doctor->specialization)->name ?? '-' }}</div>
                                </div>
                                <span class="ps-badge-scheduled">Dijadwalkan</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="container-fluid">
        <div class="ps-history-wrapper">
            <div class="ps-history-header">
                <div>
                    <h5 class="ps-card-title">Riwayat Pemeriksaan</h5>
                    <p class="ps-table-subtitle">Catatan medis terbaru Anda.</p>
                </div>
            </div>
            @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                <div class="ps-empty-state">
                    <div class="ps-empty-title">Belum ada riwayat pemeriksaan</div>
                    <div class="ps-empty-description">Riwayat akan muncul di sini setelah reservasi selesai.</div>
                </div>
            @else
                <div class="ps-history-list">
                    @foreach($completedAppointments as $appointment)
                        <div class="ps-history-item">
                            <div>
                                <div class="ps-upcoming-doctor">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</div>
                                <div class="ps-upcoming-meta">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</div>
                            </div>
                            <span class="ps-badge-completed">Selesai</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<div class="ps-dashboard-footnote py-3">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 text-muted small">
            <span>Informasi reservasi dan riwayat Anda diperbarui secara real-time.</span>
            <span>
                <a href="#" class="ps-footer-link">Kebijakan Privasi</a>
                ·
                <a href="#" class="ps-footer-link">Syarat & Ketentuan</a>
            </span>
        </div>
    </div>
</div>
<!-- Patient Profile Modal -->
<div class="modal fade" id="patientProfileModal" tabindex="-1" aria-labelledby="patientProfileModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background: rgba(2,132,196,0.12);">
                <h5 class="modal-title font-sans fw-bold" id="patientProfileModalLabel">
                    <i class="bi bi-person-badge me-2"></i> Profil Pasien
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" style="background: rgba(2,132,196,0.08); border: 1px solid rgba(2,132,196,0.18);">
                            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 90px; height: 90px; margin: 0 auto;">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                            <div class="text-center mt-3">
                                <div class="fw-bold">{{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}</div>
                                <div class="text-muted small">ID: {{ $patient->id ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="small text-muted">Gender</div>
                                <div class="fw-semibold">{{ $patient->gender ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Tanggal Lahir</div>
                                <div class="fw-semibold">
                                    @if(isset($patient->date_of_birth) && $patient->date_of_birth)
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Golongan Darah</div>
                                <div class="fw-semibold">{{ $patient->blood_type ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">No. Identitas</div>
                                <div class="fw-semibold">{{ $patient->identity_number ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">No. Telepon</div>
                                <div class="fw-semibold">{{ $patient->phone ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Alamat</div>
                                <div class="fw-semibold">{{ $patient->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <a href="{{ route('pasien.profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-gear-fill me-2"></i> Edit Profil
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
