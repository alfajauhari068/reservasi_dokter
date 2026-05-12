@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Riwayat Reservasi</h1>
            <p class="text-muted">Lihat semua reservasi pasien, termasuk status pemeriksaan, dan cetak riwayat pemeriksaan.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.approvals.index') }}">
                <div class="row gy-3">
                    <div class="col-md-3">
                        <label class="form-label">Periode</label>
                        <select class="form-select" name="period">
                            <option value="" {{ request('period') == '' ? 'selected' : '' }}>Semua</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Harian</option>
                            <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Mingguan</option>
                            <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulanan</option>
                            <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Tanggal Spesifik</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nama Pasien</label>
                        <input type="text" name="patient" class="form-control" placeholder="Cari nama pasien..." value="{{ request('patient') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Dokter</label>
                        <input type="text" name="doctor" class="form-control" placeholder="Cari nama dokter..." value="{{ request('doctor') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Spesialisasi</label>
                        <select class="form-select" name="specialization">
                            <option value="">Semua Spesialisasi</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ request('specialization') == $specialization->id ? 'selected' : '' }}>{{ $specialization->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary me-2">Terapkan Filter</button>
                        <a href="{{ route('admin.approvals.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-history fa-2x text-primary"></i>
                    </div>
                    <h3 class="mb-1">{{ $appointments->total() }}</h3>
                    <p class="text-muted mb-0">Data reservasi yang cocok filter</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Riwayat Reservasi</h5>
        </div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Spesialisasi</th>
                                <th>Tanggal Periksa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td><strong>{{ $appointment->booking_code }}</strong></td>
                                    <td>
                                        <div>{{ $appointment->patient->user->name }}</div>
                                        <small class="text-muted">{{ $appointment->patient->user->email }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $appointment->doctor->user->name }}</div>
                                        <small class="text-muted">{{ $appointment->doctor->user->email }}</small>
                                    </td>
                                    <td>{{ optional($appointment->doctor->specialization)->name ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}<br>
                                        <small class="text-muted">{{ optional($appointment->schedule)->start_time ? substr($appointment->schedule->start_time, 0, 5) : '-' }} - {{ optional($appointment->schedule)->end_time ? substr($appointment->schedule->end_time, 0, 5) : '-' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $appointment->status;
                                            $badge = 'secondary';
                                            if ($status === 'approved') $badge = 'info';
                                            elseif ($status === 'in_progress') $badge = 'warning';
                                            elseif ($status === 'completed') $badge = 'success';
                                            elseif ($status === 'cancelled') $badge = 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $badge }} text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.approvals.show', $appointment) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="alert alert-info text-center mb-0">
                    <i class="bi bi-info-circle"></i> Tidak ada reservasi yang sesuai filter.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
