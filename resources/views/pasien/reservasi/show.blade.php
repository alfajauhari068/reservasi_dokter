@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Detail Reservasi</h1>
        <p class="text-muted">Informasi lengkap tentang reservasi dan hasil pemeriksaan Anda.</p>
    </div>
    <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-secondary">Kembali ke Riwayat</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Kode Booking: {{ $appointment->booking_code }}</h5>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Nama Dokter:</strong> {{ optional($appointment->doctor->user)->name ?? '-' }}</p>
                <p class="mb-2"><strong>Spesialisasi:</strong> {{ optional($appointment->doctor->specialization)->name ?? 'Umum' }}</p>
                <p class="mb-2"><strong>Tanggal:</strong> {{ $appointment->appointment_date->format('d-m-Y') }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Jam:</strong> {{ substr($appointment->schedule->start_time, 0, 5) }} - {{ substr($appointment->schedule->end_time, 0, 5) }}</p>
                <p class="mb-2"><strong>Nomor Antrian:</strong> {{ $appointment->queue_number ?? '-' }}</p>
                <p class="mb-2"><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
            </div>
        </div>
        <p class="mb-0"><strong>Keluhan:</strong> {{ $appointment->complaint ?? '-' }}</p>
    </div>
</div>

@if($appointment->medicalRecord)
    <div class="card">
        <div class="card-header">Riwayat Pemeriksaan Dokter</div>
        <div class="card-body">
            <p><strong>Diagnosis:</strong><br>{{ $appointment->medicalRecord->diagnosis ?? '-' }}</p>
            <p><strong>Resep:</strong><br>{{ $appointment->medicalRecord->prescription ?? '-' }}</p>
            <p><strong>Catatan Dokter:</strong><br>{{ $appointment->medicalRecord->doctor_notes ?? '-' }}</p>
            <p class="text-muted">Diperbarui pada {{ $appointment->medicalRecord->updated_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>
@else
    <div class="alert alert-info">
        @if($appointment->status === 'completed')
            Riwayat pemeriksaan belum diisi oleh dokter. Silakan cek kembali nanti.
        @else
            Riwayat pemeriksaan akan tersedia setelah dokter menyelesaikan reservasi.
        @endif
    </div>
@endif
@endsection
