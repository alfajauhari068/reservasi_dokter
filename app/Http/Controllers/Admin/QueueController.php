<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    /**
     * Menampilkan halaman antrian harian
     * Dashboard untuk mengelola antrian pasien hari ini
     */
    public function index()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Ambil data antrian hari ini
        $todayQueues = $this->getTodayQueues();

        // Statistik antrian hari ini
        $queueStats = $this->getQueueStats();

        return view('admin.queues.index', compact('todayQueues', 'queueStats'));
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
                'appointments.booking_code',
                'appointments.status as appointment_status'
            ])
            ->get()
            ->map(function($queue) {
                return [
                    'id' => $queue->id,
                    'queue_number' => $queue->queue_number,
                    'booking_code' => $queue->booking_code,
                    'patient_name' => $queue->appointment->patient->user->name,
                    'patient_id' => $queue->appointment->patient->id,
                    'doctor_name' => $queue->appointment->doctor->user->name,
                    'doctor_specialization' => $queue->appointment->doctor->specialization->name ?? 'N/A',
                    'appointment_time' => $queue->appointment->appointment_date,
                    'appointment_status' => $queue->appointment_status,
                    'queue_status' => $queue->queue_status,
                    'called_at' => $queue->called_at,
                    'served_at' => $queue->served_at,
                    'created_at' => $queue->created_at,
                ];
            });
    }

    /**
     * Mengambil statistik antrian hari ini
     */
    private function getQueueStats()
    {
        $today = today()->toDateString();

        $stats = DB::select("
            SELECT
                COUNT(*) as total_queues,
                SUM(CASE WHEN queue_status = 'waiting' THEN 1 ELSE 0 END) as waiting,
                SUM(CASE WHEN queue_status = 'called' THEN 1 ELSE 0 END) as called,
                SUM(CASE WHEN queue_status = 'served' THEN 1 ELSE 0 END) as served,
                SUM(CASE WHEN queue_status = 'skipped' THEN 1 ELSE 0 END) as skipped
            FROM queues q
            INNER JOIN appointments a ON q.appointment_id = a.id
            WHERE DATE(a.appointment_date) = ?
        ", [$today]);

        return $stats[0] ?? (object) [
            'total_queues' => 0,
            'waiting' => 0,
            'called' => 0,
            'served' => 0,
            'skipped' => 0
        ];
    }

    /**
     * Update status antrian (AJAX endpoint)
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:waiting,called,served,skipped'
        ]);

        $oldStatus = $queue->queue_status;
        $newStatus = $request->status;

        // Update status dan timestamp sesuai kebutuhan
        $updateData = ['queue_status' => $newStatus];

        if ($newStatus === 'called' && $oldStatus !== 'called') {
            $updateData['called_at'] = now();
        } elseif ($newStatus === 'served' && $oldStatus !== 'served') {
            $updateData['served_at'] = now();
        }

        $queue->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status antrian berhasil diperbarui',
            'queue' => $queue->fresh()
        ]);
    }

    /**
     * Reset antrian hari ini (untuk testing atau situasi darurat)
     */
    public function resetTodayQueues()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Reset semua antrian hari ini ke status waiting
        $affected = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->whereDate('appointments.appointment_date', today())
            ->where('queues.queue_status', '!=', 'waiting')
            ->update([
                'queues.queue_status' => 'waiting',
                'queues.called_at' => null,
                'queues.served_at' => null,
            ]);

        return redirect()->back()->with('success', "Berhasil reset {$affected} antrian hari ini");
    }

    /**
     * Generate nomor antrian untuk appointment yang belum memiliki queue
     */
    public function generateQueues()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Cari appointment hari ini yang belum memiliki queue
        $appointmentsWithoutQueue = Appointment::whereDate('appointment_date', today())
            ->whereDoesntHave('queue')
            ->where('status', 'approved') // Hanya yang sudah disetujui
            ->orderBy('appointment_date')
            ->get();

        $created = 0;
        $nextQueueNumber = $this->getNextQueueNumber();

        foreach ($appointmentsWithoutQueue as $appointment) {
            Queue::create([
                'appointment_id' => $appointment->id,
                'queue_number' => $nextQueueNumber++,
                'queue_status' => 'waiting'
            ]);
            $created++;
        }

        return redirect()->back()->with('success', "Berhasil generate {$created} nomor antrian baru");
    }

    /**
     * Mendapatkan nomor antrian berikutnya
     */
    private function getNextQueueNumber()
    {
        $today = today()->toDateString();

        $maxQueue = DB::select("
            SELECT MAX(q.queue_number) as max_queue
            FROM queues q
            INNER JOIN appointments a ON q.appointment_id = a.id
            WHERE DATE(a.appointment_date) = ?
        ", [$today]);

        return ($maxQueue[0]->max_queue ?? 0) + 1;
    }

    /**
     * API endpoint untuk real-time queue updates (untuk WebSocket atau polling)
     */
    public function getQueueUpdates()
    {
        // Pastikan user adalah admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $queues = $this->getTodayQueues();
        $stats = $this->getQueueStats();

        return response()->json([
            'queues' => $queues,
            'stats' => $stats,
            'last_updated' => now()->toISOString()
        ]);
    }
}
