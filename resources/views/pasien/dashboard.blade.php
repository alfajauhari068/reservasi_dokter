@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Dashboard Pasien</h1>
        <p class="text-muted">Selamat datang, {{ auth()->user()->name }}. Kelola reservasi dan lihat riwayat pemeriksaan Anda di sini.</p>
    </div>
    <div class="text-end">
        <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary me-2">Buat Reservasi</a>
        <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-secondary">Lihat Riwayat</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(! isset($patient) || ! $patient)
    <div class="alert alert-warning">Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.</div>
@endif

<div class="row gy-4">
    <div class="col-md-4">
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title">Reservasi Aktif</h5>
                <p class="card-text fs-2 mb-1">{{ $activeCount ?? 0 }}</p>
                <p class="text-muted">Reservasi yang sedang menunggu atau dalam proses.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success h-100">
            <div class="card-body">
                <h5 class="card-title">Riwayat Selesai</h5>
                <p class="card-text fs-2 mb-1">{{ $completedCount ?? 0 }}</p>
                <p class="text-muted">Reservasi yang sudah ditangani dokter dan memiliki riwayat pemeriksaan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info h-100">
            <div class="card-body">
                <h5 class="card-title">Total Reservasi</h5>
                <p class="card-text fs-2 mb-1">{{ $totalCount ?? 0 }}</p>
                <p class="text-muted">Menampilkan reservasi terbaru Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 gy-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Reservasi Terbaru</div>
            <div class="card-body p-0">
                @if(empty($appointments) || $appointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada reservasi.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($appointments->take(5) as $appointment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                        <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($appointment->status) }}</span>
                                </div>
                                <p class="mb-1 text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</p>
                                <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Riwayat Pemeriksaan Terbaru</div>
            <div class="card-body p-0">
                @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada riwayat pemeriksaan selesai.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($completedAppointments->take(5) as $appointment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</h6>
                                        <small class="text-muted">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</small>
                                    </div>
                                    <span class="badge bg-success">Selesai</span>
                                </div>
                                <p class="mb-1 text-muted">Nomor antrian: {{ $appointment->queue_number ?? '-' }}</p>
                                <a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Lihat Riwayat</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
