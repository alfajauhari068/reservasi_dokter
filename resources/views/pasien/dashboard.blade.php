@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="glass-card p-4 mb-4 shadow-sm">
    <div class="row align-items-center gy-3">
        <div class="col-md-8">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Dashboard Pasien</span>
            <h1 class="h2 mb-2">Halo, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0">Lihat status reservasi Anda dan akses riwayat pemeriksaan dengan cepat.</p>
        </div>
        <div class="col-md-4 text-md-end">
            @if(! isset($patient) || ! $patient)
                <a href="{{ route('pasien.profile.edit') }}" class="btn btn-warning text-dark me-2 mb-2 mb-md-0">Lengkapi Profil</a>
            @endif
            <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary me-2 mb-2 mb-md-0">Buat Reservasi</a>
            <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-secondary mb-2 mb-md-0">Lihat Riwayat</a>
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
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Reservasi Aktif</p>
                    <h3 class="mb-0">{{ $activeCount ?? 0 }}</h3>
                </div>
                <span class="badge bg-primary text-white py-2 px-3">Aktif</span>
            </div>
            <p class="text-muted mb-0">Reservasi yang sedang menunggu pemeriksaan atau sedang berjalan.</p>
        </div>
    </div>
    <div class="col">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Riwayat Selesai</p>
                    <h3 class="mb-0">{{ $completedCount ?? 0 }}</h3>
                </div>
                <span class="badge bg-success text-white py-2 px-3">Selesai</span>
            </div>
            <p class="text-muted mb-0">Reservasi yang sudah selesai dan terekam dalam riwayat Anda.</p>
        </div>
    </div>
    <div class="col">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Total Reservasi</p>
                    <h3 class="mb-0">{{ $totalCount ?? 0 }}</h3>
                </div>
                <span class="badge bg-info text-dark py-2 px-3">Total</span>
            </div>
            <p class="text-muted mb-0">Jumlah semua reservasi yang telah Anda ajukan.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Reservasi Terbaru</h5>
                    <p class="text-muted mb-0">Lihat status reservasi Anda terbaru.</p>
                </div>
                <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-sm btn-outline-secondary">Semua Reservasi</a>
            </div>
            @if(empty($appointments) || $appointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada reservasi.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($appointments->take(5) as $appointment)
                        <div class="list-group-item px-0 py-3 border-0">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                    <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                </div>
                                <span class="badge rounded-pill bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning text-dark' : 'secondary') }} py-2 px-3">{{ ucfirst($appointment->status) }}</span>
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

    <div class="col-lg-6">
        <div class="glass-card p-4 h-100 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-1">Riwayat Pemeriksaan</h5>
                    <p class="text-muted mb-0">Catatan medis dan pemeriksaan terakhir yang selesai.</p>
                </div>
                <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-sm btn-outline-secondary">Semua Riwayat</a>
            </div>
            @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada riwayat pemeriksaan selesai.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($completedAppointments->take(5) as $appointment)
                        <div class="list-group-item px-0 py-3 border-0">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                    <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                </div>
                                <span class="badge rounded-pill bg-success py-2 px-3">Selesai</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</small>
                                <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Lihat Riwayat</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
