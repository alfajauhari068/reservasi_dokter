@extends('layouts.app')

@section('title', 'Tambah Schedule')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Tambah Schedule</h1>
        <p class="text-muted">Tambahkan jadwal praktek baru.</p>
    </div>
    <a href="{{ route('dokter.schedule.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dokter.schedule.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Hari <span class="text-danger">*</span></label>
                        <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                            <option value="">Pilih hari</option>
                            <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Senin</option>
                            <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                            <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                            <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                            <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Jumat</option>
                            <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                            <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                        </select>
                        @error('day_of_week')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @error('time')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('dokter.schedule.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection