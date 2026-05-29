@extends('layouts.doctor')

@section('title', 'Riwayat Pemeriksaan Dokter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Riwayat Pemeriksaan</h1>
        <p class="text-muted">Daftar semua pemeriksaan yang telah Anda selesaikan.</p>
    </div>
    <a href="{{ route('dokter.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(! empty($errorMessage))
    <div class="alert alert-danger">{{ $errorMessage }}</div>
@endif

@if($completedAppointments->isEmpty())
    <div class="alert alert-info">Belum ada riwayat pemeriksaan yang selesai.</div>
@else
    <!-- Form Pencarian -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dokter.reservasi.history') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Pasien</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nama pasien...">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Diagnosis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedAppointments as $appointment)
                    <tr>
                        <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                        <td>{{ optional($appointment->patient->user)->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}</td>
                        <td>{{ \Illuminate\Support\Str::limit(optional($appointment->medicalRecord)->diagnosis, 50) ?? '-' }}</td>
                        <td><a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection