@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
{{-- Hero Section --}}
<section class="mb-4" aria-label="Dashboard Pasien">
    <div class="patient-hero ds-card position-relative overflow-hidden p-4 p-lg-5">
        <div class="patient-hero__bg" aria-hidden="true"></div>

        <div class="row align-items-center gy-4 position-relative z-1">
            <div class="col-md-7">
                <span class="ds-hero-badge mb-2">Dashboard Pasien</span>
                <h1 class="patient-hero-title mb-3">Halo, {{ auth()->user()->name }}</h1>
                <p class="patient-hero-copy text-muted mb-4">Lihat status reservasi dan riwayat pemeriksaan Anda dalam satu tampilan yang rapi, penuh warna, dan ramah.</p>
                <div class="patient-action-buttons d-flex flex-wrap gap-2">
                    @if(! isset($patient) || ! $patient)
                        <a href="{{ route('pasien.profile.edit') }}" class="btn btn-outline-light">Lengkapi Profil</a>
                    @endif
                    <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary btn-lg">Buat Reservasi</a>
                    <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-light btn-lg">Lihat Riwayat</a>
                </div>
            </div>

            <div class="col-md-5">
                <div class="patient-sidebar ds-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-4 gap-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Profil Pasien</p>
                            <h5 class="patient-name mb-1">{{ auth()->user()->name }}</h5>
                            <p class="text-muted mb-0">ID Pasien: <strong>{{ optional($patient)->id ?? 'Belum terdaftar' }}</strong></p>
                        </div>
                        <div class="patient-avatar d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                    <div class="patient-sidebar__status p-3 rounded-3">
                        <p class="small text-muted mb-2">Akses Cepat</p>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <span class="small text-muted">Reservasi dan catatan medis</span>
                            <span class="badge badge-status-info">{{ $patient ? 'Lengkap' : 'Perlu Lengkapi' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(session('error'))
    <div class="patient-alert patient-alert--critical ds-card mb-3">
        {{ session('error') }}
    </div>
@endif

@if(! isset($patient) || ! $patient)
    <div class="patient-alert patient-alert--warning ds-card mb-3">
        Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.
    </div>
@endif

<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    <div class="col">
        <div class="patient-stat-card ds-feature-card h-100">
            <div class="admin-stat-card__header">
                <div>
                    <p class="admin-stat-card__kicker mb-1">Reservasi Aktif</p>
                    <div class="admin-stat-card__value">{{ $activeCount ?? 0 }}</div>
                    <p class="text-muted small mb-0">Menunggu pemeriksaan atau sedang berjalan.</p>
                </div>
                <div class="admin-stat-card__icon patient-stat-card__icon--primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="patient-stat-card ds-feature-card h-100">
            <div class="admin-stat-card__header">
                <div>
                    <p class="admin-stat-card__kicker mb-1">Riwayat Selesai</p>
                    <div class="admin-stat-card__value">{{ $completedCount ?? 0 }}</div>
                    <p class="text-muted small mb-0">Reservasi yang sudah selesai dan terekam.</p>
                </div>
                <div class="admin-stat-card__icon patient-stat-card__icon--success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="patient-stat-card ds-feature-card h-100">
            <div class="admin-stat-card__header">
                <div>
                    <p class="admin-stat-card__kicker mb-1">Total Reservasi</p>
                    <div class="admin-stat-card__value">{{ $totalCount ?? 0 }}</div>
                    <p class="text-muted small mb-0">Semua reservasi yang pernah Anda ajukan.</p>
                </div>
                <div class="admin-stat-card__icon patient-stat-card__icon--info">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="patient-appointments-list ds-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Reservasi Terbaru</h5>
                    <p class="text-muted mb-0">Lihat status reservasi terbaru Anda dengan cepat.</p>
                </div>
                <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-primary btn-sm">Semua Reservasi</a>
            </div>
            @if(empty($appointments) || $appointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada reservasi.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($appointments->take(5) as $appointment)
                        @php
                            $statusSource = $appointment->status;
                            $statusMap = [
                                'completed' => ['class' => 'badge-status-success', 'label' => 'Selesai', 'listClass' => 'patient-list-item--completed'],
                                'pending'   => ['class' => 'badge-status-warning', 'label' => 'Menunggu', 'listClass' => 'patient-list-item--pending'],
                                'cancelled' => ['class' => 'badge-status-critical', 'label' => 'Dibatalkan', 'listClass' => 'patient-list-item--cancelled'],
                            ];
                            $config = $statusMap[$statusSource] ?? ['class' => 'badge-status-info', 'label' => ucfirst($statusSource), 'listClass' => ''];
                        @endphp
                        <div class="list-group-item px-0 py-3 border-0 patient-list-item {{ $config['listClass'] }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                    <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                </div>
                                <span class="badge {{ $config['class'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</small>
                                <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Detail</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-4">
        <div class="patient-history-card ds-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Riwayat Pemeriksaan</h5>
                    <p class="text-muted small mb-0">Catatan medis terbaru Anda.</p>
                </div>
                <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
            </div>
            @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada riwayat pemeriksaan selesai.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($completedAppointments->take(5) as $appointment)
                        <div class="list-group-item px-0 py-3 border-0 patient-list-item patient-list-item--completed">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                    <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                </div>
                                <span class="badge badge-status-success">Selesai</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Antrian: {{ $appointment->queue_number ?? '-' }}</small>
                                <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Lihat</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
