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
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Kuota</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->schedules as $schedule)
                                <tr>
                                    <td><strong>{{ ucfirst($schedule->day_of_week) }}</strong></td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}</td>
                                    <td>{{ $schedule->quota }}</td>
                                    <td>
                                        <a href="#edit-schedule-{{ $schedule->id }}" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSchedule{{ $schedule->id }}">Edit</a>
                                        <form action="{{ route('admin.doctors.schedules.destroy', [$doctor, $schedule]) }}" method="POST" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit Schedule -->
                                <div class="modal fade" id="editSchedule{{ $schedule->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Jadwal {{ ucfirst($schedule->day_of_week) }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.doctors.schedules.update', [$doctor, $schedule]) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="day_of_week_{{ $schedule->id }}" class="form-label">Hari</label>
                                                        <select name="day_of_week" id="day_of_week_{{ $schedule->id }}" class="form-select" required>
                                                            <option value="monday" {{ $schedule->day_of_week === 'monday' ? 'selected' : '' }}>Senin</option>
                                                            <option value="tuesday" {{ $schedule->day_of_week === 'tuesday' ? 'selected' : '' }}>Selasa</option>
                                                            <option value="wednesday" {{ $schedule->day_of_week === 'wednesday' ? 'selected' : '' }}>Rabu</option>
                                                            <option value="thursday" {{ $schedule->day_of_week === 'thursday' ? 'selected' : '' }}>Kamis</option>
                                                            <option value="friday" {{ $schedule->day_of_week === 'friday' ? 'selected' : '' }}>Jumat</option>
                                                            <option value="saturday" {{ $schedule->day_of_week === 'saturday' ? 'selected' : '' }}>Sabtu</option>
                                                            <option value="sunday" {{ $schedule->day_of_week === 'sunday' ? 'selected' : '' }}>Minggu</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="start_time_{{ $schedule->id }}" class="form-label">Jam Mulai</label>
                                                        <input type="time" name="start_time" id="start_time_{{ $schedule->id }}" class="form-control" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="end_time_{{ $schedule->id }}" class="form-label">Jam Selesai</label>
                                                        <input type="time" name="end_time" id="end_time_{{ $schedule->id }}" class="form-control" value="{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="quota_{{ $schedule->id }}" class="form-label">Kuota Pasien</label>
                                                        <input type="number" name="quota" id="quota_{{ $schedule->id }}" class="form-control" min="1" value="{{ $schedule->quota }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                    <img src="{{ asset('storage/' . $doctor->photo) }}" class="card-img-top" alt="Foto Dokter" style="max-height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <p class="card-text text-center">Foto {{ $doctor->user->name }}</p>
                    </div>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Jadwal Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.schedules.store', $doctor) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="day_of_week" class="form-label">Hari</label>
                            <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                                <option value="sunday">Minggu</option>
                            </select>
                            @error('day_of_week') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="start_time" class="form-label">Jam Mulai</label>
                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">Jam Selesai</label>
                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quota" class="form-label">Kuota Pasien</label>
                            <input type="number" name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" min="1" value="10" required>
                            @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Tambah Jadwal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection