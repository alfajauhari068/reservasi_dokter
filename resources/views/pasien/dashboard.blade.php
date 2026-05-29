@extends('layouts.patient')

@section('title', 'Dashboard Pasien')
@section('fullwidth', true)

@section('content')
<div class="text-[#0F172A] space-y-6 transition-all">
    <div class="max-w-7xl mx-auto w-full px-0 sm:px-0 md:px-0 pt-4 sm:pt-6 md:pt-8">
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(! isset($patient) || ! $patient)
        <div class="alert alert-warning mb-4">
            Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu sebelum membuat reservasi.
            <div class="mt-2">
                <a href="{{ route('pasien.profile.edit') }}" class="text-decoration-none">Lengkapi Profil</a>
            </div>
        </div>
    @endif

<div class="patient-portal-root">

    {{-- Banner Sambutan Utama (full-width rounded) --}}
    <div class="patient-portal-welcome w-full p-4 sm:p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="d-flex flex-column gap-4">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <span class="patient-portal-badge-primary px-3 py-1 rounded-pill text-uppercase">PATIENT DASHBOARD</span>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('pasien.reservasi.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-300">
                            <i class="bi bi-calendar-plus"></i> Buat Reservasi
                        </a>
                        <a href="{{ route('pasien.reservasi.history') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            <i class="bi bi-clock-history"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <h1 class="fw-bold" style="font-family: var(--font-primary); font-feature-settings:'ss01' 1,'ss02' 1; letter-spacing:-0.02em; font-size: clamp(2rem, 3vw, 3rem); line-height:1.06;">
                        Halo, {{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}
                    </h1>
                    <p class="mb-0" style="max-width:62ch; line-height:1.7; color: rgba(15,23,42,0.7);">
                        Pantau janji temu aktif, status reservasi, dan riwayat pemeriksaan Anda dalam satu tampilan yang nyaman.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid utama desktop: 3 kolom seimbang --}}
    <div class="max-w-7xl mx-auto mt-6">
        <div class="row g-4 g-lg-6">
            {{-- Kolom Kiri: Profil Pasien (span 3) --}}
            <div class="col-12 col-lg-3">
                <div class="patient-portal-card shadow-sm p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ds-avatar" style="width:72px;height:72px;">{{ strtoupper(substr(($patient->full_name ?? auth()->user()->name ?? 'P'),0,1)) }}</div>
                        <div>
                            <div class="fw-bold">{{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}</div>
                            <div class="text-muted small">ID Pasien: {{ $patient->id ?? '-' }}</div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: rgba(15,23,42,0.08);">

                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 rounded-4" style="background: rgba(0,100,224,0.08); border: 1px solid rgba(0,100,224,0.14);">
                            <div class="text-muted small fw-bold">Reservasi Aktif</div>
                            <div class="fw-black" style="font-family: var(--font-primary); font-feature-settings:'ss01' 1,'ss02' 1; font-size: 1.9rem;">{{ $activeCount ?? 0 }}</div>
                            <div class="text-muted small">Sedang berjalan</div>
                        </div>
                        <div class="p-3 rounded-4" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.14);">
                            <div class="text-muted small fw-bold">Reservasi Selesai</div>
                            <div class="fw-black" style="font-family: var(--font-primary); font-feature-settings:'ss01' 1,'ss02' 1; font-size: 1.9rem;">{{ $completedCount ?? 0 }}</div>
                            <div class="text-muted small">Sudah tuntas</div>
                        </div>
                    </div>

                    <a href="{{ route('pasien.profile.edit') }}" class="inline-flex items-center justify-center gap-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 mt-4">
                        <i class="bi bi-clipboard2-pulse"></i> Atur Profil & BPJS
                    </a>
                </div>
            </div>

            {{-- Kolom Tengah: Reservasi Terbaru (span 6) --}}
            <div class="col-12 col-lg-6">
                <div class="patient-portal-card shadow-sm p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">Reservasi Terbaru</div>
                            <div class="text-muted small">Daftar janji temu utama Anda.</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-100 bg-white">
                        <table class="w-full min-w-[750px] text-left border-collapse table table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 font-mono font-bold text-xs text-slate-500">Tanggal</th>
                                    <th class="px-6 py-4 font-mono font-bold text-xs text-slate-500">Dokter</th>
                                    <th class="px-6 py-4 font-mono font-bold text-xs text-slate-500">Spesialisasi</th>
                                    <th class="px-6 py-4 font-mono font-bold text-xs text-slate-500">Status</th>
                                    <th class="px-6 py-4 text-end font-mono font-bold text-xs text-slate-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    @php
                                        $status = $appointment->status ?? 'pending';
                                        $isCompleted = $status === 'completed';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4">{{ optional($appointment->doctor->user)->name ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ optional($appointment->doctor->specialization)->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($isCompleted)
                                                <span class="badge bg-green-50 text-green-700 border border-green-100 rounded-full px-3 py-1 font-bold">Completed</span>
                                            @else
                                                <span class="badge bg-blue-50 text-blue-700 border border-blue-100 rounded-full px-3 py-1 font-bold">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-end">
                                            <a href="{{ route('pasien.reservasi.show', $appointment->id) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition duration-200 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-200">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">Belum ada reservasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Reservasi Mendatang (span 3) --}}
            <div class="col-12 col-lg-3">
                <div class="patient-portal-slim-card shadow-sm p-4 h-100 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-bold" style="font-size: 1.05rem;">Reservasi Mendatang</div>
                        <span class="badge" style="background: rgba(0,100,224,0.12); color:#0064e0; border:1px solid rgba(0,100,224,0.16); border-radius: 9999px; font-weight: 900;">
                            Dijadwalkan
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($appointments as $appointment)
                            @if(($appointment->status ?? '') !== 'completed')
                                <div class="p-3 rounded-4" style="background: rgba(248,250,252,0.9); border: 1px solid rgba(148,163,184,0.25);">
                                    <div class="text-muted small">{{ $appointment->appointment_date->format('d-m-Y') }}</div>
                                    <div class="fw-bold" style="line-height:1.2;">{{ optional($appointment->doctor->user)->name ?? '-' }}</div>
                                    <div class="text-muted small">{{ optional($appointment->doctor->specialization)->name ?? '-' }}</div>
                                </div>
                            @endif
                        @empty
                            <div class="text-muted py-4 text-center">Tidak ada jadwal mendatang.</div>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-4">
                        <a href="{{ route('pasien.reservasi.create') }}" class="inline-flex items-center justify-center gap-2 w-full rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-300">
                            <i class="bi bi-calendar-plus"></i> Reservasi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Baris Bawah 1: Riwayat Pemeriksaan --}}
        <div class="row g-4 g-lg-6 mt-4">
            <div class="col-12">
                <div class="patient-portal-card shadow-sm p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">Riwayat Pemeriksaan</div>
                            <div class="text-muted small">Rekaman medis yang sudah selesai.</div>
                        </div>
                        <a href="{{ route('pasien.reservasi.history') }}" class="text-decoration-none fw-bold" style="color:#0064e0;">Lihat semua</a>
                    </div>

@if(empty($completedAppointments) || $completedAppointments->isEmpty())
                        <div class="py-5 text-center text-muted">Belum ada riwayat pemeriksaan.</div>
                    @else
                        <div class="list-group" style="border-radius: 1.25rem; overflow:hidden; border:1px solid rgba(148,163,184,0.25);">
                            @foreach($completedAppointments as $appointment)
                                <div class="list-group-item d-flex justify-content-between align-items-start" style="background: rgba(248,250,252,0.9); border:0; border-bottom:1px solid rgba(226,232,240,0.6);">
                                    <div>
                                        <div class="fw-semibold">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</div>
                                        <div class="text-muted small">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</div>
                                    </div>
                                    <span class="badge patient-portal-badge-success rounded-pill">Selesai</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Baris Bawah 2: Banner Konten Kesehatan --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="patient-portal-banner-health">
                    <div class="p-4 sm:p-6" style="background: linear-gradient(90deg, rgba(10,19,23,1) 0%, rgba(10,19,23,0.96) 55%, rgba(10,19,23,0.88) 100%);">
                        <div class="row g-4 align-items-center">
                            <div class="col-12 col-lg-5">
                                <div class="d-flex flex-column gap-2">
                                    <div class="badge" style="background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.92); border-radius: 9999px; width: fit-content; font-weight: 900;">
                                        PANDUAN KESEHATAN
                                    </div>
                                    <h2 class="fw-bold mb-0" style="font-size: clamp(1.25rem, 2.2vw, 1.8rem); line-height:1.2;">
                                        Panduan praktis agar kunjungan Anda lebih nyaman
                                    </h2>
                                    <div class="text-white-50" style="line-height:1.6;">Dua langkah sederhana sebelum dan saat konsultasi.</div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-5">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="patient-portal-health-check p-4">
                                            <div class="fw-bold">Sebelum Berangkat</div>
                                            <div class="text-white-50 small">Checklist dokumen & persiapan.</div>
                                            <div class="mt-3 d-flex gap-2">
                                                <span class="badge" style="background: rgba(0,100,224,0.25); color:#cfe6ff;">1</span>
                                                <span class="badge" style="background: rgba(0,100,224,0.25); color:#cfe6ff;">2</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="patient-portal-health-check p-4">
                                            <div class="fw-bold">Saat Konsultasi</div>
                                            <div class="text-white-50 small">Catat keluhan & pertanyaan penting.</div>
                                            <div class="mt-3 d-flex gap-2">
                                                <span class="badge" style="background: rgba(0,100,224,0.25); color:#cfe6ff;">A</span>
                                                <span class="badge" style="background: rgba(0,100,224,0.25); color:#cfe6ff;">B</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-2">
                                <a href="{{ route('pasien.reservasi.create') }}" class="inline-flex items-center justify-center w-full rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_30px_rgba(0,0,0,0.16)] transition duration-200 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                                    Mulai Reservasi ->
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footnote --}}
    <div class="ps-dashboard-footnote py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 text-muted small max-w-7xl mx-auto">
            <span>Informasi reservasi dan riwayat Anda diperbarui secara real-time.</span>
            <span>
                <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                ·
                <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
            </span>
        </div>
    </div>
</div>

@endsection



