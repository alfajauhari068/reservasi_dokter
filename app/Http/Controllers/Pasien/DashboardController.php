<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pasien']);
    }

    public function index()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        if (! $patient) {
            return view('pasien.dashboard', [
                'appointments' => collect(),
                'completedAppointments' => collect(),
                'completedCount' => 0,
                'activeCount' => 0,
                'totalCount' => 0,
                'patient' => null,
            ]);
        }

        $appointments = Appointment::with(['doctor.user', 'doctor.specialization', 'schedule'])
            ->where('patient_id', $patient->id)
            ->orderByDesc('appointment_date')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $completedAppointments = Appointment::with(['doctor.user', 'doctor.specialization', 'schedule'])
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->orderByDesc('appointment_date')
            ->limit(5)
            ->get();

        $activeCount = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $totalCount = Appointment::where('patient_id', $patient->id)->count();
        $completedCount = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->count();

        return view('pasien.dashboard', compact('appointments', 'completedAppointments', 'completedCount', 'activeCount', 'totalCount', 'patient'));
    }
}
