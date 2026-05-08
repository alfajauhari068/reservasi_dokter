@extends('layouts.app')

@section('title', 'Kelola Schedule')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Kelola Schedule</h1>
        <p class="text-muted">Atur jadwal praktek Anda untuk setiap hari dalam seminggu.</p>
    </div>
    <div>
        <a href="{{ route('dokter.dashboard') }}" class="btn btn-outline-secondary me-2">Kembali ke Dashboard</a>
        <a href="{{ route('dokter.schedule.create') }}" class="btn btn-primary">Tambah Schedule</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Schedule</h5>
    </div>
    <div class="card-body">
        @if($schedules->isEmpty())
            <div class="text-center py-4">
                <p class="text-muted">Belum ada schedule yang dibuat.</p>
                <a href="{{ route('dokter.schedule.create') }}" class="btn btn-primary">Buat Schedule Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ ucfirst($schedule->day_of_week) }}</td>
                                <td>{{ $schedule->start_time }}</td>
                                <td>{{ $schedule->end_time }}</td>
                                <td>
                                    <a href="{{ route('dokter.schedule.edit', $schedule) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dokter.schedule.destroy', $schedule) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus schedule ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection