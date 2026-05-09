<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Queue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin dengan statistik lengkap hari ini
     * - Total reservasi per status (pending, approved, done, cancelled)
     * - Statistik per dokter hari ini
     * - Tabel antrian harian
     */
    public function index()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // === STATISTIK TOTAL RESERVASI HARI INI PER STATUS ===
        // Single query dengan conditional aggregation untuk performa optimal
        $statsQuery = Appointment::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            ")
            ->whereDate('created_at', today())
            ->first();

        // Struktur data statistik status
        $todayStats = [
            'total' => $statsQuery->total ?? 0,
            'pending' => $statsQuery->pending ?? 0,
            'approved' => $statsQuery->approved ?? 0,
            'done' => $statsQuery->done ?? 0,
            'cancelled' => $statsQuery->cancelled ?? 0,
        ];

        // === STATISTIK PER DOKTER HARI INI ===
        // Coba ambil dari hari ini, jika kosong ambil dari semua appointment yang approved/done
        $doctorStatsQuery = Appointment::selectRaw('
                doctors.id,
                users.name as doctor_name,
                specializations.name as specialization_name,
                COUNT(appointments.id) as total_appointments
            ')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
            ->whereDate('appointments.appointment_date', today())
            ->groupBy('doctors.id', 'users.name', 'specializations.name')
            ->orderBy('total_appointments', 'desc')
            ->get();

        // Jika tidak ada data hari ini, ambil dari 30 hari terakhir dengan status approved/done
        if ($doctorStatsQuery->isEmpty()) {
            $doctorStatsQuery = Appointment::selectRaw('
                    doctors.id,
                    users.name as doctor_name,
                    specializations.name as specialization_name,
                    COUNT(appointments.id) as total_appointments
                ')
                ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
                ->whereIn('appointments.status', ['approved', 'done'])
                ->whereDate('appointments.created_at', '>=', today()->subDays(30))
                ->groupBy('doctors.id', 'users.name', 'specializations.name')
                ->orderBy('total_appointments', 'desc')
                ->get();
        }

        // Jika masih kosong, tampilkan semua dokter dengan jumlah appointment (semua waktu)
        if ($doctorStatsQuery->isEmpty()) {
            $doctorStatsQuery = Doctor::selectRaw('
                    doctors.id,
                    users.name as doctor_name,
                    specializations.name as specialization_name,
                    COUNT(appointments.id) as total_appointments
                ')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
                ->leftJoin('appointments', 'doctors.id', '=', 'appointments.doctor_id')
                ->groupBy('doctors.id', 'users.name', 'specializations.name')
                ->orderBy('total_appointments', 'desc')
                ->get();
        }

        // Strukturkan data statistik dokter
        $doctorStats = $doctorStatsQuery->map(function($stat) {
            return [
                'id' => $stat->id,
                'name' => $stat->doctor_name,
                'specialization' => $stat->specialization_name ?? 'N/A',
                'total_appointments' => $stat->total_appointments,
            ];
        });

        // === TABEL ANTRIAN HARIAN ===
        // Query efisien untuk data antrian hari ini
        $dailyQueues = $this->getTodayQueues();

        // === PERMOHONAN RESERVASI MENUNGGU PERSETUJUAN ADMIN ===
        $pendingApprovalsCount = Appointment::where('approval_status', 'pending')->count();

        // Return view dengan semua data statistik
        return view('admin.dashboard', compact('todayStats', 'doctorStats', 'dailyQueues', 'pendingApprovalsCount'));
    }

    /**
     * Mengambil data antrian pasien hari ini
     * Menggunakan eager loading untuk performa optimal
     */
    private function getTodayQueues()
    {
        return Queue::with([
                'appointment.patient.user',
                'appointment.doctor.user',
                'appointment.doctor.specialization'
            ])
            ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->whereDate('appointments.appointment_date', today())
            ->orderBy('queues.queue_number')
            ->select([
                'queues.*',
                'appointments.appointment_date',
                'appointments.booking_code'
            ])
            ->get()
            ->map(function($queue) {
                return [
                    'queue_number' => $queue->queue_number,
                    'booking_code' => $queue->booking_code,
                    'patient_name' => $queue->appointment->patient->user->name,
                    'doctor_name' => $queue->appointment->doctor->user->name,
                    'doctor_specialization' => $queue->appointment->doctor->specialization->name ?? 'N/A',
                    'appointment_time' => $queue->appointment->appointment_date,
                    'queue_status' => $queue->queue_status,
                    'called_at' => $queue->called_at,
                    'served_at' => $queue->served_at,
                ];
            });
    }

    /**
     * ALTERNATIF: Query menggunakan Appointment sebagai base
     * (jika ingin memastikan semua appointment memiliki queue)
     */
    private function getTodayQueuesAlternative()
    {
        return Appointment::whereDate('appointment_date', today())
            ->whereHas('queue') // Pastikan memiliki queue
            ->with([
                'patient.user',
                'doctor.user',
                'doctor.specialization',
                'queue'
            ])
            ->orderBy('appointment_date')
            ->get()
            ->map(function($appointment) {
                return [
                    'queue_number' => $appointment->queue->queue_number,
                    'booking_code' => $appointment->booking_code,
                    'patient_name' => $appointment->patient->user->name,
                    'doctor_name' => $appointment->doctor->user->name,
                    'doctor_specialization' => $appointment->doctor->specialization->name ?? 'N/A',
                    'appointment_time' => $appointment->appointment_date,
                    'queue_status' => $appointment->queue->queue_status,
                    'called_at' => $appointment->queue->called_at,
                    'served_at' => $appointment->queue->served_at,
                ];
            });
    }

    /**
     * ALTERNATIF: Query dengan raw SQL untuk performa maksimal
     */
    private function getTodayQueuesRaw()
    {
        $today = today()->toDateString();

        return \DB::select("
            SELECT
                q.queue_number,
                a.booking_code,
                pu.name as patient_name,
                du.name as doctor_name,
                s.name as doctor_specialization,
                a.appointment_date,
                q.queue_status,
                q.called_at,
                q.served_at
            FROM queues q
            INNER JOIN appointments a ON q.appointment_id = a.id
            INNER JOIN patients p ON a.patient_id = p.id
            INNER JOIN users pu ON p.user_id = pu.id
            INNER JOIN doctors d ON a.doctor_id = d.id
            INNER JOIN users du ON d.user_id = du.id
            LEFT JOIN specializations s ON d.specialization_id = s.id
            WHERE DATE(a.appointment_date) = ?
            ORDER BY q.queue_number
        ", [$today]);
    }

    /**
     * Halaman Laporan Kunjungan
     */
    public function visitationReport()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil semua dokter untuk filter
        $doctors = Doctor::with('user')->get();

        return view('admin.reports.visitation', compact('doctors'));
    }

    /**
     * Generate Laporan Kunjungan
     */
    public function generateVisitationReport(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $doctorId = $request->doctor_id;

        // Query dasar
        $query = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['patient.user', 'doctor.user', 'doctor.specialization']);

        // Filter berdasarkan dokter jika dipilih
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get();

        // Hitung statistik
        $report = [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'doctor' => $doctorId ? Doctor::find($doctorId)->user->name : 'Semua Dokter',
            ],
            'statistics' => [
                'total_appointments' => $appointments->count(),
                'completed' => $appointments->where('status', 'done')->count(),
                'cancelled' => $appointments->where('status', 'cancelled')->count(),
                'pending' => $appointments->where('status', 'pending')->count(),
                'approved' => $appointments->where('status', 'approved')->count(),
            ],
            'details' => $appointments->map(function($appointment) {
                return [
                    'booking_code' => $appointment->booking_code,
                    'appointment_date' => $appointment->appointment_date,
                    'patient_name' => $appointment->patient->user->name,
                    'doctor_name' => $appointment->doctor->user->name,
                    'specialization' => $appointment->doctor->specialization->name ?? 'N/A',
                    'status' => $appointment->status,
                    'complaint' => $appointment->complaint,
                ];
            }),
        ];

        return view('admin.reports.visitation', compact('report', 'doctors'));
    }
}
