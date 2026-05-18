@extends('layouts.admin')

@section('page-title', 'Laporan Kunjungan')
@section('page-subtitle', 'Pantau dan analisis data kunjungan pasien')

@section('content')
{{-- STATISTIK RINGKAS --}}
@if(isset($stats))
<div class="row g-3 mb-4">
    {{-- Total Kunjungan --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-primary text-center">
            <div class="admin-icon-pill admin-icon-pill--primary mb-3">
                <i class="fas fa-calendar-check"></i>
            </div>
            <p class="admin-stat-card__kicker mb-1">Total Kunjungan</p>
            <h4 class="mb-1">{{ $stats['total_visits'] }}</h4>
            <p class="text-muted small mb-0">{{ $stats['period']['start'] }} - {{ $stats['period']['end'] }}</p>
        </div>
    </div>

    {{-- Kunjungan Selesai --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-success text-center">
            <div class="admin-icon-pill admin-icon-pill--success mb-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <p class="admin-stat-card__kicker mb-1">Kunjungan Selesai</p>
            <h4 class="mb-1">{{ $stats['completed_visits'] }}</h4>
            <p class="text-muted small mb-0">{{ $stats['completion_rate'] }}% tingkat penyelesaian</p>
        </div>
    </div>

    {{-- Dalam Proses --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-warning text-center">
            <div class="admin-icon-pill admin-icon-pill--warning mb-3">
                <i class="fas fa-clock"></i>
            </div>
            <p class="admin-stat-card__kicker mb-1">Dalam Proses</p>
            <h4 class="mb-1">{{ $stats['pending_visits'] }}</h4>
            <p class="text-muted small mb-0">Menunggu / Dipanggil</p>
        </div>
    </div>

    {{-- Rata-rata Waktu Tunggu --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-info text-center">
            <div class="admin-icon-pill admin-icon-pill--info mb-3">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <p class="admin-stat-card__kicker mb-1">Rata-rata Tunggu</p>
            <h4 class="mb-1">{{ $stats['avg_wait_time'] }}</h4>
            <p class="text-muted small mb-0">menit per pasien</p>
        </div>
    </div>
</div>
@endif

{{-- FORM FILTER --}}
<div class="admin-card mb-4">
    <div class="admin-card__header d-flex align-items-center justify-content-between gap-3 p-4">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-filter text-primary me-2"></i>
            Filter Laporan
        </h5>
    </div>
    <div class="admin-card__body">
        <form method="GET" action="{{ route('admin.reports.visitation') }}" class="row g-3">
            {{-- Filter Dokter --}}
            <div class="col-md-4">
                <label for="doctor_id" class="admin-form-label">Dokter</label>
                <select name="doctor_id" id="doctor_id" class="admin-form-control">
                    <option value="">Semua Dokter</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ $doctorId == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->user->name }}
                            @if($doctor->specialization)
                                ({{ $doctor->specialization->name }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Mulai --}}
            <div class="col-md-2">
                <label for="start_date" class="admin-form-label">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       id="start_date"
                       class="admin-form-control"
                       value="{{ $startDate ?? '' }}">
            </div>

            {{-- Filter Tanggal Akhir --}}
            <div class="col-md-2">
                <label for="end_date" class="admin-form-label">Tanggal Akhir</label>
                <input type="date"
                       name="end_date"
                       id="end_date"
                       class="admin-form-control"
                       value="{{ $endDate ?? '' }}">
            </div>

            {{-- Filter Status --}}
            <div class="col-md-2">
                <label for="status" class="admin-form-label">Status</label>
                <select name="status" id="status" class="admin-form-control">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="col-md-2">
                <label class="admin-form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Preset Periode --}}
        <div class="mt-3 d-flex flex-wrap align-items-center gap-2">
            <p class="text-muted small mb-0 fw-semibold">Preset Periode:</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}"
                   class="btn btn-secondary btn-sm">Minggu Ini</a>
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="btn btn-secondary btn-sm">Bulan Ini</a>
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}"
                   class="btn btn-secondary btn-sm">Tahun Ini</a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL LAPORAN --}}
<div class="admin-card">
    <div class="admin-card__header d-flex justify-content-between align-items-center gap-3 p-4">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-table text-primary me-2"></i>
                Detail Kunjungan
            </h5>
            <p class="text-muted small mb-0">
                @if(isset($stats))
                    Menampilkan {{ $reports->count() }} kunjungan dari {{ $stats['period']['start'] }} sampai {{ $stats['period']['end'] }}
                @endif
            </p>
        </div>
        <div class="text-end">
            @if($reports->count() > 0)
                <form method="POST" action="{{ route('admin.reports.visitation.export') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="admin-card__body p-0">
        @if($reports->count() > 0)
            <div class="admin-table-wrapper">
                <div class="table-responsive">
                    <table class="table admin-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-0 fw-semibold text-center admin-table__cell--narrow">No</th>
                                <th class="border-0 fw-semibold">Tanggal</th>
                                <th class="border-0 fw-semibold">Kode Booking</th>
                                <th class="border-0 fw-semibold">Pasien</th>
                                <th class="border-0 fw-semibold">Dokter</th>
                                <th class="border-0 fw-semibold">Jam Kunjungan</th>
                                <th class="border-0 fw-semibold">No Antrian</th>
                                <th class="border-0 fw-semibold text-center">Status</th>
                                <th class="border-0 fw-semibold text-center">Waktu Tunggu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                                <tr>
                                    {{-- Nomor Urut --}}
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    {{-- Tanggal --}}
                                    <td>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($report->appointment_date)->format('d/m/Y') }}</span>
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($report->appointment_date)->format('l') }}</small>
                                    </td>

                                    {{-- Kode Booking --}}
                                    <td>
                                        <code class="admin-code-pill">{{ $report->booking_code }}</code>
                                    </td>

                                    {{-- Pasien --}}
                                    <td>
                                        <span class="fw-semibold">{{ $report->patient_name }}</span>
                                        @if($report->symptoms)
                                            <br><small class="text-muted"><i class="fas fa-notes-medical me-1"></i>{{ Str::limit($report->symptoms, 30) }}</small>
                                        @endif
                                    </td>

                                    {{-- Dokter --}}
                                    <td>
                                        <span class="fw-semibold">{{ $report->doctor_name }}</span>
                                        <br><small class="text-muted">{{ $report->specialization_name ?? 'Umum' }}</small>
                                    </td>

                                    {{-- Jam Kunjungan --}}
                                    <td>
                                        <span class="admin-icon-pill admin-icon-pill--neutral me-2"><i class="fas fa-clock"></i></span>
                                        {{ \Carbon\Carbon::parse($report->appointment_time)->format('H:i') }}
                                    </td>

                                    {{-- No Antrian --}}
                                    <td class="text-center">
                                        <span class="badge badge-status-warning">{{ $report->queue_number }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @php
                                            $statusConfig = [
                                                'waiting' => ['class' => 'badge-status-warning', 'icon' => 'fas fa-clock', 'text' => 'Pending'],
                                                'called' => ['class' => 'badge-status-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Called'],
                                                'served' => ['class' => 'badge-status-success', 'icon' => 'fas fa-check-circle', 'text' => 'Completed'],
                                                'skipped' => ['class' => 'badge-status-critical', 'icon' => 'fas fa-times-circle', 'text' => 'Skipped'],
                                            ];

                                            $displayStatus = $report->queue_status;

                                            if (!$displayStatus) {
                                                switch ($report->appointment_status) {
                                                    case 'completed':
                                                    case 'done':
                                                        $displayStatus = 'served';
                                                        break;
                                                    case 'cancelled':
                                                        $displayStatus = 'skipped';
                                                        break;
                                                    default:
                                                        $displayStatus = 'waiting';
                                                        break;
                                                }
                                            }

                                            $config = $statusConfig[$displayStatus] ?? $statusConfig['waiting'];
                                        @endphp
                                        <span class="badge {{ $config['class'] }} text-capitalize">
                                            <i class="{{ $config['icon'] }} me-1"></i>
                                            {{ $config['text'] }}
                                        </span>
                                    </td>

                                    {{-- Waktu Tunggu --}}
                                    <td class="text-center">
                                        @if($report->queue_status === 'served' && $report->called_at && $report->served_at)
                                            @php
                                                $calledTime = \Carbon\Carbon::parse($report->called_at);
                                                $servedTime = \Carbon\Carbon::parse($report->served_at);
                                                $waitMinutes = $calledTime->diffInMinutes($servedTime);
                                            @endphp
                                            <span class="badge badge-status-info">{{ $waitMinutes }} menit</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- Tidak ada data --}}
            <div class="text-center py-5">
                <div class="admin-icon-pill admin-icon-pill--neutral mb-3">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h5 class="text-muted">Tidak ada data kunjungan</h5>
                <p class="text-muted small">Ubah filter untuk melihat data kunjungan lainnya</p>
            </div>
        @endif
    </div>
</div>

{{-- STATISTIK PER DOKTER --}}
@if(isset($stats) && $stats['doctor_stats']->count() > 0)
<div class="admin-card admin-card--section-header mt-4">
    <div class="admin-card__header d-flex align-items-center justify-content-between gap-3 p-4">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-user-md text-primary me-2"></i>
                Statistik Per Dokter
            </h5>
            <p class="text-muted small mb-0">Periode: {{ $stats['period']['start'] }} - {{ $stats['period']['end'] }}</p>
        </div>
    </div>
    <div class="admin-card__body">
        <div class="row g-3">
            @foreach($stats['doctor_stats'] as $doctorStat)
                <div class="col-lg-6 col-xl-4">
                    <div class="admin-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="admin-avatar admin-avatar-primary">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $doctorStat['name'] }}</h6>
                                <p class="text-muted small mb-0">{{ $doctorStat['specialization'] ?? 'Umum' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge badge-status-info">{{ $doctorStat['total'] }}</span>
                            <small class="text-muted">Total</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-status-success">{{ $doctorStat['completed'] }}</span>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Set default tanggal jika kosong
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if (!startDateInput.value) {
        startDateInput.value = '{{ now()->startOfMonth()->format('Y-m-d') }}';
    }
    if (!endDateInput.value) {
        endDateInput.value = '{{ now()->endOfMonth()->format('Y-m-d') }}';
    }
});
</script>
@endpush