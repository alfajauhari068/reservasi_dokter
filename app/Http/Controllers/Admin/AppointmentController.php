<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\Schedule;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * Display a list of appointments created by admin.
     */
    public function index()
    {
        $appointments = Appointment::with([
                'patient.user',
                'doctor.user',
                'schedule'
            ])
            ->orderByDesc('appointment_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.appointments.index', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Display form to create a new appointment (admin-made).
     */
    public function create()
    {
        $this->middleware(['auth', 'role:admin']);

        $patients = Patient::with('user')
            ->orderBy('id')
            ->get()
            ->map(function (Patient $patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->user->name ?? $patient->full_name ?? ('Pasien #' . $patient->id),
                ];
            });

        $specializations = Specialization::orderBy('name')->get();

        $doctors = Doctor::with(['user', 'specialization'])
            ->orderBy('id')
            ->get()
            ->map(function (Doctor $doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name ?? ('Dokter #' . $doctor->id),
                    'specialization_id' => $doctor->specialization?->id,
                    'specialization_name' => $doctor->specialization?->name ?? 'Umum',
                ];
            });

        $schedules = Schedule::with('doctor.user')
            ->orderBy('id')
            ->get();

        return view('admin.appointments.create', [
            'patients' => $patients,
            'specializations' => $specializations,
            'doctors' => $doctors,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Store a newly created appointment (admin-made).
     */
    public function store(Request $request)
    {
        $this->middleware(['auth', 'role:admin']);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'specialization_id' => 'nullable|exists:specializations,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date',
            'complaint' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patientId = $request->input('patient_id');
        $bookingCode = $this->generateUniqueBookingCode();

        DB::beginTransaction();
        try {
            $appointment = Appointment::create([
                'patient_id' => $patientId,
                'doctor_id' => $request->doctor_id,
                'schedule_id' => $request->schedule_id,
                'appointment_date' => $request->appointment_date,
                'complaint' => $request->complaint,
                'notes' => $request->notes,
                'booking_code' => $bookingCode,

                // Workflow khusus appointment buatan admin.
                'status' => 'in_progress',
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $doctor = Doctor::find($request->doctor_id);
            $specializationId = $doctor?->specialization_id;
            $nextQueueNumber = Queue::nextNumberForSpecialization($specializationId, $request->appointment_date);

            $appointment->update([
                'queue_number' => $nextQueueNumber,
                'queue_date' => $request->appointment_date,
            ]);

            Queue::create([
                'appointment_id' => $appointment->id,
                'queue_number' => $nextQueueNumber,
                'queue_status' => 'waiting',
            ]);

            DB::commit();

            app(\App\Services\NotificationService::class)->notifyDoctorNewReservation($appointment);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment berhasil dibuat');
    }

    private function generateUniqueBookingCode(): string
    {
        do {
            $candidate = strtoupper('BK' . date('Ymd') . Str::random(6));
        } while (Appointment::where('booking_code', $candidate)->exists());

        return $candidate;
    }
}

