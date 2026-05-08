<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:dokter']);
    }

    public function index()
    {
        $doctor = Doctor::where('user_id', auth()->id())
            ->with(['appointments.patient.user', 'appointments.schedule'])
            ->first();

        $pendingAppointments = collect();
        $completedCount = 0;

        if ($doctor) {
            $pendingAppointments = $doctor->appointments()
                ->whereIn('status', ['pending', 'in_progress'])
                ->with(['patient.user', 'schedule'])
                ->orderBy('appointment_date')
                ->orderBy('queue_number')
                ->get();

            $completedCount = $doctor->appointments()
                ->where('status', 'completed')
                ->count();
        }

        return view('dokter.dashboard', compact('doctor', 'pendingAppointments', 'completedCount'));
    }
}
