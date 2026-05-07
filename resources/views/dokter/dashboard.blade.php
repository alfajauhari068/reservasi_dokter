@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Dashboard Dokter</h1>
        <p class="text-muted">Selamat datang, {{ auth()->user()->name }}. Kelola reservasi pasien dan lihat status pelayanan Anda.</p>
    </div>
    <div class="text-end">
        <a href="{{ route('dokter.schedule.index') }}" class="btn btn-outline-info me-2">Kelola Schedule</a>
        <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-outline-secondary">Lihat Riwayat</a>
    </div>
</div>

@if(! $doctor)
    <div class="alert alert-warning">Profil dokter tidak ditemukan. Pastikan akun dokter sudah terhubung dengan data dokter.</div>
@else
    <div class="row gy-4">
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Permintaan Baru</h5>
                    <p class="card-text fs-2 mb-1">{{ $pendingAppointments->count() }}</p>
                    <p class="text-muted">Reservasi pasien yang belum selesai.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Selesai</h5>
                    <p class="card-text fs-2 mb-1">{{ $completedCount }}</p>
                    <p class="text-muted">Reservasi pasien yang sudah selesai ditangani.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Reservasi Pasien</div>
        <div class="card-body p-0">
            @if($pendingAppointments->isEmpty())
                <div class="p-4 text-center text-muted">Belum ada reservasi baru.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Nomor Antrian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                    <td>{{ optional($appointment->patient->user)->name ?? '-' }}</td>
                                    <td>{{ $appointment->queue_number ?? '-' }}</td>
                                    <td>{{ ucfirst($appointment->status) }}</td>
                                    <td><a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Periksa</a></td>
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
