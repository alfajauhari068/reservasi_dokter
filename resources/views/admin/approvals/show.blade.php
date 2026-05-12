@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detail Riwayat Reservasi</h1>
            <p class="text-muted">Lihat detail reservasi serta hasil pemeriksaan, lalu cetak PDF jika diperlukan.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.approvals.print', $appointment) }}" class="btn btn-primary" target="_blank">
            <i class="bi bi-printer"></i> Cetak PDF
        </a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Reservasi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kode Booking:</strong></p>
                            <p class="fw-bold">{{ $appointment->booking_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            @php
                                $status = $appointment->status;
                                $badge = 'secondary';
                                if ($status === 'approved') $badge = 'info';
                                elseif ($status === 'in_progress') $badge = 'warning';
                                elseif ($status === 'completed') $badge = 'success';
                                elseif ($status === 'cancelled') $badge = 'danger';
                            @endphp
                            <span class="badge bg-{{ $badge }} text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal Pengajuan:</strong></p>
                            <p>{{ $appointment->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal Periksa:</strong></p>
                            <p>{{ $appointment->appointment_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nomor Antrian:</strong></p>
                            <p>{{ $appointment->queue_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Approval Status:</strong></p>
                            <p class="text-capitalize">{{ str_replace('_', ' ', $appointment->approval_status) ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Data Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama:</strong></p>
                            <p>{{ $appointment->patient->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong></p>
                            <p>{{ $appointment->patient->user->email }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>No. Telepon:</strong></p>
                            <p>{{ $appointment->patient->phone_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Alamat:</strong></p>
                            <p>{{ $appointment->patient->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Data Dokter</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama Dokter:</strong></p>
                            <p>{{ $appointment->doctor->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Spesialisasi:</strong></p>
                            <p>{{ optional($appointment->doctor->specialization)->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Jam Praktek:</strong></p>
                            <p>{{ optional($appointment->schedule)->start_time ? substr($appointment->schedule->start_time, 0, 5) : '-' }} - {{ optional($appointment->schedule)->end_time ? substr($appointment->schedule->end_time, 0, 5) : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Hari:</strong></p>
                            <p>{{ optional($appointment->schedule)->day_of_week ? ucfirst($appointment->schedule->day_of_week) : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Keluhan / Keterangan Pasien</h5>
                </div>
                <div class="card-body">
                    @if($appointment->complaint)
                        <p>{{ $appointment->complaint }}</p>
                    @else
                        <p class="text-muted"><em>Tidak ada keluhan yang dicatat</em></p>
                    @endif
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Riwayat Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    @if($appointment->medicalRecord)
                        <p><strong>Diagnosis:</strong><br>{{ $appointment->medicalRecord->diagnosis ?? '-' }}</p>
                        <p><strong>Resep:</strong><br>{{ $appointment->medicalRecord->prescription ?? '-' }}</p>
                        <p><strong>Catatan Dokter:</strong><br>{{ $appointment->medicalRecord->doctor_notes ?? '-' }}</p>
                        <p class="text-muted">Diperbarui pada {{ $appointment->medicalRecord->updated_at->format('d M Y H:i') }}</p>
                    @else
                        <p class="text-muted mb-0">Riwayat pemeriksaan belum tersedia.</p>
                        @if($appointment->status === 'completed')
                            <p class="text-muted">Data pemeriksaan belum diisi oleh dokter.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Booking Code:</strong> {{ $appointment->booking_code }}</p>
                    <p class="mb-2"><strong>Status Reservasi:</strong> {{ ucwords(str_replace('_', ' ', $appointment->status)) }}</p>
                    <p class="mb-2"><strong>Status Approval:</strong> {{ ucwords(str_replace('_', ' ', $appointment->approval_status)) }}</p>
                    <p class="mb-2"><strong>Tanggal Periksa:</strong> {{ $appointment->appointment_date->format('d M Y') }}</p>
                    <p class="mb-2"><strong>Pasien:</strong> {{ $appointment->patient->user->name }}</p>
                    <p class="mb-0"><strong>Dokter:</strong> {{ $appointment->doctor->user->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
