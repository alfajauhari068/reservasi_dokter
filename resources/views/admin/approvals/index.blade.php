@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manajemen Persetujuan Reservasi</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Pending -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="card-title text-warning">{{ $pendingAppointments->total() }}</h3>
                    <p class="card-text">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Reservasi Pending -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Reservasi Menunggu Persetujuan</h5>
        </div>
        <div class="card-body">
            @if($pendingAppointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal Periksa</th>
                                <th>Keluhan</th>
                                <th>Waktu Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingAppointments as $appointment)
                                <tr>
                                    <td>
                                        <strong>{{ $appointment->booking_code }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $appointment->patient->user->name }}</div>
                                        <small class="text-muted">{{ $appointment->patient->user->email }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $appointment->doctor->user->name }}</div>
                                        <small class="text-muted">{{ $appointment->doctor->specialization->name }}</small>
                                    </td>
                                    <td>
                                        {{ $appointment->appointment_date->format('d M Y') }}<br>
                                        <small class="text-muted">
                                            {{ $appointment->schedule->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $appointment->schedule->start_time)->format('H:i') : '' }} -
                                            {{ $appointment->schedule->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $appointment->schedule->end_time)->format('H:i') : '' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ Str::limit($appointment->complaint ?? 'Tidak ada keluhan', 30) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $appointment->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.approvals.show', $appointment) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.approvals.approve', $appointment) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Setujui reservasi ini?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" title="Tolak" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $appointment->id }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Reject -->
                                <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Reservasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.approvals.reject', $appointment) }}" method="POST" onsubmit="return true;">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>Reservasi:</strong> {{ $appointment->booking_code }}</p>
                                                    <p><strong>Pasien:</strong> {{ $appointment->patient->user->name }}</p>
                                                    <p><strong>Dokter:</strong> {{ $appointment->doctor->user->name }}</p>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label for="rejection_reason_{{ $appointment->id }}" class="form-label">Alasan Penolakan</label>
                                                        <textarea name="rejection_reason" id="rejection_reason_{{ $appointment->id }}" class="form-control @error('rejection_reason') is-invalid @enderror" rows="4" required placeholder="Jelaskan alasan penolakan reservasi ini..."></textarea>
                                                        @error('rejection_reason')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingAppointments->links() }}
                </div>
            @else
                <div class="alert alert-success text-center" role="alert">
                    <i class="bi bi-check-circle"></i> Tidak ada reservasi yang menunggu persetujuan. Semua permintaan sudah diproses!
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .badge {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
    }
</style>
@endsection
