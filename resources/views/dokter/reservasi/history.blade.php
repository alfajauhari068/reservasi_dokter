@extends('layouts.app')

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
                        <td>{{ optional($appointment->patient->user)->name ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit(optional($appointment->medicalRecord)->diagnosis, 50) ?? '-' }}</td>
                        <td><a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection