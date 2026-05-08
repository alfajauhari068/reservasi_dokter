{{--
    CONTOH FORM FILTER UNTUK LAPORAN KUNJUNGAN
    File ini berisi contoh lengkap form filter dengan:
    - Dropdown dokter dari database
    - Input date range (tanggal dari - sampai)
    - Cara menampilkan filter yang sudah dipilih
    - Reset filter functionality
--}}

{{-- FORM FILTER UTAMA --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-filter text-primary me-2"></i>
            Filter Laporan Kunjungan
        </h5>
        <small class="text-muted">Sesuaikan filter untuk melihat data yang diinginkan</small>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ request()->url() }}" class="row g-3" id="filterForm">

            {{-- FILTER DOKTER --}}
            <div class="col-md-4">
                <label for="doctor_id" class="form-label fw-semibold">
                    <i class="fas fa-user-md text-muted me-1"></i>Dokter
                </label>
                <select name="doctor_id" id="doctor_id" class="form-select">
                    <option value="">Semua Dokter</option>
                    @if(isset($doctors) && $doctors->count() > 0)
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                    {{ (request('doctor_id') == $doctor->id) ? 'selected' : '' }}>
                                {{ $doctor->user->name }}
                                @if($doctor->specialization)
                                    ({{ $doctor->specialization->name }})
                                @endif
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>Tidak ada data dokter</option>
                    @endif
                </select>
            </div>

            {{-- FILTER TANGGAL MULAI --}}
            <div class="col-md-2">
                <label for="start_date" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt text-muted me-1"></i>Tanggal Dari
                </label>
                <input type="date"
                       name="start_date"
                       id="start_date"
                       class="form-control"
                       value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}">
            </div>

            {{-- FILTER TANGGAL SAMPAI --}}
            <div class="col-md-2">
                <label for="end_date" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt text-muted me-1"></i>Tanggal Sampai
                </label>
                <input type="date"
                       name="end_date"
                       id="end_date"
                       class="form-control"
                       value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}">
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold invisible">Aksi</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>

            {{-- TOMBOL RESET --}}
            <div class="col-md-2">
                <label class="form-label fw-semibold invisible">Reset</label>
                <div class="d-grid gap-2">
                    @if(request()->hasAny(['doctor_id', 'start_date', 'end_date']))
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Reset Filter
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-undo me-2"></i>Reset Filter
                        </button>
                    @endif
                </div>
            </div>
        </form>

        {{-- PRESET PERIODE CEPAT --}}
        <div class="mt-3 pt-3 border-top">
            <small class="text-muted fw-semibold mb-2 d-block">
                <i class="fas fa-clock text-info me-1"></i>Preset Periode:
            </small>
            <div class="btn-group btn-group-sm flex-wrap" role="group">
                <a href="{{ request()->url() }}?start_date={{ now()->startOfWeek()->format('Y-m-d') }}&end_date={{ now()->endOfWeek()->format('Y-m-d') }}"
                   class="btn btn-outline-info btn-sm {{ $this->isPresetActive('week') ? 'active' : '' }}">
                    <i class="fas fa-calendar-week me-1"></i>Minggu Ini
                </a>
                <a href="{{ request()->url() }}?start_date={{ now()->startOfMonth()->format('Y-m-d') }}&end_date={{ now()->endOfMonth()->format('Y-m-d') }}"
                   class="btn btn-outline-info btn-sm {{ $this->isPresetActive('month') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-1"></i>Bulan Ini
                </a>
                <a href="{{ request()->url() }}?start_date={{ now()->startOfYear()->format('Y-m-d') }}&end_date={{ now()->endOfYear()->format('Y-m-d') }}"
                   class="btn btn-outline-info btn-sm {{ $this->isPresetActive('year') ? 'active' : '' }}">
                    <i class="fas fa-calendar me-1"></i>Tahun Ini
                </a>
                <a href="{{ request()->url() }}?start_date={{ now()->subDays(30)->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}"
                   class="btn btn-outline-info btn-sm {{ $this->isPresetActive('30days') ? 'active' : '' }}">
                    <i class="fas fa-history me-1"></i>30 Hari Terakhir
                </a>
            </div>
        </div>
    </div>
</div>

{{-- MENAMPILKAN FILTER YANG SUDAH DIPILIH --}}
@if(request()->hasAny(['doctor_id', 'start_date', 'end_date']))
<div class="alert alert-info border-0 shadow-sm">
    <div class="d-flex align-items-start">
        <div class="flex-shrink-0 me-3">
            <i class="fas fa-info-circle fa-2x text-info"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2 fw-bold">Filter Aktif</h6>
            <div class="row g-2">
                {{-- FILTER DOKTER --}}
                @if(request('doctor_id'))
                    @php
                        $selectedDoctor = $doctors->find(request('doctor_id'));
                    @endphp
                    @if($selectedDoctor)
                        <div class="col-auto">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-user-md me-1"></i>
                                Dokter: {{ $selectedDoctor->user->name }}
                                @if($selectedDoctor->specialization)
                                    ({{ $selectedDoctor->specialization->name }})
                                @endif
                            </span>
                        </div>
                    @endif
                @endif

                {{-- FILTER PERIODE --}}
                @if(request('start_date') && request('end_date'))
                    <div class="col-auto">
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                        </span>
                    </div>
                @elseif(request('start_date'))
                    <div class="col-auto">
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                        </span>
                    </div>
                @elseif(request('end_date'))
                    <div class="col-auto">
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-calendar-minus me-1"></i>
                            Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- JUMLAH HARI DALAM PERIODE --}}
            @if(request('start_date') && request('end_date'))
                @php
                    $daysDiff = \Carbon\Carbon::parse(request('start_date'))->diffInDays(\Carbon\Carbon::parse(request('end_date'))) + 1;
                @endphp
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-clock me-1"></i>
                    Periode filter: {{ $daysDiff }} hari
                </small>
            @endif
        </div>
    </div>
</div>
@endif

{{-- INFO TAMBAHAN --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="alert alert-light border">
            <h6 class="alert-heading mb-2">
                <i class="fas fa-lightbulb text-warning me-2"></i>Tips Penggunaan
            </h6>
            <ul class="mb-0 small">
                <li>Pilih dokter untuk melihat laporan spesifik</li>
                <li>Gunakan preset periode untuk filter cepat</li>
                <li>Klik "Reset Filter" untuk melihat semua data</li>
                <li>Tanggal maksimal adalah hari ini</li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-light border">
            <h6 class="alert-heading mb-2">
                <i class="fas fa-chart-bar text-success me-2"></i>Format Data
            </h6>
            <ul class="mb-0 small">
                <li>Dokter: Nama lengkap + spesialisasi</li>
                <li>Tanggal: Format YYYY-MM-DD</li>
                <li>Periode: Otomatis dihitung jumlah hari</li>
                <li>Filter: Persistent di URL parameter</li>
            </ul>
        </div>
    </div>
</div>