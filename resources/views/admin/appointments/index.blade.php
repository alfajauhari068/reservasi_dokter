@extends('layouts.admin')

@section('page-title', 'Daftar Appointment Admin')
@section('page-subtitle', 'Riwayat reservasi pasien yang dibuat oleh admin')

@section('content')
<div class="admin-card">
    <div class="admin-card__header d-flex align-items-center justify-content-between gap-3 p-4">
        <div>
            <h2 class="admin-card__title h5 mb-2">Daftar Appointment</h2>
            <p class="admin-card__subtitle text-muted mb-0">Lihat semua appointment yang dibuat oleh admin beserta status dan jadwal.</p>
        </div>
        <div>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Appointment
            </a>
        </div>
    </div>

    <div class="admin-card__body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table admin-table table-hover align-middle mb-0">
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
                        @php
                            $statusClass = match ($appointment->status) {
                                'completed' => 'badge-status-success',
                                'approved', 'in_progress' => 'badge-status-info',
                                'pending' => 'badge-status-warning',
                                'cancelled' => 'badge-status-critical',
                                default => 'badge-status-info',
                            };
                            $approvalClass = match ($appointment->approval_status) {
                                'approved' => 'badge-status-success',
                                'pending' => 'badge-status-warning',
                                'rejected' => 'badge-status-critical',
                                default => 'badge-status-info',
                            };
                        @endphp
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
                                <span class="badge {{ $statusClass }} text-capitalize">{{ str_replace('_', ' ', $appointment->status) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $approvalClass }} text-capitalize">{{ str_replace('_', ' ', $appointment->approval_status) }}</span>
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

        <div class="admin-pagination mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
