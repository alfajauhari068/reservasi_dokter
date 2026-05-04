@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Dokter</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <label>Nama Dokter</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Email (Akun Login)</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Spesialisasi</label>
            <select name="specialization_id" class="form-control @error('specialization_id') is-invalid @enderror" required>
                <option value="">-- Pilih Spesialisasi --</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->id }}" {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                @endforeach
            </select>
            @error('specialization_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Nomor Lisensi</label>
            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" required value="{{ old('license_number') }}">
            @error('license_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Pengalaman (tahun)</label>
            <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" required value="{{ old('experience_years', 0) }}">
            @error('experience_years')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Biaya Konsultasi (Rp)</label>
            <input type="number" step="0.01" name="consultation_fee" class="form-control @error('consultation_fee') is-invalid @enderror" required value="{{ old('consultation_fee', 0) }}">
            @error('consultation_fee')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio') }}</textarea>
            @error('bio')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Foto Dokter</label>
            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
            @error('photo')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>
                <input type="checkbox" name="is_available" value="1" {{ old('is_available') ? 'checked' : '' }}>
                Dokter Tersedia
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection