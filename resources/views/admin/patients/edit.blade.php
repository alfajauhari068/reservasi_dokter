@extends('layouts.admin')

@section('page-title', 'Edit Pasien')
@section('page-subtitle', 'Perbarui data identitas pasien')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h4 class="mb-2 fw-bold">Edit Pasien</h4>
                <p class="text-muted mb-0">Ubah data pasien untuk kebutuhan appointment.</p>
            </div>
            <div>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.patients.update', $patient->id) }}" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-12">
                <label for="full_name" class="form-label fw-semibold">Nama Pasien</label>
                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name', $patient->full_name) }}"
                    placeholder="Nama lengkap pasien"
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="gender" class="form-label fw-semibold">Gender</label>
                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="" {{ !$patient->gender ? 'selected' : '' }}>-- Pilih --</option>
                    <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Female</option>
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
                    value="{{ old('date_of_birth', $patient->date_of_birth) }}"
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
                    value="{{ old('blood_type', $patient->blood_type) }}"
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
                    value="{{ old('identity_number', $patient->identity_number) }}"
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
                    placeholder="Alamat pasien"
                >{{ old('address', $patient->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

