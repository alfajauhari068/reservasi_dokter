@extends('layouts.app')

@section('title', 'Form Pemeriksaan Pasien')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Form Pemeriksaan Pasien</h1>
        <p class="text-muted">Isi hasil pemeriksaan untuk pasien ini.</p>
    </div>
    <a href="{{ route('dokter.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Data Reservasi</h5>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Nama Pasien:</strong> {{ optional($appointment->patient->user)->name ?? '-' }}</p>
                <p class="mb-2"><strong>Tanggal:</strong> {{ $appointment->appointment_date->format('d-m-Y') }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Jam:</strong> {{ substr($appointment->schedule->start_time, 0, 5) }} - {{ substr($appointment->schedule->end_time, 0, 5) }}</p>
                <p class="mb-2"><strong>Nomor Antrian:</strong> {{ $appointment->queue_number ?? '-' }}</p>
            </div>
        </div>
        <p class="mb-0"><strong>Keluhan:</strong> {{ $appointment->complaint ?? '-' }}</p>
    </div>
</div>

<form action="{{ route('dokter.reservasi.update', $appointment) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header">Hasil Pemeriksaan</div>
        <div class="card-body">
            <div class="mb-3">
                <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis', optional($appointment->medicalRecord)->diagnosis) }}</textarea>
                @error('diagnosis')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="prescription" class="form-label">Resep</label>
                <textarea class="form-control" id="prescription" name="prescription" rows="3">{{ old('prescription', optional($appointment->medicalRecord)->prescription) }}</textarea>
                @error('prescription')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="doctor_notes" class="form-label">Catatan Dokter</label>
                <textarea class="form-control" id="doctor_notes" name="doctor_notes" rows="3">{{ old('doctor_notes', optional($appointment->medicalRecord)->doctor_notes) }}</textarea>
                @error('doctor_notes')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pemeriksaan</button>
        </div>
    </div>
</form>
@endsection