@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="page-heading mb-4">
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="glass-card p-4 h-100">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div>
                        <span class="badge bg-info bg-opacity-15 text-info mb-3">Dashboard Dokter</span>
                        <h1 class="h2 mb-2">Selamat datang, {{ auth()->user()->name }}</h1>
                        <p class="text-muted mb-3">Kelola reservasi pasien, tinjau jadwal, dan selesaikan pemeriksaan dengan cepat.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-primary">Lihat Riwayat</a>
                            <a href="{{ route('dokter.dashboard') }}" class="btn btn-secondary">Segarkan Dashboard</a>
                        </div>
                    </div>
                    <div class="text-md-end text-white-75">
                        <p class="mb-1 text-uppercase text-secondary small">Status Akun</p>
                        <h5 class="mb-1">{{ optional($doctor->specialization)->name ?? 'Spesialisasi belum lengkap' }}</h5>
                        <p class="mb-0">ID Dokter: <strong>{{ optional($doctor)->id ?? '-' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                @if($doctor && $doctor->photo)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Foto Profil Dokter" class="rounded-circle border border-3 border-primary" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                @endif
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px;">
                        <i class="fas fa-user-md text-primary fs-4"></i>
                    </div>
                    <div>
                        <p class="text-secondary text-uppercase small mb-1">Profil Dokter</p>
                        <h5 class="mb-0">{{ optional($doctor->user)->name ?? auth()->user()->name }}</h5>
                        <small class="text-muted">{{ optional($doctor->specialization)->name ?? 'Spesialisasi tidak tersedia' }}</small>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3">
                            <p class="text-muted small mb-1">Reservasi Baru</p>
                            <h4 class="mb-0">{{ $pendingAppointments->count() }}</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3">
                            <p class="text-muted small mb-1">Sudah Selesai</p>
                            <h4 class="mb-0">{{ $completedCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(! $doctor)
    <div class="alert alert-warning">Profil dokter tidak ditemukan. Pastikan akun dokter sudah terhubung dengan data dokter.</div>
@else
    <div class="row row-cols-1 row-cols-lg-3 g-4 mb-4">
        <div class="col">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-uppercase text-secondary small mb-1">Permintaan Baru</p>
                        <h3 class="mb-0">{{ $pendingAppointments->count() }}</h3>
                    </div>
                    <span class="badge bg-warning text-dark">Baru</span>
                </div>
                <p class="text-muted mb-0">Reservasi yang menunggu tindak lanjut dokter.</p>
            </div>
        </div>
        <div class="col">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-uppercase text-secondary small mb-1">Riwayat Selesai</p>
                        <h3 class="mb-0">{{ $completedCount }}</h3>
                    </div>
                    <span class="badge bg-success">Selesai</span>
                </div>
                <p class="text-muted mb-0">Reservasi yang sudah selesai dan tercatat di riwayat.</p>
            </div>
        </div>
        <div class="col">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-uppercase text-secondary small mb-1">Total Pasien Hari Ini</p>
                        <h3 class="mb-0">{{ $pendingAppointments->count() + $completedCount }}</h3>
                    </div>
                    <span class="badge bg-info text-dark">Hari Ini</span>
                </div>
                <p class="text-muted mb-0">Jumlah total reservasi yang terkait dengan dokter hari ini.</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Reservasi Pasien</h5>
                <p class="mb-0 text-muted small">Daftar reservasi yang perlu ditindaklanjuti oleh dokter.</p>
            </div>
            <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-secondary btn-sm">Lihat Semua Riwayat</a>
        </div>
        <div class="card-body p-0">
            @if($pendingAppointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada reservasi baru.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Nomor Antrian</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                    <td>{{ optional($appointment->patient->user)->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}</td>
                                    <td>{{ $appointment->queue_number ?? '-' }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-end"><a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Periksa</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endif
@endsection
