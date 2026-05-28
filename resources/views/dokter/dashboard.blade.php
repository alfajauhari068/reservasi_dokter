@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="container-fluid py-4 min-vh-100 clinical-page-bg" >
    <div class="container-xl px-0">
        <div class="row g-4 mb-4" >
            {{-- Backup hero header lama:
                <div class="col-lg-7">
                    <div class="ds-hero-left">
                        <div class="ds-label-badge">DASHBOARD DOKTER</div>
                        <h1 class="ds-hero-title">Halo, {{ auth()->user()->name }}</h1>
                        <p class="ds-hero-description">
                            Kelola reservasi pasien Anda dengan mudah dan pantau statistik harian secara real-time.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('dokter.reservasi.history') }}" class="ds-btn-primary">
                                Lihat Riwayat
                            </a>
                            <a href="{{ route('dokter.dashboard') }}" class="ds-btn-secondary">
                                Segarkan Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            --}}

            <div class="@if(isset($doctor)) col-lg-8 @else col-12 @endif">
                <div class="clinical-card p-4 h-100" style="background: linear-gradient(135deg, #1d4ed8, #3b82f6); color: #ffffff; minHeight: '230px';">
                    <div class="text-uppercase fw-semibold text-black mb-3 hero-label">DASHBOARD DOKTER</div>
                    <h1 class="h2 fw-bold mb-3 font-sans">Halo, {{ auth()->user()->name ?? 'Dokter' }}</h1>
                    <p class="text-white mb-4">Kelola reservasi pasien Anda dengan mudah dan pantau statistik harian secara real-time.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-light">
                            <i class="bi bi-journal-bookmark me-1"></i> Lihat Riwayat
                        </a>
                        <a href="{{ route('dokter.dashboard') }}" class="btn btn-outline-light border-white text-black">
                            <i class="bi bi-arrow-clockwise me-1"></i> Segarkan Dashboard
                        </a>
                    </div>
                </div>
            </div>

            @if(isset($doctor))
                <div class="col-lg-4">
                    <div class="clinical-card p-4 h-100">
                        @php $avatarPath = $doctor->avatar ?? null; @endphp
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="flex-shrink-0">
                                @if($avatarPath)
                                    <button type="button"
                                            class="btn btn-link p-0"
                                            style="border:none; background:transparent;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#doctorProfileModal"
                                            aria-label="Lihat profil dokter">
                                        <img src="{{ asset('storage/' . $doctor->avatar) }}" alt="Foto Profil Dokter" class="rounded-circle avatar-placeholder">
                                    </button>
                                @else
                                    <button type="button"
                                            class="btn btn-link p-0"
                                            style="border:none; background:transparent;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#doctorProfileModal"
                                            aria-label="Lihat profil dokter">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center avatar-placeholder">
                                            <i class="bi bi-person-fill fs-2"></i>
                                        </div>
                                    </button>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <h3 class="h5 fw-bold mb-1 font-sans">{{ auth()->user()->name ?? 'Dokter' }}</h3>
                                <span class="badge status-badge-active">Aktif</span>
                            </div>   
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted small">Spesialisasi</span>
                                <span class="fw-semibold">{{ optional($doctor->specialization)->name ?? 'Belum diatur' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-muted small">ID Dokter</span>
                                <span class="font-mono fw-bold">{{ $doctor->id ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="clinical-card p-4 text-center empty-state-alert">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle-fill display-4 text-danger"></i>
                        </div>
                        <h3 class="h5 fw-bold">Profil dokter tidak ditemukan di sistem.</h3>
                        <p class="text-danger mb-0">Pastikan data dokter sudah terhubung sebelum menggunakan dashboard ini.</p>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($doctor))
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="clinical-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2 metric-icon-bg-primary">
                                <i class="bi bi-file-earmark-plus-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="text-uppercase text-muted small">PERMINTAAN BARU</div>
                                <div class="h2 fw-bold font-mono mb-0 text-dark">{{ $pendingAppointments->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="clinical-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2 metric-icon-bg-success">
                                <i class="bi bi-clipboard2-check-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="text-uppercase text-muted small">RIWAYAT SELESAI</div>
                                <div class="h2 fw-bold font-mono mb-0 text-dark">{{ $completedCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="clinical-card p-4 h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2 metric-icon-bg-warning">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="text-uppercase text-muted small">TOTAL PASIEN HARI INI</div>
                                <div class="h2 fw-bold font-mono mb-0 text-dark">{{ $pendingAppointments->count() + $completedCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="clinical-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h2 class="h5 fw-semibold">Daftar Reservasi Pasien</h2>
                                <p class="text-muted mb-0">Daftar reservasi yang perlu ditindaklanjuti oleh dokter.</p>
                            </div>
                        </div>

                        @if($pendingAppointments->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                                <h3 class="h6 fw-bold">Belum ada reservasi baru.</h3>
                                <p class="text-muted mb-0">Seluruh reservasi aktif untuk hari ini telah selesai atau belum didaftarkan.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle border-0 mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-muted small">TANGGAL</th>
                                            <th class="text-uppercase text-muted small">PASIEN</th>
                                            <th class="text-uppercase text-muted small">NOMOR ANTRIAN</th>
                                            <th class="text-uppercase text-muted small">STATUS</th>
                                            <th class="text-uppercase text-muted small">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingAppointments as $appointment)
                                            <tr>
                                                <td class="font-sans text-sm">{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                                <td>
                                                    {{ $appointment->patient->user->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}
                                                </td>
                                                <td><span class="font-mono badge bg-secondary bg-opacity-10 text-secondary py-2 px-3 rounded-pill">{{ $appointment->queue_number ?? '-' }}</span></td>
                                                <td>
                                                    <span class="badge badge-soft-warning">{{ ucfirst($appointment->status) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-light btn-sm">
                                                        Periksa
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    @php $nextAppointment = $pendingAppointments->first(); @endphp
                    @if($nextAppointment)
                        <div class="clinical-card p-4 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <span class="text-uppercase text-muted small">Pasien Berikutnya</span>
                                <h3 class="h5 fw-bold mt-2">{{ optional($nextAppointment->patient->user)->name
                                    ?? $nextAppointment->patient->full_name
                                    ?? $nextAppointment->patient->identity_number }}</h3>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Tanggal</span>
                                    <span>{{ $nextAppointment->appointment_date->format('d-m-Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">Status</span>
                                    <span class="badge badge-soft-warning">{{ ucfirst($nextAppointment->status) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-2">
                                    <span class="text-muted">Nomor Antrian</span>
                                    <span class="font-mono fw-semibold">{{ $nextAppointment->queue_number ?? '-' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('dokter.reservasi.show', $nextAppointment) }}" class="btn btn-primary w-100">
                                Buka Detail
                            </a>
                        </div>
                    @else
                        <div class="clinical-card p-4 h-100 d-flex flex-column justify-content-between text-center">
                            <div>
                                <div class="mb-3">
                                    <i class="bi bi-person-lines-fill display-4 text-muted"></i>
                                </div>
                                <h3 class="h6 fw-bold">Tidak ada pasien berikutnya saat ini.</h3>
                                <p class="text-muted mb-4">Silakan periksa kembali setelah ada reservasi baru.</p>
                            </div>
                            <button type="button" class="btn btn-light w-100 disabled opacity-50" disabled>
                                Buka Detail
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
</div>
<div class="ps-dashboard-footnote py-3">
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 text-muted small">
        <span>Informasi reservasi dan riwayat Anda diperbarui secara real-time.</span>
        <span>
            <a href="#" class="ps-footer-link">Kebijakan Privasi</a>
            ·
            <a href="#" class="ps-footer-link">Syarat & Ketentuan</a>
        </span>
    </div>
</div>
<!-- Doctor Profile Modal -->
<div class="modal fade" id="doctorProfileModal" tabindex="-1" aria-labelledby="doctorProfileModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background: rgba(2,132,196,0.12);">
                <h5 class="modal-title font-sans fw-bold" id="doctorProfileModalLabel">
                    <i class="bi bi-person-badge me-2"></i> Profil Dokter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" style="background: rgba(2,132,196,0.08); border: 1px solid rgba(2,132,196,0.18);">
                            <div class="d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; margin: 0 auto;">
                                @if(isset($doctor) && ($doctor->avatar ?? null))
                                    <img src="{{ asset('storage/' . $doctor->avatar) }}" alt="Foto Profil Dokter" class="rounded-circle avatar-placeholder" style="width:90px;height:90px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center avatar-placeholder" style="width:90px;height:90px;">
                                        <i class="bi bi-person-fill fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center mt-3">
                                <div class="fw-bold">{{ auth()->user()->name ?? 'Dokter' }}</div>
                                <div class="text-muted small">ID: {{ $doctor->id ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="small text-muted">Spesialisasi</div>
                                <div class="fw-semibold">{{ optional($doctor->specialization ?? null)->name ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">ID Dokter</div>
                                <div class="fw-semibold">{{ $doctor->id ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Nama User</div>
                                <div class="fw-semibold">{{ auth()->user()->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
