@extends('layouts.app')

@section('title', 'Reservasi Dokter')

@section('content')
<div class="row gx-4 gy-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Reservasi Pasien</span>
                <h1 class="h3 mb-1">Buat Janji Konsultasi</h1>
                <p class="text-muted mb-0">Pilih dokter, tanggal, dan slot jam untuk memesan konsultasi medis.</p>
            </div>
            <a href="{{ route('pasien.reservasi.history') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-clock-history me-1"></i>Lihat Riwayat
            </a>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('pasien.reservasi.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="doctor_id" class="form-label text-secondary">Dokter</label>
                        <select id="doctor_id" name="doctor_id" class="form-select form-select-lg @error('doctor_id') is-invalid @enderror">
                            <option value="">Pilih dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name }} @if($doctor->specialization) • {{ $doctor->specialization->name }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="appointment_date" class="form-label text-secondary">Tanggal Konsultasi</label>
                            <input id="appointment_date" type="date" name="appointment_date" value="{{ old('appointment_date') }}" class="form-control @error('appointment_date') is-invalid @enderror">
                            @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label text-secondary">Slot Jam</label>
                            <select id="schedule_id" name="schedule_id" class="form-select form-select-lg @error('schedule_id') is-invalid @enderror">
                                <option value="">Pilih slot jam</option>
                                @php $totalSchedules = 0; @endphp
                                @foreach($doctors as $doctor)
                                    @foreach($doctor->schedules as $schedule)
                                        @php $totalSchedules++; @endphp
                                        <option value="{{ $schedule->id }}"
                                            data-doctor="{{ $doctor->id }}"
                                            data-day="{{ $schedule->day_of_week }}"
                                            {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ ucfirst($schedule->day_of_week) }} • {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                            ({{ $doctor->user->name }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('schedule_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="scheduleHelp" class="form-text small text-muted">
                                @if($totalSchedules == 0)
                                    <span class="text-danger">Tidak ada jadwal dokter. Harap hubungi admin.</span>
                                @else
                                    Pilih dokter dan tanggal untuk melihat slot yang tersedia. Total jadwal: {{ $totalSchedules }}.
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="complaint" class="form-label text-secondary">Keluhan</label>
                        <textarea id="complaint" name="complaint" rows="5" class="form-control @error('complaint') is-invalid @enderror">{{ old('complaint') }}</textarea>
                        @error('complaint')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Lengkapi data reservasi sebelum mengirim.</small>
                        <button type="submit" class="btn btn-primary btn-lg px-4">Simpan Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-4">
                <h5 class="mb-3">Panduan Reservasi</h5>
                <p class="small text-muted">Isilah semua data sesuai dengan jadwal dokter. Sistem akan menampilkan nomor antrian setelah reservasi berhasil.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent border-0 px-0 py-2">
                        <strong>1.</strong> Pilih dokter sesuai spesialisasi.
                    </li>
                    <li class="list-group-item bg-transparent border-0 px-0 py-2">
                        <strong>2.</strong> Pilih tanggal konsultasi yang tersedia.
                    </li>
                    <li class="list-group-item bg-transparent border-0 px-0 py-2">
                        <strong>3.</strong> Pilih slot jam dan isi keluhan.
                    </li>
                    <li class="list-group-item bg-transparent border-0 px-0 py-2">
                        <strong>4.</strong> Simpan lalu cek detail di halaman konfirmasi.
                    </li>
                </ul>
                <div class="mt-4">
                    <h6 class="mb-2">Status Jadwal</h6>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">Aktif</span>
                        <span class="text-muted">Dokter siap menerima reservasi.</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2">Pending</span>
                        <span class="text-muted">Reservasi akan diverifikasi segera.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const doctorSelect = document.getElementById('doctor_id');
        const dateInput = document.getElementById('appointment_date');
        const scheduleSelect = document.getElementById('schedule_id');

        function getDayName(dateValue) {
            if (!dateValue) {
                return null;
            }
            const date = new Date(dateValue + 'T00:00:00');
            return date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        }

        function filterSlots() {
            const selectedDoctor = doctorSelect.value;
            const selectedDate = dateInput.value;
            const selectedDay = getDayName(selectedDate);

            Array.from(scheduleSelect.options).forEach(option => {
                if (!option.value) {
                    option.hidden = false;
                    option.disabled = false;
                    return;
                }

                const doctorId = option.dataset.doctor;
                const dayName = option.dataset.day;
                const matchDoctor = !selectedDoctor || doctorId === selectedDoctor;
                const matchDay = !selectedDate || dayName === selectedDay;

                option.hidden = !(matchDoctor && matchDay);
                option.disabled = !(matchDoctor && matchDay);
            });

            const visibleOptions = Array.from(scheduleSelect.options).filter(option => !option.hidden && option.value);
            const scheduleHelp = document.getElementById('scheduleHelp');

            if (visibleOptions.length === 0 && selectedDoctor && selectedDate) {
                scheduleHelp.textContent = 'Tidak ada slot yang tersedia untuk tanggal dan dokter ini.';
            } else {
                scheduleHelp.textContent = 'Pilih dokter dan tanggal untuk melihat slot yang tersedia. Total jadwal: {{ $totalSchedules }}.';
            }
        }

        doctorSelect.addEventListener('change', filterSlots);
        dateInput.addEventListener('change', filterSlots);

        filterSlots();
    });
</script>
@endsection
