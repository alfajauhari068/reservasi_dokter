@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="ds-dashboard-content">
    <section class="ds-hero-band mb-4">
        <div class="container-fluid">
            <div class="row g-4 align-items-start">
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
                <div class="col-lg-5">
                    @if($doctor)
                        <div class="ds-doctor-sidebar">
                            <div class="ds-doctor-avatar">
                                @if($doctor->photo)
                                    <img src="{{ asset('storage/' . $doctor->photo) }}" alt="Foto Profil Dokter">
                                @else
                                    <img src="{{ asset('assets/doctor-avatar-circle.svg') }}" alt="Avatar Dokter">
                                @endif
                            </div>
                            <div class="ds-badge-success">Aktif</div>
                            <div class="ds-info-row">
                                <span class="ds-info-label">Spesialisasi</span>
                                <span class="ds-info-value">
                                    {{ optional($doctor->specialization)->name ?? 'Belum diatur' }}
                                </span>
                            </div>
                            <div class="ds-info-row">
                                <span class="ds-info-label">ID Dokter</span>
                                <span class="ds-info-value">
                                    {{ optional($doctor)->id ?? '-' }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="ds-empty-state">
                            <div class="ds-empty-title">Profil dokter tidak ditemukan</div>
                            <div class="ds-empty-description">
                                Pastikan akun dokter sudah terhubung dengan data dokter.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($doctor)
        <section class="mb-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="ds-stat-card">
                            <svg class="ds-stat-icon ds-stat-icon-primary" aria-hidden="true"></svg>
                            <div class="ds-stat-value">{{ $pendingAppointments->count() }}</div>
                            <div class="ds-stat-label">Permintaan Baru</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="ds-stat-card">
                            <svg class="ds-stat-icon ds-stat-icon-success" aria-hidden="true"></svg>
                            <div class="ds-stat-value">{{ $completedCount }}</div>
                            <div class="ds-stat-label">Riwayat Selesai</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="ds-stat-card">
                            <svg class="ds-stat-icon ds-stat-icon-ink" aria-hidden="true"></svg>
                            <div class="ds-stat-value">
                                {{ $pendingAppointments->count() + $completedCount }}
                            </div>
                            <div class="ds-stat-label">Total Pasien Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="container-fluid">
                <div class="row g-3">
                    <div class="col-xl-8">
                        <div class="ds-table-wrapper">
                            <div class="p-3 border-bottom ds-table-header">
                                <h5 class="mb-1">Daftar Reservasi Pasien</h5>
                                <p class="mb-0 ds-table-subtitle">
                                    Daftar reservasi yang perlu ditindaklanjuti oleh dokter.
                                </p>
                            </div>

                            @if($pendingAppointments->isEmpty())
                                <div class="p-0">
                                    <div class="ds-empty-state">
                                        <div class="ds-empty-title">Belum ada reservasi baru</div>
                                        <div class="ds-empty-description">
                                            Reservasi pasien akan muncul di sini.
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="ds-table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Pasien</th>
                                                <th>Nomor Antrian</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingAppointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                                    <td>
                                                        {{ optional($appointment->patient->user)->name
                                                        ?? $appointment->patient->full_name
                                                        ?? $appointment->patient->identity_number }}
                                                    </td>
                                                    <td>{{ $appointment->queue_number ?? '-' }}</td>
                                                    <td>
                                                        <span class="ds-badge-warning">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('dokter.reservasi.show', $appointment) }}"
                                                           class="ds-text-link">
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

                    <div class="col-xl-4">
                        @php
                        $nextAppointment = $pendingAppointments->first();
                        @endphp
                        @if($nextAppointment)
                            <div class="ds-next-patient-card">
                                <h3 class="ds-card-title">Pasien Berikutnya</h3>
                                <div class="ds-patient-name">
                                    {{ optional($nextAppointment->patient->user)->name
                                    ?? $nextAppointment->patient->full_name
                                    ?? $nextAppointment->patient->identity_number }}
                                </div>
                                <div class="ds-patient-meta">
                                    <div class="ds-meta-item">
                                        📅 {{ $nextAppointment->appointment_date->format('d-m-Y') }}
                                    </div>
                                    <div class="ds-meta-item">
                                        🎫 Antrian: {{ $nextAppointment->queue_number ?? '-' }}
                                    </div>
                                </div>
                                <span class="ds-badge-warning">
                                    {{ ucfirst($nextAppointment->status) }}
                                </span>
                                <a href="{{ route('dokter.reservasi.show', $nextAppointment) }}"
                                   class="ds-btn-primary ds-btn-full mt-2">
                                    Buka Detail
                                </a>
                            </div>
                        @else
                            <div class="ds-empty-state">
                                <div class="ds-empty-title">Tidak ada pasien berikutnya</div>
                                <div class="ds-empty-description">
                                    Semua reservasi telah diproses.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
