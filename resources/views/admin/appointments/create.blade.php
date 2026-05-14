@extends('layouts.admin')

@section('page-title', 'Buat Appointment Admin')
@section('page-subtitle', 'Admin membuat reservasi untuk pasien secara langsung')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h4 class="mb-2 fw-bold">Buat Appointment</h4>
                <p class="text-muted mb-1">Isi data appointment untuk pasien berikut.</p>
                <p class="text-muted small mb-0">Appointment yang dibuat di sini akan muncul di halaman Riwayat Reservasi / Daftar Appointment admin.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.appointments.store') }}" class="row g-3">
            @csrf

            <div class="col-12 col-md-6">
                <label for="patient_id" class="form-label fw-semibold">Pasien</label>
                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror">
                    <option value="" selected disabled>-- Pilih Pasien --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient['id'] }}" {{ old('patient_id') == $patient['id'] ? 'selected' : '' }}>
                            {{ $patient['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    Jika pasien belum terdaftar, tambahkan pasien baru terlebih dahulu.
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.patients.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Tambah Pasien Baru
                    </a>
                </div>
            </div>


            <div class="col-12 col-md-6">
                <label for="doctor_id" class="form-label fw-semibold">Dokter</label>
                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                    <option value="" selected disabled>-- Pilih Dokter --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor['id'] }}" {{ old('doctor_id') == $doctor['id'] ? 'selected' : '' }}>
                            {{ $doctor['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="schedule_id" class="form-label fw-semibold">Jadwal</label>
                <select name="schedule_id" id="schedule_id" class="form-select @error('schedule_id') is-invalid @enderror">
                    <option value="" selected disabled>-- Pilih Jadwal --</option>
                    @foreach($schedules as $schedule)
                        @php
                            $appointmentLabelParts = [];
                            // day_of_week bisa berupa string seperti monday/tuesday, atau sudah tersimpan.
                            if (isset($schedule->day_of_week)) {
                                $appointmentLabelParts[] = ucfirst($schedule->day_of_week);
                            }
                            // jam
                            if (isset($schedule->start_time)) {
                                $appointmentLabelParts[] = $schedule->start_time;
                            }
                            if (isset($schedule->end_time)) {
                                $appointmentLabelParts[] = ' - ' . $schedule->end_time;
                            }

                            // dokter (jika relasi tersedia)
                            $doctorName = $schedule->doctor->user->name ?? null;
                            if ($doctorName) {
                                $appointmentLabelParts[] = $doctorName;
                            }

                            $label = trim(implode(' ', $appointmentLabelParts));
                            // fallback jika label kosong
                            $label = $label !== '' ? $label : ('Schedule #' . $schedule->id);
                        @endphp
                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('schedule_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="appointment_date" class="form-label fw-semibold">Tanggal Kunjungan</label>
                <input
                    type="date"
                    name="appointment_date"
                    id="appointment_date"
                    class="form-control @error('appointment_date') is-invalid @enderror"
                    value="{{ old('appointment_date') }}"
                >
                @error('appointment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="complaint" class="form-label fw-semibold">Keluhan</label>
                <textarea
                    name="complaint"
                    id="complaint"
                    rows="4"
                    class="form-control @error('complaint') is-invalid @enderror"
                >{{ old('complaint') }}</textarea>
                @error('complaint')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="notes" class="form-label fw-semibold">Catatan</label>
                <textarea
                    name="notes"
                    id="notes"
                    rows="3"
                    class="form-control @error('notes') is-invalid @enderror"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

