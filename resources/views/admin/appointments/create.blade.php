@extends('layouts.admin')

@section('page-title', 'Buat Appointment Admin')
@section('page-subtitle', 'Admin membuat reservasi untuk pasien secara langsung')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div id="createAppointmentPage" class="card border-0 shadow-sm">
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
                    <form method="POST" action="{{ route('admin.appointments.store') }}" class="row g-3" style="min-width:0;">
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
                <label for="specialization_id" class="form-label fw-semibold">Spesialisasi</label>
                <select name="specialization_id" id="specialization_id" class="form-select @error('specialization_id') is-invalid @enderror">
                    <option value="" selected>-- Pilih Spesialisasi --</option>
                    @foreach($specializations as $specialization)
                        <option value="{{ $specialization->id }}" {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
                @error('specialization_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    Pilih spesialisasi untuk menyaring daftar dokter.
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="doctor_id" class="form-label fw-semibold">Dokter</label>
                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                    <option value="" selected disabled>-- Pilih Dokter --</option>
                    @foreach($doctors as $doctor)
                        <option
                            value="{{ $doctor['id'] }}"
                            data-specialization-id="{{ $doctor['specialization_id'] }}"
                            {{ old('doctor_id') == $doctor['id'] ? 'selected' : '' }}>
                            {{ $doctor['name'] }} ({{ $doctor['specialization_name'] }})
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
                            if (isset($schedule->day_of_week)) {
                                $appointmentLabelParts[] = ucfirst($schedule->day_of_week);
                            }
                            if (isset($schedule->start_time)) {
                                $appointmentLabelParts[] = $schedule->start_time;
                            }
                            if (isset($schedule->end_time)) {
                                $appointmentLabelParts[] = ' - ' . $schedule->end_time;
                            }

                            $doctorName = $schedule->doctor->user->name ?? null;
                            if ($doctorName) {
                                $appointmentLabelParts[] = $doctorName;
                            }

                            $doctorSpecialization = $schedule->doctor->specialization->name ?? 'Umum';
                            $appointmentLabelParts[] = '(' . $doctorSpecialization . ')';

                            $label = trim(implode(' ', $appointmentLabelParts));
                            $label = $label !== '' ? $label : ('Schedule #' . $schedule->id);
                        @endphp
                        <option
                            value="{{ $schedule->id }}"
                            data-doctor-id="{{ $schedule->doctor_id }}"
                            {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const specializationSelect = document.getElementById('specialization_id');
        const doctorSelect = document.getElementById('doctor_id');
        const scheduleSelect = document.getElementById('schedule_id');

        const doctorOptions = Array.from(doctorSelect.querySelectorAll('option'));
        const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option'));

        const filterDoctors = () => {
            const selectedSpecialization = specializationSelect.value;
            doctorSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.selected = true;
            placeholder.textContent = '-- Pilih Dokter --';
            doctorSelect.appendChild(placeholder);

            doctorOptions.forEach(option => {
                const matchesSpecialization = !selectedSpecialization || option.dataset.specializationId === selectedSpecialization;
                if (option.value && matchesSpecialization) {
                    doctorSelect.appendChild(option.cloneNode(true));
                }
            });

            if (!doctorSelect.querySelector(`option[value="${doctorSelect.dataset.selected}"]`)) {
                doctorSelect.value = '';
            } else {
                doctorSelect.value = doctorSelect.dataset.selected;
            }

            filterSchedules();
        };

        const filterSchedules = () => {
            const selectedDoctorId = doctorSelect.value;
            scheduleSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.disabled = true;
            placeholder.selected = true;
            placeholder.textContent = '-- Pilih Jadwal --';
            scheduleSelect.appendChild(placeholder);

            scheduleOptions.forEach(option => {
                const matchesDoctor = !selectedDoctorId || option.dataset.doctorId === selectedDoctorId;
                if (option.value && matchesDoctor) {
                    scheduleSelect.appendChild(option.cloneNode(true));
                }
            });

            if (!scheduleSelect.querySelector(`option[value="${scheduleSelect.dataset.selected}"]`)) {
                scheduleSelect.value = '';
            } else {
                scheduleSelect.value = scheduleSelect.dataset.selected;
            }
        };

        // Store old selection values in dataset, if present.
        doctorSelect.dataset.selected = '{{ old('doctor_id') }}';
        scheduleSelect.dataset.selected = '{{ old('schedule_id') }}';

        specializationSelect.addEventListener('change', filterDoctors);
        doctorSelect.addEventListener('change', filterSchedules);

        filterDoctors();
    });
</script>
@endpush

