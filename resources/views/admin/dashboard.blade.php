@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan statistik reservasi hari ini')

@section('content')
{{-- HERO SUMMARY --}}
<section class="mb-4" aria-label="Admin Landing">
    <div class="admin-card hero-reservasi position-relative p-4 p-lg-5 d-flex flex-column flex-lg-row align-items-start justify-content-between gap-4" role="region" aria-label="Hero Admin">
        {{-- Unsplash hero image background --}}
        <div class="hero-reservasi__bg" aria-hidden="true">
            <img
                src="{{ asset('assets/hero-1.jpg') }}"
                alt="Hero background"
            />
            <div class="hero-reservasi__overlay"></div>
        </div>

        <div class="hero-reservasi__content">
            <div class="row w-100 g-3 align-items-center">

                <div class="col-12 col-lg-7">
<span class="badge badge-status-info mb-3">Control Center</span>
                    <h1 class="ds-h1 mb-2">Selamat datang, Admin</h1>
                    <p class="ds-body mb-3">Kelola reservasi, antrian, dokter, dan laporan dengan tampilan yang rapi, modern, dan informatif.</p>
                    <div class="d-flex flex-wrap gap-2">
<a href="{{ route('admin.approvals.index') }}" class="btn btn-primary">Lihat Riwayat</a>
<a href="{{ route('admin.queues.index') }}" class="btn btn-secondary">Kelola Antrian</a>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Kelola Dokter</a>
                    </div>

                </div>

                <div class="col-12 col-lg-5">
                    <div class="text-lg-end">
                        <p class="ds-caption text-uppercase mb-1">Ringkasan hari ini</p>
                        <div class="d-flex flex-column gap-2 align-items-lg-end">
                            <div class="badge badge-status-info px-4 py-2">Total: <strong>{{ $todayStats['total'] }}</strong></div>
                            <div class="badge badge-status-success px-4 py-2">Selesai: <strong>{{ $todayStats['done'] }}</strong></div>
                            <div class="badge badge-status-info px-4 py-2">Approved: <strong>{{ $todayStats['approved'] }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="admin-stat-grid mb-4">
    <div class="admin-card p-4 h-100 ds-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <p class="ds-caption text-uppercase mb-1">Antrian Real-Time</p>
                <h4 class="ds-h2 mb-0">{{ $dailyQueues->count() }}</h4>
            </div>
            <i class="fas fa-users fa-2x text-primary"></i>
        </div>
        <p class="ds-body mb-0">Lacak status pasien hari ini secara langsung.</p>
        <a href="{{ route('admin.queues.index') }}" class="btn btn-primary mt-4">Kelola Antrian</a>
    </div>
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <p class="text-uppercase small mb-1">Dokter Aktif</p>
                <h4 class="fw-bold mb-0">{{ $doctorStats->count() }}</h4>
            </div>
            <div class="admin-stat-card__icon bg-success-soft text-success">
                <i class="fas fa-user-md fa-lg"></i>
            </div>
        </div>
        <p class="text-muted small mb-0">Kelola data dokter dan spesialisasi secara cepat.</p>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-ghost mt-4">Kelola Dokter</a>
    </div>
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <p class="text-uppercase text-secondary small mb-1">Laporan Mingguan</p>
                <h4 class="fw-bold mb-0">{{ $todayServedCount ?? 0 }}</h4>
            </div>
            <div class="admin-stat-card__icon bg-primary-soft text-primary">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
        </div>
        <p class="text-muted small mb-0">Tampilkan ringkasan kunjungan pasien dalam periode.</p>
        <a href="{{ route('admin.reports.visitation') }}" class="btn btn-secondary btn-ghost mt-4">Lihat Laporan</a>
    </div>
</div>

{{-- KARTU STATISTIK UTAMA --}}
<div class="admin-stat-grid mb-4">
    <div class="admin-stat-card admin-stat-card--accent-primary p-4 h-100">
        <div class="admin-stat-card__header">
            <div>
                <p class="admin-stat-card__kicker mb-0">Total Hari Ini</p>
                <h4 class="mb-0">{{ $todayStats['total'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi yang tercatat hari ini.</p>
            </div>
            <div class="admin-stat-card__icon">
                <i class="fas fa-calendar-check fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="admin-stat-card admin-stat-card--accent-primary p-4 h-100">
        <div class="admin-stat-card__header">
            <div>
                <p class="admin-stat-card__kicker mb-0">Approved</p>
                <h4 class="mb-0">{{ $todayStats['approved'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi yang disetujui hari ini.</p>
            </div>
            <div class="admin-stat-card__icon">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="admin-stat-card admin-stat-card--accent-success p-4 h-100">
        <div class="admin-stat-card__header">
            <div>
                <p class="admin-stat-card__kicker mb-0">Selesai</p>
                <h4 class="mb-0">{{ $todayStats['done'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi yang sudah selesai ditangani.</p>
            </div>
            <div class="admin-stat-card__icon bg-success-soft text-success">
                <i class="fas fa-check-double fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="admin-stat-card admin-stat-card--accent-danger p-4 h-100">
        <div class="admin-stat-card__header">
            <div>
                <p class="admin-stat-card__kicker mb-0">Dibatalkan</p>
                <h4 class="mb-0">{{ $todayStats['cancelled'] ?? 0 }}</h4>
                <p class="mb-0 text-muted small">Reservasi yang dibatalkan hari ini.</p>
            </div>
            <div class="admin-stat-card__icon bg-critical-soft text-danger">
                <i class="fas fa-times-circle fa-lg"></i>
            </div>
        </div>
    </div>
</div>

{{-- MANAGEMENT EXPERIENCE --}}
<div class="admin-stat-grid mb-4">
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-start gap-3 mb-3">
            <span class="badge badge-status-info p-3"><i class="fas fa-clock"></i></span>
            <div>
                <h6 class="mb-1 fw-bold">Antrian Real-Time</h6>
                <p class="text-muted small mb-0">Pantau posisi antrian dan alur pasien dengan cepat.</p>
            </div>
        </div>
        <a href="{{ route('admin.queues.index') }}" class="btn btn-secondary btn-ghost">Buka Antrian</a>
    </div>
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-start gap-3 mb-3">
            <span class="badge badge-status-success p-3"><i class="fas fa-user-md"></i></span>
            <div>
                <h6 class="mb-1 fw-bold">Manajemen Dokter</h6>
                <p class="text-muted small mb-0">Kelola data dokter dan spesialisasi tanpa ribet.</p>
            </div>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-ghost">Kelola Dokter</a>
    </div>
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-start gap-3 mb-3">
            <span class="badge badge-status-info p-3"><i class="fas fa-chart-line"></i></span>
            <div>
                <h6 class="mb-1 fw-bold">Laporan Kunjungan</h6>
                <p class="text-muted small mb-0">Analisis kunjungan pasien dengan data lengkap.</p>
            </div>
        </div>
        <a href="{{ route('admin.reports.visitation') }}" class="btn btn-secondary btn-ghost">Lihat Laporan</a>
    </div>
    <div class="admin-card p-4 h-100">
        <div class="d-flex align-items-start gap-3 mb-3">
            <span class="badge badge-status-warning p-3"><i class="fas fa-file-medical-alt"></i></span>
            <div>
                <h6 class="mb-1 fw-bold">History & Audit</h6>
                <p class="text-muted small mb-0">Akses riwayat reservasi dan catatan audit dengan mudah.</p>
            </div>
        </div>
        <a href="{{ route('admin.approvals.index') }}" class="btn btn-ghost">Lihat History</a>
    </div>
</div>

{{-- RINGKASAN PER DOKTER --}}
<div class="admin-card admin-card--flat mb-4">
    <h5 class="mb-0 fw-bold text-dark">
        <i class="fas fa-user-md text-primary me-2"></i>
        Ringkasan Reservasi Per Dokter
    </h5>
    <small class="text-muted">
        @if($doctorStats->count() > 0)
            Menampilkan {{ $doctorStats->count() }} dokter dengan total reservasi
        @else
            Belum ada data
        @endif
    </small>
</div>

<div class="row g-3 mb-4">
    @if($doctorStats->count() > 0)
        @foreach($doctorStats as $doctor)
            <div class="col-lg-6 col-xl-4">
                <div class="admin-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge badge-status-info p-3"><i class="fas fa-user-md"></i></span>
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $doctor['name'] }}</h6>
                            <p class="mb-1 small text-muted">{{ $doctor['specialization'] }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-status-info">{{ $doctor['total_appointments'] }}</span>
                        <small class="text-muted">reservasi</small>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="admin-card p-4 text-center">
                <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data reservasi dokter</p>
                <small class="text-muted d-block">Pasien dapat membuat reservasi melalui aplikasi</small>
            </div>
        </div>
    @endif
</div>
{{-- Daily Queue Table --}}
<div class="admin-table-wrapper mb-4">
    <div class="admin-card admin-card--section-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-1 fw-bold">Antrian Harian Hari Ini</h5>
                <p class="text-muted mb-0 small">Daftar pasien yang akan berkunjung hari ini</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.visitation') }}" class="btn btn-primary">
                    <i class="fas fa-chart-bar me-2"></i>Laporan Kunjungan
                </a>
                <button class="btn btn-ghost" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0 fw-semibold">Kode Booking</th>
                        <th class="border-0 fw-semibold">No. Antrian</th>
                        <th class="border-0 fw-semibold">Nama Pasien</th>
                        <th class="border-0 fw-semibold">Dokter</th>
                        <th class="border-0 fw-semibold">Waktu</th>
                        <!-- <th class="border-0 fw-semibold">Status Reservasi</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyQueues as $queue)
                        <tr>
                            <td>
                                <code class="bg-light px-2 py-1 rounded small">{{ $queue['booking_code'] }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-status-info px-3 py-2">{{ $queue['queue_number'] }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                        <div class="status-avatar status-avatar-primary me-3">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                    <span class="fw-semibold">{{ $queue['patient_name'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                        <div class="status-avatar status-avatar-success me-3">
                                            <i class="fas fa-user-md text-success"></i>
                                        </div>
                                    <span>{{ $queue['doctor_name'] }}</span>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-clock text-muted me-2"></i>
                                {{ \Carbon\Carbon::parse($queue['appointment_time'])->format('H:i') }}
                            </td>
                            {{--
                            <td>
                                @php
                                    $statusSource = $queue['status'] ?? ($queue['approval_status'] ?? 'pending');
                                    $statusClass = match($statusSource) {
                                        'pending' => 'status-pending',
                                        'approved' => 'status-approved',
                                        'done' => 'status-done',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-pending'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($statusSource) }}
                                </span>
                            </td>
                            --}}

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                    <p>Tidak ada antrian untuk hari ini</p>
                                    <small class="text-muted">Pasien dapat membuat reservasi melalui aplikasi</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
</div>
    </div>
</div>

{{-- Appointment Summary Card (Admin) --}}
@php
    $appointmentStatusCounts = [
        'approved' => $todayStats['approved'] ?? 0,
        'done' => $todayStats['done'] ?? 0,
        'cancelled' => $todayStats['cancelled'] ?? 0,
    ];
@endphp

<div class="mb-4">
    <x-admin.appointment-summary-card
        title="Daftar Reservasi"
        :appointmentsCount="$todayStats['total'] ?? 0"
        :statusCounts="$appointmentStatusCounts"
        :href="route('admin.approvals.index')"
    />
</div>

@endsection
