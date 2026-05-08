<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display list of pending appointments waiting for approval
     */
    public function index()
    {
        $pendingAppointments = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'schedule'
        ])
            ->where('approval_status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.approvals.index', compact('pendingAppointments'));
    }

    /**
     * Show detail of a pending appointment
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->approval_status !== 'pending') {
            return redirect()->route('admin.approvals.index')
                ->with('info', 'Reservasi ini sudah diproses.');
        }

        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'schedule'
        ]);

        return view('admin.approvals.show', compact('appointment'));
    }

    /**
     * Approve pending appointment
     */
    public function approve(Appointment $appointment)
    {
        if ($appointment->approval_status !== 'pending') {
            return redirect()->route('admin.approvals.index')
                ->with('info', 'Reservasi ini sudah diproses.');
        }

        $appointment->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Reservasi berhasil disetujui. Data akan ditampilkan di dashboard dokter.');
    }

    /**
     * Reject pending appointment
     */
    public function reject(Request $request, Appointment $appointment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        if ($appointment->approval_status !== 'pending') {
            return redirect()->route('admin.approvals.index')
                ->with('info', 'Reservasi ini sudah diproses.');
        }

        $appointment->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Reservasi berhasil ditolak. Pasien akan diberitahu alasan penolakan.');
    }
}
