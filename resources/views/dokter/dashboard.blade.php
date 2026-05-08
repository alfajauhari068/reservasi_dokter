@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="page-heading">
    <div class="row align-items-center gy-3">
        <div class="col-md-8">
            <h1>Dashboard Dokter</h1>
            <p class="mb-0 text-muted">Selamat datang, {{ auth()->user()->name }}. Kelola reservasi pasien dan pantau pelayanan Anda.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('dokter.schedule.index') }}" class="btn btn-gradient me-2 mb-2 mb-md-0">Kelola Jadwal</a>
            <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-outline-secondary">Lihat Riwayat</a>
        </div>
    </div>
</div>

@if(! $doctor)
    <div class="alert alert-warning">Profil dokter tidak ditemukan. Pastikan akun dokter sudah terhubung dengan data dokter.</div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <div class="col">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary mb-2">Permintaan Baru</h6>
                        <p class="mb-0 text-muted">Reservasi yang menunggu tindak lanjut.</p>
                    </div>
                    <span class="badge badge-pill bg-warning text-dark">Baru</span>
                </div>
                <strong class="d-block display-6">{{ $pendingAppointments->count() }}</strong>
            </div>
        </div>
        <div class="col">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary mb-2">Riwayat Selesai</h6>
                        <p class="mb-0 text-muted">Reservasi yang sudah ditutup.</p>
                    </div>
                    <span class="badge badge-pill bg-success">Selesai</span>
                </div>
                <strong class="d-block display-6">{{ $completedCount }}</strong>
            </div>
        </div>
        <div class="col">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary mb-2">Profil Dokter</h6>
                        <p class="mb-1"><strong>{{ optional($doctor->user)->name ?? auth()->user()->name }}</strong></p>
                        <p class="text-muted mb-1">{{ optional($doctor->specialization)->name ?? 'Spesialisasi tidak tersedia' }}</p>
                    </div>
                    <span class="badge badge-pill bg-info text-dark">ID {{ $doctor->id }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0">
            <h5 class="mb-0">Reservasi Pasien</h5>
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
                                    <td>{{ optional($appointment->patient->user)->name ?? '-' }}</td>
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
