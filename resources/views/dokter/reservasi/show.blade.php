@extends('layouts.app')

@section('title', 'Form Pemeriksaan Pasien')

@section('content')
<div class="glass-card p-4 mb-4 shadow-sm">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div>
            <span class="badge bg-primary bg-opacity-15 text-primary mb-2">Form Pemeriksaan</span>
            <h1 class="h3 mb-1">Pemeriksaan Pasien</h1>
            <p class="text-muted mb-0">Lengkapi diagnosis, resep, dan catatan dokter untuk reservasi ini.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dokter.reservasi.print-pdf', $appointment) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-print me-2"></i>Cetak PDF
            </a>
            <a href="{{ route('dokter.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1">Data Pasien</h5>
                    <p class="text-muted mb-0">Informasi dasar pasien yang terdaftar.</p>
                </div>
                <span class="badge bg-info text-dark">Reservasi</span>
            </div>
            <div class="mb-3">
                <p class="mb-2"><strong>Nama Pasien</strong></p>
                <p class="text-secondary">{{ optional($appointment->patient->user)->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}</p>
            </div>
            <div class="mb-3 row row-cols-1 row-cols-sm-2 g-3">
                <div>
                    <p class="text-muted small mb-1">Tanggal</p>
                    <p class="mb-0">{{ $appointment->appointment_date->format('d-m-Y') }}</p>
                </div>
                <div>
                    <p class="text-muted small mb-1">Nomor Antrian</p>
                    <p class="mb-0">{{ $appointment->queue_number ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p class="text-muted small mb-1">Keluhan</p>
                <p class="mb-0">{{ $appointment->complaint ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1">Jadwal Pemeriksaan</h5>
                    <p class="text-muted mb-0">Detail sesi konsultasi pasien.</p>
                </div>
                <span class="badge bg-warning text-dark">{{ strtoupper($appointment->status) }}</span>
            </div>
            <div class="mb-3 row row-cols-1 row-cols-sm-2 g-3">
                <div>
                    <p class="text-muted small mb-1">Waktu Mulai</p>
                    <p class="mb-0">{{ substr($appointment->schedule->start_time, 0, 5) }}</p>
                </div>
                <div>
                    <p class="text-muted small mb-1">Waktu Selesai</p>
                    <p class="mb-0">{{ substr($appointment->schedule->end_time, 0, 5) }}</p>
                </div>
            </div>
            <p class="text-muted small mb-1">Dokter</p>
            <p class="mb-0">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak tersedia' }}</p>
        </div>
    </div>
</div>

<form action="{{ route('dokter.reservasi.update', $appointment) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="glass-card p-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h5 class="mb-1">Hasil Pemeriksaan</h5>
                <p class="text-muted mb-0">Masukkan diagnosis, resep, dan catatan untuk pasien.</p>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" required>{{ old('diagnosis', optional($appointment->medicalRecord)->diagnosis) }}</textarea>
                @error('diagnosis')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label for="prescription" class="form-label">Resep</label>
                <textarea class="form-control" id="prescription" name="prescription" rows="4">{{ old('prescription', optional($appointment->medicalRecord)->prescription) }}</textarea>
                @error('prescription')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label for="doctor_notes" class="form-label">Catatan Dokter</label>
                <textarea class="form-control" id="doctor_notes" name="doctor_notes" rows="4">{{ old('doctor_notes', optional($appointment->medicalRecord)->doctor_notes) }}</textarea>
                @error('doctor_notes')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</form>
@endsection