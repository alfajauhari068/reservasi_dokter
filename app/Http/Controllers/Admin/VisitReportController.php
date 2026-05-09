<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Exports\VisitReportExport;
use App\Services\VisitReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class VisitReportController extends Controller
{
    protected VisitReportService $visitReportService;

    public function __construct(VisitReportService $visitReportService)
    {
        $this->visitReportService = $visitReportService;
    }
    /**
     * Menampilkan halaman laporan kunjungan dengan form filter
     * dan hasil laporan berdasarkan filter yang dipilih
     */
    public function index(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // === FILTER PARAMETER HANDLING ===
        $doctorId = $request->get('doctor_id'); // Opsional: null berarti semua dokter
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status = $request->get('status');

        // Gunakan service untuk membangun query laporan yang reusable
        $query = $this->visitReportService->buildReportQuery($request);

        $reports = $query->select([
            'appointments.id',
            'appointments.appointment_date',
            'schedules.start_time as appointment_time',
            'appointments.booking_code',
            'appointments.complaint as symptoms',
            'appointments.status as appointment_status',
            'patient_users.name as patient_name',
            'doctor_users.name as doctor_name',
            'doctors.id as doctor_id',
            'specializations.name as specialization_name',
            'queues.queue_number',
            'queues.queue_status',
            'queues.called_at',
            'queues.served_at'
        ])
        ->orderBy('appointments.appointment_date', 'desc')
        ->orderBy('schedules.start_time', 'desc')
        ->get();

        // Hitung statistik
        $stats = $this->calculateStatistics($reports, $startDate, $endDate, $doctorId);

        // Ambil semua dokter untuk dropdown filter
        $doctors = Doctor::with('user', 'specialization')->get();

        // Status options untuk filter
        $statusOptions = [
            'waiting' => 'Menunggu',
            'called' => 'Dipanggil',
            'served' => 'Selesai',
            'skipped' => 'Dilewati'
        ];

        return view('admin.reports.visitation', compact(
            'reports',
            'stats',
            'doctors',
            'statusOptions',
            'doctorId',
            'startDate',
            'endDate',
            'status'
        ));
    }

    /**
     * Eksport laporan kunjungan ke PDF berdasarkan filter yang sama dengan index().
     */
    public function exportPdf(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = $this->visitReportService->buildReportQuery($request);

        $reports = $query->select([
            'appointments.id',
            'appointments.appointment_date',
            'schedules.start_time as appointment_time',
            'appointments.booking_code',
            'appointments.complaint as symptoms',
            'patient_users.name as patient_name',
            'doctor_users.name as doctor_name',
            'specializations.name as specialization_name',
            'queues.queue_number',
            'queues.queue_status',
            'queues.called_at',
            'queues.served_at'
        ])
        ->orderBy('appointments.appointment_date', 'desc')
        ->orderBy('schedules.start_time', 'desc')
        ->get();

        $filter = [
            'doctor_id' => $request->get('doctor_id'),
            'start_date' => $request->get('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->get('end_date', now()->endOfMonth()->format('Y-m-d')),
        ];

        $selectedDoctor = null;
        if ($filter['doctor_id']) {
            $selectedDoctor = Doctor::with('user', 'specialization')->find($filter['doctor_id']);
        }

        $pdf = Pdf::loadView('admin.reports.pdf.visit_report_pdf', compact('reports', 'filter', 'selectedDoctor'));

        $filename = sprintf('laporan-kunjungan-%s-%s.pdf', $filter['start_date'], $filter['end_date']);

        return $pdf->download($filename);
    }

    /**
     * Eksport laporan kunjungan ke Excel berdasarkan filter yang sama dengan index().
     */
    public function exportExcel(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $filename = sprintf('laporan-kunjungan-%s-%s.xlsx',
            $request->get('start_date', now()->startOfMonth()->format('Y-m-d')),
            $request->get('end_date', now()->endOfMonth()->format('Y-m-d'))
        );

        return Excel::download(new VisitReportExport($request), $filename);
    }

    /**
     * Generic export endpoint for the report form.
     * Defaults to Excel export for now.
     */
    public function export(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return $this->exportExcel($request);
    }

    /**
     * Menghitung statistik dari data laporan
     */
    private function calculateStatistics($reports, $startDate, $endDate, $doctorId = null)
    {
        $totalVisits = $reports->count();
        $completedVisits = $reports->filter(function ($report) {
            return $report->queue_status === 'served' || in_array($report->appointment_status, ['done', 'completed']);
        })->count();
        $pendingVisits = $reports->filter(function ($report) {
            return $report->queue_status === 'waiting' || $report->queue_status === 'called' || $report->appointment_status === 'pending';
        })->count();
        $cancelledVisits = $reports->filter(function ($report) {
            return $report->queue_status === 'skipped' || $report->appointment_status === 'cancelled';
        })->count();

        // Hitung rata-rata waktu tunggu (dalam menit)
        $avgWaitTime = 0;
        $servedReports = $reports->where('queue_status', 'served');
        if ($servedReports->count() > 0) {
            $totalWaitMinutes = 0;
            foreach ($servedReports as $report) {
                if ($report->called_at && $report->served_at) {
                    $calledTime = Carbon::parse($report->called_at);
                    $servedTime = Carbon::parse($report->served_at);
                    $totalWaitMinutes += $calledTime->diffInMinutes($servedTime);
                }
            }
            $avgWaitTime = $servedReports->count() > 0 ? round($totalWaitMinutes / $servedReports->count(), 1) : 0;
        }

        // Statistik per dokter: tampilkan semua dokter (atau hanya dokter yang dipilih)
        $doctorQuery = Doctor::with(['user', 'specialization']);
        if ($doctorId) {
            $doctorQuery->where('id', $doctorId);
        }

        $allDoctors = $doctorQuery->orderBy('id')->get();

        $doctorStats = $allDoctors->map(function ($doctor) use ($reports) {
            $doctorReports = $reports->where('doctor_id', $doctor->id);

            return [
                'total' => $doctorReports->count(),
                'completed' => $doctorReports->filter(function ($report) {
                    return $report->queue_status === 'served' || in_array($report->appointment_status, ['done', 'completed']);
                })->count(),
                'name' => $doctor->user->name,
                'specialization' => $doctor->specialization->name ?? 'Umum'
            ];
        })->sortByDesc('total')->values();

        return [
            'total_visits' => $totalVisits,
            'completed_visits' => $completedVisits,
            'pending_visits' => $pendingVisits,
            'cancelled_visits' => $cancelledVisits,
            'completion_rate' => $totalVisits > 0 ? round(($completedVisits / $totalVisits) * 100, 1) : 0,
            'avg_wait_time' => $avgWaitTime,
            'doctor_stats' => $doctorStats,
            'period' => [
                'start' => Carbon::parse($startDate)->format('d/m/Y'),
                'end' => Carbon::parse($endDate)->format('d/m/Y')
            ]
        ];
    }

    /**
     * Mengecek apakah preset periode sedang aktif
     */
    private function isPresetActive($preset)
    {
        $startDate = request('start_date');
        $endDate = request('end_date');

        if (!$startDate || !$endDate) {
            return false;
        }

        switch ($preset) {
            case 'week':
                return $startDate === now()->startOfWeek()->format('Y-m-d') &&
                       $endDate === now()->endOfWeek()->format('Y-m-d');
            case 'month':
                return $startDate === now()->startOfMonth()->format('Y-m-d') &&
                       $endDate === now()->endOfMonth()->format('Y-m-d');
            case 'year':
                return $startDate === now()->startOfYear()->format('Y-m-d') &&
                       $endDate === now()->endOfYear()->format('Y-m-d');
            case '30days':
                return $startDate === now()->subDays(30)->format('Y-m-d') &&
                       $endDate === now()->format('Y-m-d');
            default:
                return false;
        }
    }
}