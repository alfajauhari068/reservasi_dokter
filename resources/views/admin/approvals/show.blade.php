@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detail Permohonan Reservasi</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Informasi Reservasi -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Reservasi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Kode Booking:</strong></p>
                            <p><span class="badge bg-primary fs-6">{{ $appointment->booking_code }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status Persetujuan:</strong></p>
                            <p>
                                <span class="badge bg-warning">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Persetujuan
                                </span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Pengajuan:</strong></p>
                            <p>{{ $appointment->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Periksa:</strong></p>
                            <p>{{ $appointment->appointment_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pasien -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Data Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong></p>
                            <p>{{ $appointment->patient->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong></p>
                            <p>{{ $appointment->patient->user->email }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>No. Telepon:</strong></p>
                            <p>{{ $appointment->patient->phone_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong></p>
                            <p>{{ $appointment->patient->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Dokter -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Data Dokter</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama Dokter:</strong></p>
                            <p>{{ $appointment->doctor->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Spesialisasi:</strong></p>
                            <p>{{ $appointment->doctor->specialization->name }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Jam Praktek:</strong></p>
                            <p>
                                {{ $appointment->schedule->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $appointment->schedule->start_time)->format('H:i') : '' }} -
                                {{ $appointment->schedule->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $appointment->schedule->end_time)->format('H:i') : '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Hari:</strong></p>
                            <p>{{ ucfirst($appointment->schedule->day_of_week) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keluhan Pasien -->
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
        </div>

        <!-- Sidebar Aksi -->
        <div class="col-md-4">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Setujui Reservasi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Klik tombol di bawah untuk menyetujui reservasi ini. Setelah disetujui, data akan muncul di dashboard dokter.</p>
                    <form action="{{ route('admin.approvals.approve', $appointment) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui reservasi ini?')">
                            <i class="bi bi-check-lg"></i> Setujui Reservasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Tolak Reservasi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Klik tombol di bawah untuk menolak reservasi ini dan berikan alasan penolakan.</p>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-lg"></i> Tolak Reservasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Reservasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.approvals.reject', $appointment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Reservasi:</strong> {{ $appointment->booking_code }}<br>
                        <strong>Pasien:</strong> {{ $appointment->patient->user->name }}<br>
                        <strong>Dokter:</strong> {{ $appointment->doctor->user->name }}
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="6" required placeholder="Jelaskan secara detail alasan penolakan reservasi ini untuk diberitahukan ke pasien..."></textarea>
                        @error('rejection_reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimal 10 karakter, maksimal 500 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Reservasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    p {
        margin-bottom: 0.25rem;
    }
</style>
@endsection
