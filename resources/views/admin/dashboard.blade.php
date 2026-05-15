@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan statistik reservasi hari ini')

@section('content')
{{-- HERO SUMMARY --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="glass-card p-4 d-flex flex-column flex-lg-row align-items-start justify-content-between gap-4">
            <div>
                <span class="badge bg-info bg-opacity-10 text-info mb-3">Control Center</span>
                <h1 class="h3 mb-2 fw-bold">Selamat datang, Admin</h1>
                <p class="text-muted mb-3">Kelola reservasi, antrian, dokter, dan laporan dengan tampilan lebih bersih dan intuitif.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.approvals.index') }}" class="btn btn-primary btn-sm">Lihat Riwayat</a>
                    <a href="{{ route('admin.queues.index') }}" class="btn btn-outline-secondary btn-sm">Kelola Antrian</a>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-info btn-sm">Kelola Dokter</a>
                </div>
            </div>
            <div class="text-end">
                <p class="text-muted mb-1 small">Ringkasan hari ini</p>
                <div class="d-flex flex-column gap-2 align-items-end">
                    <div class="badge bg-primary text-white px-4 py-2 rounded-pill">Total: <strong>{{ $todayStats['total'] }}</strong></div>
                    <div class="badge bg-success text-white px-4 py-2 rounded-pill">Selesai: <strong>{{ $todayStats['done'] }}</strong></div>
                    <div class="badge bg-info text-dark px-4 py-2 rounded-pill">Approved: <strong>{{ $todayStats['approved'] }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Antrian Real-Time</p>
                    <h4 class="fw-bold mb-0">{{ $dailyQueues->count() }}</h4>
                </div>
                <i class="fas fa-users fa-2x text-primary"></i>
            </div>
            <p class="text-muted small mb-0">Lacak status pasien hari ini secara langsung.</p>
            <a href="{{ route('admin.queues.index') }}" class="btn btn-sm btn-primary mt-4">Kelola Antrian</a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Dokter Aktif</p>
                    <h4 class="fw-bold mb-0">{{ $doctorStats->count() }}</h4>
                </div>
                <i class="fas fa-user-md fa-2x text-success"></i>
            </div>
            <p class="text-muted small mb-0">Kelola data dokter dan spesialisasi secara cepat.</p>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-success mt-4">Kelola Dokter</a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Laporan Mingguan</p>
                    <h4 class="fw-bold mb-0">{{ $todayServedCount ?? 0 }}</h4>
                </div>
                <i class="fas fa-chart-line fa-2x text-info"></i>
            </div>
            <p class="text-muted small mb-0">Tampilkan ringkasan kunjungan pasien dalam periode.</p>
            <a href="{{ route('admin.reports.visitation') }}" class="btn btn-sm btn-outline-info mt-4">Lihat Laporan</a>
        </div>
    </div>
</div>

{{-- KARTU STATISTIK UTAMA --}}
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Total Hari Ini</p>
                    <h4 class="fw-bold mb-0">{{ $todayStats['total'] }}</h4>
                </div>
                <i class="fas fa-calendar-check fa-2x text-primary"></i>
            </div>
            <p class="text-muted small mb-0">Reservasi yang tercatat hari ini.</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Approved</p>
                    <h4 class="fw-bold mb-0">{{ $todayStats['approved'] }}</h4>
                </div>
                <i class="fas fa-check-circle fa-2x text-info"></i>
            </div>
            <p class="text-muted small mb-0">Reservasi yang disetujui hari ini.</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Selesai</p>
                    <h4 class="fw-bold mb-0">{{ $todayStats['done'] }}</h4>
                </div>
                <i class="fas fa-check-double fa-2x text-success"></i>
            </div>
            <p class="text-muted small mb-0">Reservasi yang sudah selesai ditangani.</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-secondary small mb-1">Dibatalkan</p>
                    <h4 class="fw-bold mb-0">{{ $todayStats['cancelled'] ?? 0 }}</h4>
                </div>
                <i class="fas fa-times-circle fa-2x text-danger"></i>
            </div>
            <p class="text-muted small mb-0">Reservasi yang dibatalkan hari ini.</p>
        </div>
    </div>
</div>

{{-- MANAGEMENT EXPERIENCE --}}
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start gap-3 mb-3">
                <span class="badge bg-primary bg-opacity-15 text-primary p-3 rounded-3"><i class="fas fa-clock"></i></span>
                <div>
                    <h6 class="mb-1 fw-bold">Antrian Real-Time</h6>
                    <p class="text-muted small mb-0">Pantau posisi antrian dan alur pasien dengan cepat.</p>
                </div>
            </div>
            <a href="{{ route('admin.queues.index') }}" class="btn btn-sm btn-outline-primary">Buka Antrian</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start gap-3 mb-3">
                <span class="badge bg-success bg-opacity-15 text-success p-3 rounded-3"><i class="fas fa-user-md"></i></span>
                <div>
                    <h6 class="mb-1 fw-bold">Manajemen Dokter</h6>
                    <p class="text-muted small mb-0">Kelola data dokter dan spesialisasi tanpa ribet.</p>
                </div>
            </div>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-success">Kelola Dokter</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start gap-3 mb-3">
                <span class="badge bg-info bg-opacity-15 text-info p-3 rounded-3"><i class="fas fa-chart-line"></i></span>
                <div>
                    <h6 class="mb-1 fw-bold">Laporan Kunjungan</h6>
                    <p class="text-muted small mb-0">Analisis kunjungan pasien dengan data lengkap.</p>
                </div>
            </div>
            <a href="{{ route('admin.reports.visitation') }}" class="btn btn-sm btn-outline-info">Lihat Laporan</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex align-items-start gap-3 mb-3">
                <span class="badge bg-warning bg-opacity-15 text-warning p-3 rounded-3"><i class="fas fa-file-medical-alt"></i></span>
                <div>
                    <h6 class="mb-1 fw-bold">History & Audit</h6>
                    <p class="text-muted small mb-0">Akses riwayat reservasi dan catatan audit dengan mudah.</p>
                </div>
            </div>
            <a href="{{ route('admin.approvals.index') }}" class="btn btn-sm btn-outline-warning text-dark">Lihat History</a>
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
                        <!-- <th class="border-0 fw-semibold">Status Reservasi</th> -->
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
                            {{--
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
                            --}}

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
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
                <div class="display-4 text-success mb-2">{{ $todayServedCount ?? 0 }}</div>
                <h6 class="text-muted mb-0">Sudah Dilayani</h6>
            </div>
        </div>
    </div>
</div>
@endif
@endsection