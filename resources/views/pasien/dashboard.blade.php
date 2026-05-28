@extends('layouts.app')

@section('title', 'Dashboard Pasien')
@section('fullwidth', true)

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">
    <div class="container-fluid fluid-dashboard-container">
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card clinical-card h-100 p-4">
                    <span class="badge-soft-primary px-3 py-1 text-xs-caps">PATIENT DASHBOARD</span>
                    <h1 class="display-6 fw-bold text-dark mt-3 mb-2">
                        Halo, {{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}
                    </h1>
                    <p class="text-secondary mb-4">
                        Pantau janji temu aktif, status reservasi, dan riwayat pemeriksaan Anda dalam satu tampilan penuh lebar.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold text-white">
                            <i class="bi bi-calendar-plus me-2"></i> Buat Reservasi
                        </a>
                        <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-primary px-4 py-2 rounded-3 fw-semibold bg-white border-light-subtle">
                            <i class="bi bi-clock-history me-2"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card clinical-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <button type="button"
                                class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle border-0"
                                style="width: 52px; height: 52px; cursor:pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#patientProfileModal"
                                aria-label="Lihat profil pasien">
                            <i class="bi bi-person-badge fs-4"></i>
                        </button>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold font-sans text-dark">{{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}</h6>
                            <span class="text-xs-caps text-secondary font-mono">ID PASIEN: {{ $patient->id ?? '-' }}</span>
                        </div>
                    </div>

                    <hr class="my-3 border-light">

                    <div class="row text-center mt-2">
                        <div class="col-6 border-end border-light-subtle">
                            <div class="text-xs-caps text-secondary mb-1">Reservasi Aktif</div>
                            <h2 class="fw-bold text-primary font-mono m-0 mb-1">{{ $activeCount ?? 0 }}</h2>
                            <span class="text-xs text-muted">Aktif</span>
                        </div>
                        <div class="col-6">
                            <div class="text-xs-caps text-secondary mb-1">Reservasi Selesai</div>
                            <h2 class="fw-bold text-success font-mono m-0 mb-1">{{ $completedCount ?? 0 }}</h2>
                            <span class="text-xs text-muted">Selesai</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('pasien.profile.edit') }}" class="btn btn-outline-primary w-100 py-2 rounded-3 fs-7 fw-semibold">
                            <i class="bi bi-gear-fill me-1"></i> Atur Profil & BPJS
                        </a>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card clinical-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1">Reservasi Terbaru</h5>
                            <p class="text-secondary mb-0">Daftar reservasi terbaru Anda dengan dokter.</p>
                        </div>
                    </div>

                    @if(empty($appointments) || $appointments->isEmpty())
                        <div class="py-5 text-center text-secondary">
                            <div class="fw-bold">Belum ada reservasi</div>
                            <div>Buat reservasi baru untuk mulai berkonsultasi dengan dokter.</div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
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
                                                <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('pasien.reservasi.show', $reservation) }}" class="text-decoration-none">Lihat Detail</a>
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
                <div class="card clinical-card p-4">
                    <h5 class="mb-3">Reservasi Mendatang</h5>
                    @php
                        $upcomingReservations = $appointments->whereIn('status', ['pending', 'in_progress'])->take(3);
                    @endphp
                    @if($upcomingReservations->isEmpty())
                        <div class="py-4 text-center text-secondary">
                            Tidak ada jadwal berikutnya. Reservasi berikutnya akan muncul di sini.
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($upcomingReservations as $reservation)
                                <div class="card border-light shadow-sm p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">{{ optional($reservation->doctor->user)->name ?? '-' }}</div>
                                            <div class="text-secondary small">{{ $reservation->appointment_date->format('d-m-Y') }} • {{ optional($reservation->doctor->specialization)->name ?? '-' }}</div>
                                        </div>
                                        <span class="badge bg-info text-dark">Dijadwalkan</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="card clinical-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">Riwayat Pemeriksaan</h5>
                            <p class="text-secondary mb-0">Catatan medis terbaru Anda.</p>
                        </div>
                    </div>
                    @if(empty($completedAppointments) || $completedAppointments->isEmpty())
                        <div class="py-5 text-center text-secondary">
                            Belum ada riwayat pemeriksaan. Riwayat akan muncul di sini setelah reservasi selesai.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($completedAppointments as $appointment)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ optional($appointment->doctor->user)->name ?? 'Dokter tidak ditemukan' }}</div>
                                        <div class="text-secondary small">{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }} • {{ $appointment->appointment_date->format('d-m-Y') }}</div>
                                    </div>
                                    <span class="badge bg-success">Selesai</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="ps-dashboard-footnote py-3 mt-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 text-muted small">
                <span>Informasi reservasi dan riwayat Anda diperbarui secara real-time.</span>
                <span>
                    <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                    ·
                    <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Patient Profile Modal -->
<div class="modal fade" id="patientProfileModal" tabindex="-1" aria-labelledby="patientProfileModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background: rgba(2,132,196,0.12);">
                <h5 class="modal-title font-sans fw-bold" id="patientProfileModalLabel">
                    <i class="bi bi-person-badge me-2"></i> Profil Pasien
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" style="background: rgba(2,132,196,0.08); border: 1px solid rgba(2,132,196,0.18);">
                            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 90px; height: 90px; margin: 0 auto;">
                                <i class="bi bi-person-fill fs-1"></i>
                            </div>
                            <div class="text-center mt-3">
                                <div class="fw-bold">{{ $patient->full_name ?? auth()->user()->name ?? 'Pasien' }}</div>
                                <div class="text-muted small">ID: {{ $patient->id ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="small text-muted">Gender</div>
                                <div class="fw-semibold">{{ $patient->gender ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Tanggal Lahir</div>
                                <div class="fw-semibold">
                                    @if(isset($patient->date_of_birth) && $patient->date_of_birth)
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Golongan Darah</div>
                                <div class="fw-semibold">{{ $patient->blood_type ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">No. Identitas</div>
                                <div class="fw-semibold">{{ $patient->identity_number ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">No. Telepon</div>
                                <div class="fw-semibold">{{ $patient->phone ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="small text-muted">Alamat</div>
                                <div class="fw-semibold">{{ $patient->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <a href="{{ route('pasien.profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-gear-fill me-2"></i> Edit Profil
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
