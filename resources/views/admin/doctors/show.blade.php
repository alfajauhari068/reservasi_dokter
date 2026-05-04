@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Dokter</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $doctor->user->name }}</p>
                    <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
                    <p><strong>Spesialisasi:</strong> {{ $doctor->specialization->name }}</p>
                    <p><strong>Nomor Lisensi:</strong> {{ $doctor->license_number }}</p>
                    <p><strong>Pengalaman:</strong> {{ $doctor->experience_years }} tahun</p>
                    <p><strong>Biaya Konsultasi:</strong> Rp {{ number_format($doctor->consultation_fee, 0, ',', '.') }}</p>
                    <p><strong>Bio:</strong> {{ $doctor->bio ?? 'Tidak ada' }}</p>
                    <p><strong>Status:</strong> 
                        @if($doctor->is_available)
                            <span class="badge bg-success">Tersedia</span>
                        @else
                            <span class="badge bg-danger">Tidak Tersedia</span>
                        @endif
                    </p>
                </div>
            </div>

            <h3 class="mt-4">Jadwal Periksa</h3>
            @if($doctor->schedules->count() > 0)
                <ul class="list-group">
                    @foreach($doctor->schedules as $schedule)
                        <li class="list-group-item">
                            <strong>{{ $schedule->day_of_week }}</strong>: 
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}
                            (Kuota: {{ $schedule->quota }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Belum ada jadwal periksa</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
        <div class="col-md-4">
            @if($doctor->photo)
                <div class="card">
                    <img src="{{ asset('storage/' . $doctor->photo) }}" class="card-img-top" alt="Foto Dokter">
                    <div class="card-body">
                        <p class="card-text text-center">Foto {{ $doctor->user->name }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection