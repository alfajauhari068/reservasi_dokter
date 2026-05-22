<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display reservation history for admin with filter support.
     */
    public function index(Request $request)
    {
        $doctors = Doctor::with('user')->orderBy('id')->get();
        $specializations = Specialization::orderBy('name')->get();
        $statuses = ['pending', 'approved', 'in_progress', 'completed', 'cancelled'];

        $query = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'schedule'
        ]);

        if ($request->filled('patient')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient . '%');
            });
        }

        if ($request->filled('doctor')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor . '%');
            });
        }

        if ($request->filled('specialization')) {
            $query->whereHas('doctor.specialization', function ($q) use ($request) {
                $q->where('id', $request->specialization);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period') && $request->period !== 'custom') {
            if ($request->period === 'today') {
                $query->whereDate('appointment_date', today());
            } elseif ($request->period === 'this_week') {
                $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->period === 'this_month') {
                $query->whereBetween('appointment_date', [now()->startOfMonth(), now()->endOfMonth()]);
            }
        }

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $fromDate = $request->filled('from_date') ? $request->from_date : now()->subYears(10)->format('Y-m-d');
            $toDate = $request->filled('to_date') ? $request->to_date : now()->format('Y-m-d');
            $query->whereBetween('appointment_date', [$fromDate, $toDate]);
        }

        $appointments = $query->orderByDesc('appointment_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        $appointments->appends($request->query());

        return view('admin.approvals.index', compact(
            'appointments',
            'doctors',
            'specializations',
            'statuses'
        ));
    }

    /**
     * Show detail of a reservation history record.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'schedule',
            'medicalRecord'
        ]);

        return view('admin.approvals.show', compact('appointment'));
    }

    /**
     * Export reservation history detail to PDF.
     */
    public function print(Appointment $appointment)
    {
        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'schedule',
            'medicalRecord'
        ]);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.approvals.pdf', compact('appointment'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('riwayat-pemeriksaan-' . $appointment->booking_code . '.pdf');
    }
}
