@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<section class="mb-4">
    <div class="doctor-hero ds-card position-relative overflow-hidden p-4 p-lg-5">
        <div class="doctor-hero__bg" aria-hidden="true"></div>

        <div class="row align-items-center gy-4 position-relative z-1">
            <div class="col-lg-7">
                <span class="ds-hero-badge mb-2 text-primary-600">Dashboard Dokter</span>
                <h1 class="doctor-hero-heading mb-3">Halo, {{ auth()->user()->name }}</h1>
                <p class="doctor-hero-copy text-muted mb-4">Kelola reservasi pasien, tinjau jadwal, dan selesaikan pemeriksaan dengan cepat.</p>
                <div class="doctor-action-buttons d-flex flex-wrap gap-2">
                    <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-primary btn-lg">Lihat Riwayat</a>
                    <a href="{{ route('dokter.dashboard') }}" class="btn btn-ghost btn-lg">Segarkan Dashboard</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="doctor-sidebar ds-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Status Akun</p>
                            <h5 class="doctor-specialization mb-1">{{ optional($doctor->specialization)->name ?? 'Spesialisasi belum lengkap' }}</h5>
                            <p class="text-muted mb-0">ID Dokter: <strong>{{ optional($doctor)->id ?? '-' }}</strong></p>
                        </div>
                        <div class="doctor-avatar d-flex align-items-center justify-content-center">
                            @if(optional($doctor)->photo)
                                <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Foto Dokter" class="doctor-avatar__img rounded-circle" />
                            @else
                                <span class="doctor-avatar__icon"><i class="fas fa-user-md"></i></span>
                            @endif
                        </div>
                    </div>

                    <div class="doctor-sidebar__status p-3 rounded-3 mt-3">
                        <p class="small text-muted mb-2">Panel Dokter</p>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <span class="small text-muted">Klinik profesional</span>
                            <span class="badge badge-status-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(! $doctor)
    <div class="doctor-alert doctor-alert--warning ds-card p-4 mb-4 d-flex align-items-center gap-3">
        <i class="fas fa-exclamation-triangle doctor-alert__icon"></i>
        <div>Profil dokter tidak ditemukan. Pastikan akun dokter sudah terhubung dengan data dokter.</div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <div class="col">
            <div class="doctor-summary-card ds-feature-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Permintaan Baru</p>
                        <h3 class="doctor-summary-card__value mb-0">{{ $pendingAppointments->count() }}</h3>
                    </div>
                    <div class="doctor-summary-card__icon doctor-summary-card__icon--warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <p class="text-muted small mb-0">Reservasi yang menunggu tindak lanjut dokter.</p>
            </div>
        </div>

        <div class="col">
            <div class="doctor-summary-card ds-feature-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Riwayat Selesai</p>
                        <h3 class="doctor-summary-card__value mb-0">{{ $completedCount }}</h3>
                    </div>
                    <div class="doctor-summary-card__icon doctor-summary-card__icon--success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <p class="text-muted small mb-0">Reservasi yang sudah selesai dan tercatat di riwayat.</p>
            </div>
        </div>

        <div class="col">
            <div class="doctor-summary-card ds-feature-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase text-muted small mb-1">Total Pasien Hari Ini</p>
                        <h3 class="doctor-summary-card__value mb-0">{{ $pendingAppointments->count() + $completedCount }}</h3>
                    </div>
                    <div class="doctor-summary-card__icon doctor-summary-card__icon--info">
                        <i class="fas fa-user-injured"></i>
                    </div>
                </div>
                <p class="text-muted small mb-0">Jumlah total reservasi yang terkait dengan dokter hari ini.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="doctor-appointments-list ds-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">Reservasi Pasien</h5>
                        <p class="text-muted small mb-0">Daftar reservasi yang perlu ditindaklanjuti oleh dokter.</p>
                    </div>
                    <a href="{{ route('dokter.reservasi.history') }}" class="btn btn-primary btn-sm">Lihat Semua Riwayat</a>
                </div>

                @if($pendingAppointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada reservasi baru.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <thead class="doctor-table-head">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Nomor Antrian</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                        <td>{{ optional($appointment->patient->user)->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}</td>
                                        <td>{{ $appointment->queue_number ?? '-' }}</td>
                                        <td><span class="badge-status-warning">{{ ucfirst($appointment->status) }}</span></td>
                                        <td class="text-end"><a href="{{ route('dokter.reservasi.show', $appointment) }}" class="btn btn-sm btn-primary">Periksa</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="doctor-patient-detail-card ds-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">Pasien Berikutnya</h5>
                        <p class="text-muted small mb-0">Detail antrian berikutnya untuk hari ini.</p>
                    </div>
                    <span class="badge badge-status-info">{{ $pendingAppointments->count() }} Antrian</span>
                </div>

                @php $nextAppointment = $pendingAppointments->first(); @endphp
                @if($nextAppointment)
                    <div class="doctor-patient-detail-card__body mb-4">
                        <p class="text-uppercase text-muted small mb-2">Nama Pasien</p>
                        <h6 class="mb-3">{{ optional($nextAppointment->patient->user)->name ?? $nextAppointment->patient->full_name ?? '-' }}</h6>
                        <p class="text-uppercase text-muted small mb-1">Tanggal</p>
                        <p class="mb-2">{{ $nextAppointment->appointment_date->format('d-m-Y') }} • Antrian {{ $nextAppointment->queue_number ?? '-' }}</p>
                        <p class="text-uppercase text-muted small mb-1">Status</p>
                        <span class="badge badge-status-warning">{{ ucfirst($nextAppointment->status) }}</span>
                    </div>
                    <a href="{{ route('dokter.reservasi.show', $nextAppointment) }}" class="btn btn-primary w-100">Buka Detail</a>
                @else
                    <div class="p-4 text-center text-muted">Tidak ada pasien berikutnya saat ini.</div>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection
