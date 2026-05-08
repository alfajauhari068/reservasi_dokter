<?php

namespace App\Http\Controllers\Pasien;

use App\Events\AppointmentRequested;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pasien']);
    }

    public function create()
    {
        $doctors = Doctor::with('user', 'specialization', 'schedules')
            ->where('is_available', true)
            ->get();

        return view('pasien.reservasi.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'complaint' => 'nullable|string|max:2000',
        ]);

        $patient = Patient::where('user_id', auth()->id())->first();
        if (! $patient) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu.');
        }

        $doctor = Doctor::with('schedules')->find($request->doctor_id);
        if (! $doctor) {
            return back()->withErrors(['doctor_id' => 'Dokter tidak ditemukan.'])->withInput();
        }

        $schedule = $doctor->schedules()->where('id', $request->schedule_id)->first();
        if (! $schedule) {
            return back()->withErrors(['schedule_id' => 'Slot jadwal tidak valid untuk dokter yang dipilih.'])->withInput();
        }

        $appointmentDate = $request->appointment_date;
        $selectedDay = strtolower(Carbon::parse($appointmentDate)->format('l'));

        if ($schedule->day_of_week !== $selectedDay) {
            return back()->withErrors(['appointment_date' => 'Tanggal tidak sesuai dengan jadwal yang dipilih.'])->withInput();
        }

        $nextQueue = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $appointmentDate)
            ->max('queue_number');

        $queueNumber = $nextQueue ? $nextQueue + 1 : 1;
        $bookingCode = strtoupper('BK' . date('Ymd') . Str::random(4));

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'schedule_id' => $schedule->id,
                'appointment_date' => $appointmentDate,
                'complaint' => $request->complaint,
                'status' => 'pending',
                'booking_code' => $bookingCode,
                'queue_number' => $queueNumber,
                'queue_date' => $appointmentDate,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('pasien.reservasi.show', $appointment)->with('success', 'Reservasi berhasil dibuat.');
    }

    public function history()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        if (! $patient) {
            return view('pasien.reservasi.history', [
                'appointments' => collect(),
                'completedAppointments' => collect(),
                'errorMessage' => 'Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu.',
            ]);
        }

        $appointments = Appointment::with(['doctor.user', 'doctor.specialization', 'schedule'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('created_at')
            ->get();

        $completedAppointments = $appointments->where('status', 'completed');

        return view('pasien.reservasi.history', compact('appointments', 'completedAppointments'));
    }

    public function show(Appointment $appointment)
    {
        $patient = Patient::where('user_id', auth()->id())->first();
        if (! $patient) {
            return redirect()->route('pasien.dashboard')
                ->with('error', 'Profil pasien tidak ditemukan. Silakan lengkapi data pasien terlebih dahulu.');
        }

        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        $appointment->load(['doctor.user', 'doctor.specialization', 'schedule', 'medicalRecord']);

        return view('pasien.reservasi.show', compact('appointment'));
    }
}
