@extends('layouts.admin')

@section('page-title', 'Laporan Kunjungan')
@section('page-subtitle', 'Pantau dan analisis data kunjungan pasien')

@section('content')
{{-- STATISTIK RINGKAS --}}
@if(isset($stats))
<div class="row g-3 mb-4">
    {{-- Total Kunjungan --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1 text-primary fw-bold">{{ $stats['total_visits'] }}</h4>
                <p class="mb-0 text-muted small">Total Kunjungan</p>
                <small class="text-muted">{{ $stats['period']['start'] }} - {{ $stats['period']['end'] }}</small>
            </div>
        </div>
    </div>

    {{-- Kunjungan Selesai --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <h4 class="mb-1 text-success fw-bold">{{ $stats['completed_visits'] }}</h4>
                <p class="mb-0 text-muted small">Kunjungan Selesai</p>
                <small class="text-success">{{ $stats['completion_rate'] }}% tingkat penyelesaian</small>
            </div>
        </div>
    </div>

    {{-- Dalam Proses --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h4 class="mb-1 text-warning fw-bold">{{ $stats['pending_visits'] }}</h4>
                <p class="mb-0 text-muted small">Dalam Proses</p>
                <small class="text-muted">Menunggu/Dipanggil</small>
            </div>
        </div>
    </div>

    {{-- Rata-rata Waktu Tunggu --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-hourglass-half fa-2x text-info"></i>
                </div>
                <h4 class="mb-1 text-info fw-bold">{{ $stats['avg_wait_time'] }}</h4>
                <p class="mb-0 text-muted small">Rata-rata Tunggu</p>
                <small class="text-muted">menit per pasien</small>
            </div>
        </div>
    </div>
</div>
@endif

{{-- FORM FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-filter text-primary me-2"></i>
            Filter Laporan
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.visitation') }}" class="row g-3">
            {{-- Filter Dokter --}}
            <div class="col-md-4">
                <label for="doctor_id" class="form-label fw-semibold">Dokter</label>
                <select name="doctor_id" id="doctor_id" class="form-select">
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
                <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       id="start_date"
                       class="form-control"
                       value="{{ $startDate ?? '' }}">
            </div>

            {{-- Filter Tanggal Akhir --}}
            <div class="col-md-2">
                <label for="end_date" class="form-label fw-semibold">Tanggal Akhir</label>
                <input type="date"
                       name="end_date"
                       id="end_date"
                       class="form-control"
                       value="{{ $endDate ?? '' }}">
            </div>

            {{-- Filter Status --}}
            <div class="col-md-2">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select name="status" id="status" class="form-select">
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
                <label class="form-label fw-semibold">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Preset Periode --}}
        <div class="mt-3">
            <small class="text-muted fw-semibold">Preset Periode:</small>
            <div class="btn-group btn-group-sm ms-2" role="group">
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}"
                   class="btn btn-outline-secondary btn-sm">Minggu Ini</a>
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                   class="btn btn-outline-secondary btn-sm">Bulan Ini</a>
                <a href="{{ route('admin.reports.visitation', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}"
                   class="btn btn-outline-secondary btn-sm">Tahun Ini</a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL LAPORAN --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-table text-primary me-2"></i>
                Detail Kunjungan
            </h5>
            <small class="text-muted">
                @if(isset($stats))
                    Menampilkan {{ $reports->count() }} kunjungan dari {{ $stats['period']['start'] }} sampai {{ $stats['period']['end'] }}
                @endif
            </small>
        </div>
        <div class="text-end">
            @if($reports->count() > 0)
                <form method="POST" action="{{ route('admin.reports.visitation.export') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        @if($reports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold text-center" style="width: 80px;">No</th>
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
                                    <code class="bg-light px-2 py-1 rounded small">{{ $report->booking_code }}</code>
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
                                    <i class="fas fa-clock text-muted me-2"></i>
                                    {{ \Carbon\Carbon::parse($report->appointment_time)->format('H:i') }}
                                </td>

                                {{-- No Antrian --}}
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $report->queue_number }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="text-center">
                                    @php
                                        $statusConfig = [
                                            'waiting' => ['class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock', 'text' => 'Pending'],
                                            'called' => ['class' => 'bg-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Called'],
                                            'served' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text' => 'Completed'],
                                            'skipped' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' => 'Skipped'],
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
                                    <span class="badge {{ $config['class'] }}">
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
                                        <span class="badge bg-info">{{ $waitMinutes }} menit</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Tidak ada data --}}
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-chart-bar fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">Tidak ada data kunjungan</h5>
                <p class="text-muted small">Ubah filter untuk melihat data kunjungan lainnya</p>
            </div>
        @endif
    </div>
</div>

{{-- STATISTIK PER DOKTER --}}
@if(isset($stats) && $stats['doctor_stats']->count() > 0)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-user-md text-primary me-2"></i>
            Statistik Per Dokter
        </h5>
        <small class="text-muted">Periode: {{ $stats['period']['start'] }} - {{ $stats['period']['end'] }}</small>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($stats['doctor_stats'] as $doctorStat)
                <div class="col-lg-6 col-xl-4">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user-md text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $doctorStat['name'] }}</h6>
                                    <small class="text-muted">{{ $doctorStat['specialization'] ?? 'Umum' }}</small>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="mb-0 text-primary">{{ $doctorStat['total'] }}</h5>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0 text-success">{{ $doctorStat['completed'] }}</h5>
                                    <small class="text-muted">Selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
}

.card {
    transition: box-shadow 0.15s ease-in-out;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-submit form ketika preset periode diklik
document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        window.location.href = url;
    });
});

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