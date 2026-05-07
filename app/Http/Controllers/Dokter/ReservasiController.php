<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\doctors;
use App\Models\medical_records;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:dokter']);
    }

    public function show(Appointment $appointment)
    {
        $doctor = doctors::where('user_id', auth()->id())->first();
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

        $doctor = doctors::where('user_id', auth()->id())->first();
        if (! $doctor) {
            return redirect()->route('dokter.dashboard')
                ->with('error', 'Profil dokter tidak ditemukan.');
        }

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Update or create medical record
        medical_records::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'diagnosis' => $request->diagnosis,
                'prescription' => $request->prescription,
                'doctor_notes' => $request->doctor_notes,
            ]
        );

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pemeriksaan selesai dan data tersimpan.');
    }

    public function history()
    {
        $doctor = doctors::where('user_id', auth()->id())
            ->with(['appointments.patient.user', 'appointments.schedule', 'appointments.medicalRecord'])
            ->first();

        if (! $doctor) {
            return view('dokter.reservasi.history', [
                'appointments' => collect(),
                'errorMessage' => 'Profil dokter tidak ditemukan.',
            ]);
        }

        $completedAppointments = $doctor->appointments()
            ->where('status', 'completed')
            ->with(['patient.user', 'schedule', 'medicalRecord'])
            ->orderByDesc('appointment_date')
            ->get();

        return view('dokter.reservasi.history', compact('completedAppointments'));
    }
}