<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:dokter']);
    }

    public function show(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $appointment->load(['patient.user', 'schedule', 'medicalRecord']);

        return view('dokter.reservasi.show', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'diagnosis' => 'required|string|max:2000',
            'prescription' => 'nullable|string|max:2000',
            'doctor_notes' => 'nullable|string|max:2000',
        ]);

        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (! $doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Update or create medical record
        MedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'diagnosis' => $request->diagnosis,
                'prescription' => $request->prescription,
                'doctor_notes' => $request->doctor_notes,
            ]
        );

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        // Update queue status ketika pemeriksaan sudah selesai
        if ($appointment->queue) {
            $appointment->queue->update([
                'queue_status' => 'served',
                'called_at' => $appointment->queue->called_at ?? now(),
                'served_at' => now(),
            ]);
        }

        return redirect()->route('dokter.dashboard')->with('success', 'Pemeriksaan selesai dan data tersimpan.');
    }

    public function history(Request $request)
    {
        $doctor = Doctor::where('user_id', auth()->id())
            ->with(['appointments.patient.user', 'appointments.schedule', 'appointments.medicalRecord'])
            ->first();

        $completedAppointments = collect();
        $errorMessage = null;

        if (! $doctor) {
            $errorMessage = 'Profil dokter tidak ditemukan.';
        } else {
            $query = $doctor->appointments()
                ->where('status', 'completed')
                ->with(['patient.user', 'schedule', 'medicalRecord']);

            // Filter berdasarkan pencarian nama pasien
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('full_name', 'like', '%' . $search . '%')
                      ->orWhereHas('user', function ($userQ) use ($search) {
                          $userQ->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            // Filter berdasarkan tanggal
            if ($request->filled('date_from')) {
                $query->whereDate('appointment_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('appointment_date', '<=', $request->date_to);
            }

            $completedAppointments = $query->orderByDesc('appointment_date')->get();
        }

        return view('dokter.reservasi.history', compact('completedAppointments', 'errorMessage'));
    }

    public function printPdf(Appointment $appointment)
    {
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (!$doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $appointment->load(['patient.user', 'schedule', 'medicalRecord']);

        $pdf = app('dompdf.wrapper')->loadView('dokter.reservasi.pdf', compact('appointment'));

        return $pdf->download('data-pasien-' . $appointment->patient->full_name . '.pdf');
    }
}