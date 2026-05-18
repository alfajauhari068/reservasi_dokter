@extends('layouts.app')

@section('title', 'Lengkapi Profil Pasien')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Lengkapi Profil Pasien</h1>
        <p class="text-muted">Isi data profil untuk dapat melakukan reservasi.</p>
    </div>
    <a href="{{ route('pasien.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('pasien.profile.update') }}" class="row g-3">
            @csrf

            <div class="col-12">
                <label for="full_name" class="form-label fw-semibold">Nama Lengkap</label>
                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name', optional($patient)->full_name) }}"
                    placeholder="Nama lengkap pasien"
                    required
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="gender" class="form-label fw-semibold">Gender</label>
                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="" {{ old('gender', optional($patient)->gender) === null ? 'selected' : '' }}>-- Pilih --</option>
                    <option value="male" {{ old('gender', optional($patient)->gender) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', optional($patient)->gender) === 'female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="date_of_birth" class="form-label fw-semibold">Tanggal Lahir</label>
                <input
                    type="date"
                    name="date_of_birth"
                    id="date_of_birth"
                    class="form-control @error('date_of_birth') is-invalid @enderror"
                    value="{{ old('date_of_birth', optional($patient)->date_of_birth) }}"
                >
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="blood_type" class="form-label fw-semibold">Golongan Darah</label>
                <input
                    type="text"
                    name="blood_type"
                    id="blood_type"
                    maxlength="3"
                    class="form-control @error('blood_type') is-invalid @enderror"
                    value="{{ old('blood_type', optional($patient)->blood_type) }}"
                    placeholder="mis. A+"
                >
                @error('blood_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="identity_number" class="form-label fw-semibold">Nomor Identitas</label>
                <input
                    type="text"
                    name="identity_number"
                    id="identity_number"
                    maxlength="50"
                    class="form-control @error('identity_number') is-invalid @enderror"
                    value="{{ old('identity_number', optional($patient)->identity_number) }}"
                    placeholder="No. KTP / NIK"
                >
                @error('identity_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="address" class="form-label fw-semibold">Alamat</label>
                <textarea
                    name="address"
                    id="address"
                    rows="4"
                    class="form-control @error('address') is-invalid @enderror"
                    placeholder="Alamat lengkap pasien"
                >{{ old('address', optional($patient)->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('pasien.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection
