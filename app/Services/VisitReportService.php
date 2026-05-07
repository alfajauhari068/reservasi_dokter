<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VisitReportService
{
    /**
     * Membangun query appointment untuk laporan kunjungan.
     * Filter dokter opsional dan periode tanggal wajib.
     */
    public function buildReportQuery(Request $request): Builder
    {
        $doctorId = $request->get('doctor_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status = $request->get('status');

        $query = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.specialization',
            'queue'
        ])
        ->join('queues', 'appointments.id', '=', 'queues.appointment_id')
        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
        ->join('users as patient_users', 'patients.user_id', '=', 'patient_users.id')
        ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
        ->join('schedules', 'appointments.schedule_id', '=', 'schedules.id')
        ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
        ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
        ->whereBetween('appointments.appointment_date', [$startDate, $endDate]);

        if ($doctorId) {
            $query->where('appointments.doctor_id', $doctorId);
        }

        if ($status) {
            $query->where('queues.queue_status', $status);
        }

        return $query;
    }

    /**
     * Mengembalikan data filter yang sudah dihitung dengan default.
     */
    public function getFilterValues(Request $request): array
    {
        return [
            'doctor_id' => $request->get('doctor_id'),
            'start_date' => $request->get('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->get('end_date', now()->endOfMonth()->format('Y-m-d')),
            'status' => $request->get('status'),
        ];
    }
}
