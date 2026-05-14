@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="page-heading">
    <div class="row align-items-center gy-3">
        <div class="col-md-8">
            <h1>Dashboard Pasien</h1>
            <p class="mb-0 text-muted">Selamat datang, {{ auth()->user()->name }}. Kelola reservasi dan lihat riwayat pemeriksaan Anda di sini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @if(! isset($patient) || ! $patient)
                <a href="{{ route('pasien.profile.edit') }}" class="btn btn-warning text-dark me-2 mb-2 mb-md-0">Lengkapi Profil</a>
            @endif
            <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-gradient me-2 mb-2 mb-md-0">Buat Reservasi</a>
            <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-secondary">Lihat Riwayat</a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(! isset($patient) || ! $patient)
    <div class="alert alert-warning">Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.</div>
@endif

<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    <div class="col">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-uppercase text-secondary mb-2">Reservasi Aktif</h6>
                    <p class="mb-0 text-muted">Sedang menunggu atau dalam proses</p>
                </div>
                <span class="badge badge-pill bg-primary">Aktif</span>
            </div>
            <strong class="d-block display-6">{{ $activeCount ?? 0 }}</strong>
        </div>
    </div>
    <div class="col">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-uppercase text-secondary mb-2">Riwayat Selesai</h6>
                    <p class="mb-0 text-muted">Reservasi telah selesai</p>
                </div>
                <span class="badge badge-pill bg-success">Selesai</span>
            </div>
            <strong class="d-block display-6">{{ $completedCount ?? 0 }}</strong>
        </div>
    </div>
    <div class="col">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-uppercase text-secondary mb-2">Total Reservasi</h6>
                    <p class="mb-0 text-muted">Semua reservasi Anda</p>
                </div>
                <span class="badge badge-pill bg-info text-dark">Total</span>
            </div>
            <strong class="d-block display-6">{{ $totalCount ?? 0 }}</strong>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0">
                <h5 class="mb-0">Reservasi Terbaru</h5>
            </div>
            <div class="card-body p-0">
                @if(empty($appointments) || $appointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada reservasi.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($appointments->take(5) as $appointment)
                            <div class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                        <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                    </div>
                                    <span class="badge rounded-pill bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning text-dark' : 'secondary') }} py-2 px-3">{{ ucfirst($appointment->status) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</small>
                                    <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0">
                <h5 class="mb-0">Riwayat Pemeriksaan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada riwayat pemeriksaan selesai.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($completedAppointments->take(5) as $appointment)
                            <div class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                        <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                    </div>
                                    <span class="badge rounded-pill bg-success py-2 px-3">Selesai</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</small>
                                    <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Lihat Riwayat</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
