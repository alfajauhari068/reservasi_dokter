@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Dokter</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label>Nama Dokter</label>
            <input type="text" name="name" value="{{ $doctor->user->name }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Email (Akun Login)</label>
            <input type="email" name="email" value="{{ $doctor->user->email }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Password (Kosongkan jika tidak ingin ubah)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Spesialisasi</label>
            <select name="specialization_id" class="form-control @error('specialization_id') is-invalid @enderror" required>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->id }}" {{ $doctor->specialization_id == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                @endforeach
            </select>
            @error('specialization_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Nomor Lisensi</label>
            <input type="text" name="license_number" value="{{ $doctor->license_number }}" class="form-control @error('license_number') is-invalid @enderror" required>
            @error('license_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Pengalaman (tahun)</label>
            <input type="number" name="experience_years" value="{{ $doctor->experience_years }}" class="form-control @error('experience_years') is-invalid @enderror" required>
            @error('experience_years')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Biaya Konsultasi (Rp)</label>
            <input type="number" step="0.01" name="consultation_fee" value="{{ $doctor->consultation_fee }}" class="form-control @error('consultation_fee') is-invalid @enderror" required>
            @error('consultation_fee')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ $doctor->bio }}</textarea>
            @error('bio')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>Foto Dokter</label>
            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
            @if($doctor->photo)
                <div class="mt-2">
                    <small>Foto saat ini:</small><br>
                    <img src="{{ asset('storage/' . $doctor->photo) }}" width="150" class="img-thumbnail">
                </div>
            @endif
            @error('photo')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group mb-3">
            <label>
                <input type="checkbox" name="is_available" value="1" {{ $doctor->is_available ? 'checked' : '' }}>
                Dokter Tersedia
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection