@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan statistik reservasi hari ini')

@section('content')
{{-- KARTU STATISTIK UTAMA --}}
<div class="row g-3 mb-4">
    {{-- Total Reservasi Hari Ini --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1 text-primary fw-bold">{{ $todayStats['total'] }}</h4>
                <p class="mb-0 text-muted small">Total Reservasi Hari Ini</p>
            </div>
        </div>
    </div>

    {{-- Reservasi Pending --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h4 class="mb-1 text-warning fw-bold">{{ $todayStats['pending'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi Pending</p>
            </div>
        </div>
    </div>

    {{-- Reservasi Approved --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-2x text-info"></i>
                </div>
                <h4 class="mb-1 text-info fw-bold">{{ $todayStats['approved'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi Approved</p>
            </div>
        </div>
    </div>

    {{-- Reservasi Selesai --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-double fa-2x text-success"></i>
                </div>
                <h4 class="mb-1 text-success fw-bold">{{ $todayStats['done'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi Selesai</p>
            </div>
        </div>
    </div>

    {{-- Reservasi Dibatalkan --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                </div>
                <h4 class="mb-1 text-danger fw-bold">{{ $todayStats['cancelled'] }}</h4>
                <p class="mb-0 text-muted small">Reservasi Dibatalkan</p>
            </div>
        </div>
    </div>
</div>

{{-- AKSES CEPAT KE FITUR --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-3x text-warning"></i>
                </div>
                <h5 class="mb-2">Persetujuan Reservasi</h5>
                <p class="text-muted small mb-3">
                    <strong class="text-warning">{{ $pendingApprovalsCount }}</strong> permohonan menunggu persetujuan
                </p>
                <a href="{{ route('admin.approvals.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Persetujuan
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x text-info"></i>
                </div>
                <h5 class="mb-2">Manajemen Antrian</h5>
                <p class="text-muted small mb-3">Pantau dan kelola antrian pasien hari ini</p>
                <a href="{{ route('admin.queues.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Antrian
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-chart-bar fa-3x text-success"></i>
                </div>
                <h5 class="mb-2">Laporan Kunjungan</h5>
                <p class="text-muted small mb-3">Lihat laporan kunjungan berdasarkan periode</p>
                <a href="{{ route('admin.reports.visitation') }}" class="btn btn-success">
                    <i class="fas fa-arrow-right me-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-md fa-3x text-primary"></i>
                </div>
                <h5 class="mb-2">Kelola Dokter</h5>
                <p class="text-muted small mb-3">Tambah, edit, dan hapus data dokter</p>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Kelola Dokter
                </a>
            </div>
        </div>
    </div>
</div>

{{-- RINGKASAN PER DOKTER --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-user-md text-primary me-2"></i>
            Ringkasan Reservasi Per Dokter
        </h5>
        <small class="text-muted">
            @if($doctorStats->count() > 0)
                Menampilkan {{ $doctorStats->count() }} dokter dengan total reservasi
            @else
                Belum ada data
            @endif
        </small>
    </div>
    <div class="card-body">
        @if($doctorStats->count() > 0)
            <div class="row g-3">
                @foreach($doctorStats as $doctor)
                    <div class="col-lg-6 col-xl-4">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-md text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $doctor['name'] }}</h6>
                                <p class="mb-1 small text-muted">{{ $doctor['specialization'] }}</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2">{{ $doctor['total_appointments'] }}</span>
                                    <small class="text-muted">reservasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada data reservasi dokter</p>
                <small class="text-muted d-block">Pasien dapat membuat reservasi melalui aplikasi</small>
            </div>
        @endif
    </div>
</div>

{{-- Daily Queue Table --}}
<div class="card table-modern">
    <div class="card-header bg-white border-bottom-0 py-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-1 fw-bold">Antrian Harian Hari Ini</h5>
                <p class="text-muted mb-0 small">Daftar pasien yang akan berkunjung hari ini</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.visitation') }}" class="btn btn-success btn-admin">
                    <i class="fas fa-chart-bar me-2"></i>Laporan Kunjungan
                </a>
                <button class="btn btn-outline-secondary btn-admin" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0 fw-semibold">Kode Booking</th>
                        <th class="border-0 fw-semibold">No. Antrian</th>
                        <th class="border-0 fw-semibold">Nama Pasien</th>
                        <th class="border-0 fw-semibold">Dokter</th>
                        <th class="border-0 fw-semibold">Waktu</th>
                        <th class="border-0 fw-semibold">Status Reservasi</th>
                        <th class="border-0 fw-semibold">Status Antrian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyQueues as $queue)
                        <tr>
                            <td>
                                <code class="bg-light px-2 py-1 rounded small">{{ $queue['booking_code'] }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark fs-6 px-3 py-2">{{ $queue['queue_number'] }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $queue['patient_name'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-md text-success"></i>
                                    </div>
                                    <span>{{ $queue['doctor_name'] }}</span>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-clock text-muted me-2"></i>
                                {{ \Carbon\Carbon::parse($queue['appointment_time'])->format('H:i') }}
                            </td>
                            <td>
                                @php
                                    $statusSource = $queue['status'] ?? ($queue['approval_status'] ?? 'pending');
                                    $statusClass = match($statusSource) {
                                        'pending' => 'status-pending',
                                        'approved' => 'status-approved',
                                        'done' => 'status-done',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-pending'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($statusSource) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $queueStatusClass = match($queue['queue_status']) {
                                        'waiting' => 'queue-status-waiting',
                                        'called' => 'queue-status-called',
                                        'served' => 'queue-status-served',
                                        'skipped' => 'queue-status-skipped',
                                        default => 'queue-status-waiting'
                                    };
                                @endphp
                                <span class="status-badge {{ $queueStatusClass }}">
                                    {{ $queue['queue_status'] == 'N/A' ? '-' : ucfirst($queue['queue_status']) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                    <p>Tidak ada antrian untuk hari ini</p>
                                    <small class="text-muted">Pasien dapat membuat reservasi melalui aplikasi</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Quick Stats Summary --}}
@if($dailyQueues->count() > 0)
<div class="row g-4 mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="display-4 text-primary mb-2">{{ $dailyQueues->count() }}</div>
                <h6 class="text-muted mb-0">Total Antrian Hari Ini</h6>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="display-4 text-success mb-2">{{ $dailyQueues->where('status', 'done')->count() }}</div>
                <h6 class="text-muted mb-0">Sudah Dilayani</h6>
            </div>
        </div>
    </div>
</div>
@endif
@endsection