<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FilterExampleController extends Controller
{
    /**
     * CONTOH: Method untuk halaman laporan dengan form filter lengkap
     * Fokus pada dropdown dokter dan date range
     */
    public function indexWithFilterExample(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil parameter filter dari request
        $doctorId = $request->get('doctor_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set default periode jika tidak ada filter tanggal
        if (!$startDate && !$endDate) {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        }

        // Ambil data dokter untuk dropdown
        // Pastikan menggunakan eager loading untuk performa
        $doctors = Doctor::with(['user', 'specialization'])
                        ->orderBy('users.name')
                        ->join('users', 'doctors.user_id', '=', 'users.id')
                        ->select('doctors.*')
                        ->get();

        // Query untuk mengambil data laporan (jika diperlukan)
        $query = Appointment::with([
                'patient.user',
                'doctor.user',
                'doctor.specialization',
                'queue'
            ])
            ->join('queues', 'appointments.id', '=', 'queues.appointment_id')
            ->whereBetween('appointments.appointment_date', [$startDate, $endDate]);

        // Terapkan filter dokter jika dipilih
        if ($doctorId) {
            $query->where('appointments.doctor_id', $doctorId);
        }

        // Ambil data laporan
        $reports = $query->orderBy('appointments.appointment_date', 'desc')
                         ->limit(100) // Limit untuk performa
                         ->get();

        // Siapkan data untuk view
        $filterData = [
            'doctor_id' => $doctorId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'date_range_formatted' => $this->formatDateRange($startDate, $endDate),
            'is_filtered' => $request->hasAny(['doctor_id', 'start_date', 'end_date']),
            'total_days' => $this->calculateDaysDifference($startDate, $endDate)
        ];

        return view('admin.reports.filter_example', compact(
            'doctors',
            'reports',
            'filterData'
        ));
    }

    /**
     * CONTOH: Method khusus untuk menangani form filter saja
     * Tanpa query data laporan (untuk halaman form filter murni)
     */
    public function filterFormOnly(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil data dokter untuk dropdown
        $doctors = Doctor::with(['user', 'specialization'])
                        ->orderBy('users.name')
                        ->join('users', 'doctors.user_id', '=', 'users.id')
                        ->select('doctors.*')
                        ->get();

        // Siapkan data filter yang sudah dipilih
        $currentFilters = [
            'doctor_id' => $request->get('doctor_id'),
            'start_date' => $request->get('start_date', now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->get('end_date', now()->endOfMonth()->format('Y-m-d')),
            'has_filters' => $request->hasAny(['doctor_id', 'start_date', 'end_date'])
        ];

        // Tambahkan info dokter yang dipilih (jika ada)
        if ($currentFilters['doctor_id']) {
            $selectedDoctor = $doctors->find($currentFilters['doctor_id']);
            $currentFilters['selected_doctor'] = $selectedDoctor ? [
                'name' => $selectedDoctor->user->name,
                'specialization' => $selectedDoctor->specialization?->name
            ] : null;
        }

        return view('admin.reports.filter_form_only', compact(
            'doctors',
            'currentFilters'
        ));
    }

    /**
     * Helper: Format date range untuk display
     */
    private function formatDateRange($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return null;
        }

        $start = Carbon::parse($startDate)->format('d/m/Y');
        $end = Carbon::parse($endDate)->format('d/m/Y');

        return $start . ' - ' . $end;
    }

    /**
     * Helper: Hitung selisih hari
     */
    private function calculateDaysDifference($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return 0;
        }

        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
    }

    /**
     * Helper: Cek apakah preset periode sedang aktif
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