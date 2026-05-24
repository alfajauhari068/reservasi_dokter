@extends('layouts.admin')

@section('page-title', 'Form Filter Laporan')
@section('page-subtitle', 'Contoh form filter dengan dropdown dokter dan date range')

@section('content')
<div class="container-fluid">
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-filter fa-4x text-primary mb-3"></i>
                    <h2 class="fw-bold text-primary">Form Filter Laporan Kunjungan</h2>
                    <p class="text-muted mb-0">Contoh implementasi dropdown dokter dan input date range</p>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM FILTER --}}
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sliders-h me-2"></i>
                        Filter Parameter
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ request()->url() }}" class="row g-4">

                        {{-- DOKTER --}}
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label fw-bold">
                                <i class="fas fa-user-md text-primary me-2"></i>
                                Pilih Dokter
                            </label>
                            <select name="doctor_id" id="doctor_id" class="form-select form-select-lg">
                                <option value="">-- Semua Dokter --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                            {{ ($currentFilters['doctor_id'] ?? null) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }}
                                        @if($doctor->specialization)
                                            - {{ $doctor->specialization->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Pilih dokter spesifik atau biarkan kosong untuk semua dokter
                            </div>
                        </div>

                        {{-- TANGGAL MULAI --}}
                        <div class="col-md-3">
                            <label for="start_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                Tanggal Mulai
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   class="form-control form-control-lg"
                                   value="{{ $currentFilters['start_date'] ?? '' }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   required>
                            <div class="form-text">
                                Tanggal awal periode
                            </div>
                        </div>

                        {{-- TANGGAL SAMPAI --}}
                        <div class="col-md-3">
                            <label for="end_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-minus text-danger me-2"></i>
                                Tanggal Sampai
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   class="form-control form-control-lg"
                                   value="{{ $currentFilters['end_date'] ?? '' }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   required>
                            <div class="form-text">
                                Tanggal akhir periode
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-search me-2"></i>
                                    Terapkan Filter
                                </button>

                                @if($currentFilters['has_filters'] ?? false)
                                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-undo me-2"></i>
                                        Reset Semua
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MENAMPILKAN FILTER YANG SUDAH DIPILIH --}}
    @if($currentFilters['has_filters'] ?? false)
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card border-success shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Filter Yang Dipilih
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- DOKTER YANG DIPILIH --}}
                        @if($currentFilters['selected_doctor'] ?? null)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-md text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $currentFilters['selected_doctor']['name'] }}</h6>
                                        <small class="text-muted">
                                            {{ $currentFilters['selected_doctor']['specialization'] ?? 'Dokter Umum' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- PERIODE TANGGAL --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-calendar-alt text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Periode Laporan</h6>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($currentFilters['start_date'])->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($currentFilters['end_date'])->format('d M Y') }}
                                    </small>
                                    <br>
                                    <small class="text-success fw-semibold">
                                        {{ \Carbon\Carbon::parse($currentFilters['start_date'])->diffInDays(\Carbon\Carbon::parse($currentFilters['end_date'])) + 1 }} hari
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- PRESET PERIODE --}}
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-magic text-info me-2"></i>
                        Preset Periode Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Klik salah satu preset untuk filter cepat:</p>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <a href="{{ request()->url() }}?start_date={{ now()->startOfWeek()->format('Y-m-d') }}&end_date={{ now()->endOfWeek()->format('Y-m-d') }}"
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-week me-2"></i>
                                <div class="fw-bold">Minggu Ini</div>
                                <small>{{ now()->startOfWeek()->format('d/m') }} - {{ now()->endOfWeek()->format('d/m') }}</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ request()->url() }}?start_date={{ now()->startOfMonth()->format('Y-m-d') }}&end_date={{ now()->endOfMonth()->format('Y-m-d') }}"
                               class="btn btn-outline-success w-100">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <div class="fw-bold">Bulan Ini</div>
                                <small>{{ now()->startOfMonth()->format('M Y') }}</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ request()->url() }}?start_date={{ now()->startOfYear()->format('Y-m-d') }}&end_date={{ now()->endOfYear()->format('Y-m-d') }}"
                               class="btn btn-outline-warning w-100">
                                <i class="fas fa-calendar me-2"></i>
                                <div class="fw-bold">Tahun Ini</div>
                                <small>{{ now()->year }}</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ request()->url() }}?start_date={{ now()->subDays(30)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}"
                               class="btn btn-outline-info w-100">
                                <i class="fas fa-history me-2"></i>
                                <div class="fw-bold">30 Hari</div>
                                <small>Terakhir</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INFO PENGGUNAAN --}}
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Cara Penggunaan Form Filter
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">📋 Dropdown Dokter</h6>
                            <ul class="small mb-3">
                                <li>Data dari tabel <code>doctors</code></li>
                                <li>Menampilkan nama + spesialisasi</li>
                                <li>Opsi "Semua Dokter" untuk filter global</li>
                                <li>Diurutkan berdasarkan nama</li>
                            </ul>

                            <h6 class="text-success">📅 Date Range</h6>
                            <ul class="small mb-0">
                                <li>Format YYYY-MM-DD</li>
                                <li>Maksimal tanggal hari ini</li>
                                <li>Validasi required</li>
                                <li>Auto-calculate jumlah hari</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">🔄 Filter State</h6>
                            <ul class="small mb-3">
                                <li>Persistent di URL parameter</li>
                                <li>Visual feedback filter aktif</li>
                                <li>Reset button untuk clear all</li>
                                <li>Preset buttons untuk quick filter</li>
                            </ul>

                            <h6 class="text-danger">⚡ Performance</h6>
                            <ul class="small mb-0">
                                <li>Eager loading untuk doctors</li>
                                <li>Query optimized dengan JOIN</li>
                                <li>Limit results untuk performa</li>
                                <li>Cache-friendly URLs</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <style>
.form-select-lg, .form-control-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
}

.btn-outline-primary:hover, .btn-outline-success:hover,
.btn-outline-warning:hover, .btn-outline-info:hover {
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Scoped to admin shell to avoid global .card collisions */
.admin-shell .card {
    transition: all 0.3s ease;
}

.admin-shell .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi date range
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    function validateDateRange() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDate && endDate && startDate > endDate) {
            endDateInput.setCustomValidity('Tanggal akhir harus setelah tanggal mulai');
        } else {
            endDateInput.setCustomValidity('');
        }
    }

    startDateInput.addEventListener('change', validateDateRange);
    endDateInput.addEventListener('change', validateDateRange);

    // Set default values jika kosong
    if (!startDateInput.value) {
        startDateInput.value = '{{ now()->startOfMonth()->format('Y-m-d') }}';
    }
    if (!endDateInput.value) {
        endDateInput.value = '{{ now()->endOfMonth()->format('Y-m-d') }}';
    }

    // Auto-submit preset links (optional enhancement)
    document.querySelectorAll('.btn-outline-primary, .btn-outline-success, .btn-outline-warning, .btn-outline-info').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        });
    });
});
</script>
@endpush