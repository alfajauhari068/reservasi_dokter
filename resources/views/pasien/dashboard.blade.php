@extends('layouts.app')

@section('title', 'Dashboard Pasien')

@section('content')
<section class="ps-hero-band mb-4">
    <div class="container-fluid">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <div class="ps-hero-left">
                    <div class="ps-label-badge">DASHBOARD PASIEN</div>
                    <h1 class="ps-hero-title">Halo, {{ auth()->user()->name }}</h1>
                    <p class="ps-hero-description">
                        Pantau janji temu, status reservasi, dan riwayat pemeriksaan Anda dalam satu tempat.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('pasien.reservasi.create') }}" class="ps-btn-primary">Buat Reservasi</a>
                        <a href="{{ route('pasien.reservasi.history') }}" class="ps-btn-secondary">Lihat Riwayat</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ps-patient-summary-card">
                    <div class="ps-patient-summary-header">
                        <div class="ps-patient-avatar">
                            @if(optional($patient)->photo)
                                <img src="{{ asset('storage/' . $patient->photo) }}" alt="Foto Pasien">
                            @else
                                <i class="fas fa-user-injured"></i>
                            @endif
                        </div>
                        <div>
                            <div class="ps-patient-name">{{ auth()->user()->name }}</div>
                            <div class="ps-patient-id">ID Pasien: {{ optional($patient)->id ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="ps-summary-metrics">
                        <div class="ps-summary-item">
                            <span class="ps-summary-label">Reservasi aktif</span>
                            <span class="ps-summary-value">{{ $activeCount ?? 0 }}</span>
                        </div>
                        <div class="ps-summary-item">
                            <span class="ps-summary-label">Selesai</span>
                            <span class="ps-summary-value">{{ $completedCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(session('error'))
    <div class="ps-alert ps-alert-critical mb-4">
        <div class="ps-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div>
            {{ session('error') }}
        </div>
    </div>
@endif

@if(! isset($patient) || ! $patient)
    <div class="ps-alert ps-alert-warning mb-4">
        <div class="ps-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.
        </div>
        <a href="{{ route('pasien.profile.edit') }}" class="ps-text-link">Lengkapi Profil</a>
    </div>
@endif

<section class="mb-4">
    <div class="container-fluid">
        <div class="row g-3 ps-stat-row">
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-primary"></div>
                    <div class="ps-stat-value">{{ $activeCount ?? 0 }}</div>
                    <div class="ps-stat-label">Reservasi Aktif</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-success"></div>
                    <div class="ps-stat-value">{{ $completedCount ?? 0 }}</div>
                    <div class="ps-stat-label">Reservasi Selesai</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="ps-stat-card">
                    <div class="ps-stat-icon ps-stat-icon-ink"></div>
                    <div class="ps-stat-value">{{ $totalCount ?? 0 }}</div>
                    <div class="ps-stat-label">Total Reservasi</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="container-fluid">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="ps-table-wrapper">
                    <div class="ps-table-header">
                        <h5 class="ps-table-title">Reservasi Terbaru</h5>
                        <p class="ps-table-subtitle">Daftar reservasi terbaru Anda dengan dokter.</p>
                    </div>

                    @if(empty($appointments) || $appointments->isEmpty())
                        <div class="ps-empty-state">
                            <div class="ps-empty-title">Belum ada reservasi</div>
                            <div class="ps-empty-description">Buat reservasi baru untuk mulai berkonsultasi dengan dokter.</div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="ps-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th>Spesialisasi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $reservation)
                                        <tr>
                                            <td>{{ $reservation->appointment_date->format('d-m-Y') }}</td>
                                            <td>{{ optional($reservation->doctor->user)->name ?? '-' }}</td>
                                            <td>{{ optional($reservation->doctor->specialization)->name ?? '-' }}</td>
                                            <td>
                                                <span class="ps-badge-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('pasien.reservasi.show', $reservation) }}" class="ps-text-link">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4">
                <div class="ps-upcoming-card">
                    <h5 class="ps-card-title">Reservasi Mendatang</h5>
                    @php
                        $upcomingReservations = $appointments->whereIn('status', ['pending', 'in_progress'])->take(3);
                    @endphp
                    @if($upcomingReservations->isEmpty())
                        <div class="ps-empty-state">
                            <div class="ps-empty-title">Tidak ada jadwal berikutnya</div>
                            <div class="ps-empty-description">Reservasi berikutnya akan muncul di sini.</div>
                        </div>
                    @else
                        @foreach($upcomingReservations as $reservation)
                            <div class="ps-upcoming-item">
                                <div>
                                    <div class="ps-upcoming-doctor">{{ optional($reservation->doctor->user)->name ?? '-' }}</div>
                                    <div class="ps-upcoming-meta">{{ $reservation->appointment_date->format('d-m-Y') }} • {{ optional($reservation->doctor->specialization)->name ?? '-' }}</div>
                                </div>
                                <span class="ps-badge-scheduled">Dijadwalkan</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="container-fluid">
        <div class="ps-history-wrapper">
            <div class="ps-history-header">
                <div>
                    <h5 class="ps-card-title">Riwayat Pemeriksaan</h5>
                    <p class="ps-table-subtitle">Catatan medis terbaru Anda.</p>
                </div>
            </div>
            @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                <div class="ps-empty-state">
                    <div class="ps-empty-title">Belum ada riwayat pemeriksaan</div>
                    <div class="ps-empty-description">Riwayat akan muncul di sini setelah reservasi selesai.</div>
                </div>
            @else
                <div class="ps-history-list">
                    @foreach($completedAppointments as $appointment)
                        <div class="ps-history-item">
                            <div>
                                <div class="ps-upcoming-doctor">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</div>
                                <div class="ps-upcoming-meta">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</div>
                            </div>
                            <span class="ps-badge-completed">Selesai</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

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
</div>
@endsection
