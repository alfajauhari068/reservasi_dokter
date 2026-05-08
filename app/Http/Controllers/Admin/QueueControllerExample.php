<?php

/*
    CONTOH PENGGUNAAN TABEL ANTRIAN DI CONTROLLER
    Tambahkan method ini di QueueController atau DashboardController
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    /**
     * CONTOH: Menampilkan tabel antrian harian sederhana
     * Method ini bisa ditambahkan ke QueueController atau DashboardController
     */
    public function dailyQueueTable()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil data antrian hari ini dengan eager loading
        $queues = Queue::with([
                'appointment.patient.user',
                'appointment.doctor.user',
                'appointment.doctor.specialization'
            ])
            ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->whereDate('appointments.appointment_date', today())
            ->orderBy('queues.queue_number')
            ->get();

        // Kirim data ke view
        return view('admin.queues.table_example', compact('queues'));
    }

    /**
     * CONTOH DENGAN PAGINATION DAN PENCARIAN
     * Menampilkan tabel antrian harian dengan fitur search dan pagination
     */
    public function dailyQueueTableWithSearch(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil parameter search dari request
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10); // Default 10 data per halaman

        // Query dasar untuk antrian hari ini
        $query = Queue::with([
                'appointment.patient.user',
                'appointment.doctor.user',
                'appointment.doctor.specialization'
            ])
            ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('users as patient_users', 'patients.user_id', '=', 'patient_users.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
            ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
            ->whereDate('appointments.appointment_date', today())
            ->select([
                'queues.*',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.booking_code',
                'patient_users.name as patient_name',
                'doctor_users.name as doctor_name',
                'specializations.name as specialization_name'
            ]);

        // Terapkan filter pencarian jika ada
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('patient_users.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('doctor_users.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('appointments.booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhere('queues.queue_number', 'LIKE', '%' . $search . '%');
            });
        }

        // Urutkan berdasarkan nomor antrian dan terapkan pagination
        $queues = $query->orderBy('queues.queue_number')
                        ->paginate($perPage)
                        ->appends($request->query()); // Pertahankan parameter query di pagination

        // Kirim data ke view dengan parameter pencarian
        return view('admin.queues.table_example', compact('queues', 'search', 'perPage'));
    }

    /**
     * CONTOH ALTERNATIF: Menggunakan query builder untuk performa lebih baik
     * Dengan pagination dan search
     */
    public function dailyQueueTableOptimizedWithSearch(Request $request)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Parameter dari request
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        // Query dengan JOIN untuk performa optimal
        $query = DB::table('queues as q')
            ->join('appointments as a', 'q.appointment_id', '=', 'a.id')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->join('users as pu', 'p.user_id', '=', 'pu.id') // patient user
            ->join('doctors as d', 'a.doctor_id', '=', 'd.id')
            ->join('users as du', 'd.user_id', '=', 'du.id') // doctor user
            ->leftJoin('specializations as s', 'd.specialization_id', '=', 's.id')
            ->whereDate('a.appointment_date', today())
            ->select([
                'q.id',
                'q.queue_number',
                'q.queue_status',
                'pu.name as patient_name',
                'du.name as doctor_name',
                's.name as specialization_name',
                'a.appointment_date',
                'a.appointment_time',
                'a.booking_code'
            ]);

        // Filter pencarian
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('pu.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('du.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('a.booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhere('q.queue_number', 'LIKE', '%' . $search . '%');
            });
        }

        // Pagination
        $queues = $query->orderBy('q.queue_number')
                        ->paginate($perPage)
                        ->appends($request->query());

        // Convert ke object untuk kompatibilitas dengan view
        $queues->getCollection()->transform(function ($item) {
            $item->appointment = (object) [
                'appointment_time' => $item->appointment_time,
                'appointment_date' => $item->appointment_date,
                'booking_code' => $item->booking_code,
                'patient' => (object) [
                    'user' => (object) ['name' => $item->patient_name]
                ],
                'doctor' => (object) [
                    'user' => (object) ['name' => $item->doctor_name],
                    'specialization' => (object) ['name' => $item->specialization_name]
                ]
            ];
            return $item;
        });

        return view('admin.queues.table_example', compact('queues', 'search', 'perPage'));
    }

    /**
     * CONTOH SEDERHANA: Menggunakan query builder tanpa pagination dan search
     */
    public function dailyQueueTableSimple()
    {
        // Query dengan JOIN untuk mengambil semua data dalam satu query
        $queues = DB::table('queues as q')
            ->join('appointments as a', 'q.appointment_id', '=', 'a.id')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->join('users as pu', 'p.user_id', '=', 'pu.id') // patient user
            ->join('doctors as d', 'a.doctor_id', '=', 'd.id')
            ->join('users as du', 'd.user_id', '=', 'du.id') // doctor user
            ->leftJoin('specializations as s', 'd.specialization_id', '=', 's.id')
            ->whereDate('a.appointment_date', today())
            ->orderBy('q.queue_number')
            ->select([
                'q.id',
                'q.queue_number',
                'q.queue_status',
                'pu.name as patient_name',
                'du.name as doctor_name',
                's.name as specialization_name',
                'a.appointment_date',
                'a.appointment_time',
                'a.booking_code'
            ])
            ->get();

        // Kirim data ke view
        return view('admin.queues.table_example', compact('queues'));
    }
}