@extends('layouts.admin')

@section('page-title', 'Daftar Appointment Admin')
@section('page-subtitle', 'Riwayat reservasi pasien yang dibuat oleh admin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-4 d-flex align-items-center justify-content-between gap-3">
        <div>
            <h4 class="mb-2 fw-bold">Daftar Appointment</h4>
            <p class="text-muted mb-0">Lihat semua appointment yang dibuat oleh admin beserta status dan jadwal.</p>
        </div>
        <div>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Appointment
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal Appointment</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ optional($appointment->appointment_date)->format('d M Y') }}</td>
                            <td>{{ optional($appointment->patient->user)->name ?? $appointment->patient->full_name ?? $appointment->patient->identity_number }}</td>
                            <td>{{ optional($appointment->doctor->user)->name ?? '-' }}</td>
                            <td>
                                @if(optional($appointment->schedule)->day_of_week)
                                    {{ ucfirst(optional($appointment->schedule)->day_of_week) }}
                                @endif
                                @if(optional($appointment->schedule)->start_time)
                                    {{ optional($appointment->schedule)->start_time }} - {{ optional($appointment->schedule)->end_time }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $appointment->status) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark text-capitalize">{{ str_replace('_', ' ', $appointment->approval_status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada appointment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
