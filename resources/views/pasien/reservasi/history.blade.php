@extends('layouts.patient')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Riwayat Pemeriksaan</h1>
        <p class="text-muted">Lihat semua reservasi Anda dan detail pemeriksaan yang sudah ditangani dokter.</p>
    </div>
    <div>
        <a href="{{ route('pasien.reservasi.create') }}" class="btn btn-primary me-2">Buat Reservasi Baru</a>
        <a href="{{ route('pasien.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(! empty($errorMessage))
    <div class="alert alert-danger">{{ $errorMessage }}</div>
@endif

<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-0">
                @if($appointments->isEmpty())
                    <div class="p-4 text-center text-muted">Belum ada reservasi yang tercatat.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Dokter</th>
                                    <th>Spesialisasi</th>
                                    <th>Jam</th>
                                    <th>Nomor Antrian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_date->format('d-m-Y') }}</td>
                                        <td>{{ optional($appointment->doctor->user)->name ?? '-' }}</td>
                                        <td>{{ optional($appointment->doctor->specialization)->name ?? 'Umum' }}</td>
                                        <td>{{ substr($appointment->schedule->start_time, 0, 5) }} - {{ substr($appointment->schedule->end_time, 0, 5) }}</td>
                                        <td>{{ $appointment->queue_number ?? '-' }}</td>
                                        <td>{{ ucfirst($appointment->status) }}</td>
                                        <td><a href="{{ route('pasien.reservasi.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Ringkasan Riwayat</h5>
                <p class="mb-2">Total reservasi: <strong>{{ $appointments->count() }}</strong></p>
                <p class="mb-0">Reservasi selesai: <strong>{{ $completedAppointments->count() }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
