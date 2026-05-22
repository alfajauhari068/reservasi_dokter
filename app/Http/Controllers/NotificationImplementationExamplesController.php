<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Schedule;
use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * CONTOH IMPLEMENTASI NOTIFIKASI
 * 
 * File ini menunjukkan cara menggunakan NotificationService
 * untuk mengirim notifikasi pada berbagai event.
 * 
 * CATATAN: Salin dan modifikasi bagian yang relevan ke controller asli Anda.
 */
class NotificationImplementationExamplesController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * ============================================
     * CONTOH 1: MEMBUAT RESERVASI BARU
     * ============================================
     * 
     * Tempat: AppointmentsController@store()
     * Event: Setelah appointment berhasil dibuat
     * 
     * @example
     * public function store(Request $request)
     * {
     *     $validated = $request->validate([...]);
     *     $appointment = Appointment::create($validated);
     * 
     *     // Kirim notifikasi
     *     $notificationService = app(NotificationService::class);
     *     $notificationService->notifyAdminsNewReservation($appointment);
     *     $notificationService->notifyDoctorNewReservation($appointment);
     * 
     *     return redirect(...)->with('success', 'Reservasi berhasil dibuat');
     * }
     */
    public function exampleCreateAppointment(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'doctor_id'       => 'required|exists:doctors,id',
            'schedule_id'     => 'required|exists:schedules,id',
            'appointment_date' => 'required|date',
            'complaint'       => 'required|string',
        ]);

        // Buat appointment
        $appointment = Appointment::create($validated);

        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyAdminsNewReservation($appointment);
        $this->notificationService->notifyDoctorNewReservation($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'appointment' => $appointment,
        ]);
    }

    /**
     * ============================================
     * CONTOH 2: MENYETUJUI RESERVASI
     * ============================================
     * 
     * Tempat: AppointmentsController@approve() atau sejenisnya
     * Event: Admin menyetujui reservasi
     * 
     * @example
     * public function approve(Appointment $appointment)
     * {
     *     $appointment->update([
     *         'approval_status' => 'approved',
     *         'approved_by' => auth()->id(),
     *         'approved_at' => now(),
     *     ]);
     * 
     *     // ✅ KIRIM NOTIFIKASI KE PASIEN
     *     $notificationService = app(NotificationService::class);
     *     $notificationService->notifyPatientReservationApproved($appointment);
     * 
     *     return redirect(...)->with('success', 'Reservasi disetujui');
     * }
     */
    public function exampleApproveAppointment(Appointment $appointment)
    {
        $appointment->update([
            'approval_status' => 'approved',
            'approved_by'     => auth()->id(),
            'approved_at'     => now(),
        ]);

        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyPatientReservationApproved($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi disetujui',
        ]);
    }

    /**
     * ============================================
     * CONTOH 3: MENOLAK/MEMBATALKAN RESERVASI
     * ============================================
     * 
     * Tempat: AppointmentsController@reject() atau sejenisnya
     * Event: Admin menolak atau membatalkan reservasi
     * 
     * @example
     * public function reject(Request $request, Appointment $appointment)
     * {
     *     $appointment->update([
     *         'approval_status' => 'rejected',
     *         'rejection_reason' => $request->reason,
     *         'approved_by' => auth()->id(),
     *         'approved_at' => now(),
     *     ]);
     * 
     *     // ✅ KIRIM NOTIFIKASI KE PASIEN
     *     $notificationService = app(NotificationService::class);
     *     $notificationService->notifyPatientReservationCancelled($appointment);
     * 
     *     return redirect(...)->with('success', 'Reservasi ditolak');
     * }
     */
    public function exampleRejectAppointment(Request $request, Appointment $appointment)
    {
        $request->validate(['reason' => 'nullable|string']);

        $appointment->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->reason,
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
        ]);

        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyPatientReservationCancelled($appointment);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi ditolak',
        ]);
    }

    /**
     * ============================================
     * CONTOH 4: MENGUBAH STATUS ANTRIAN
     * ============================================
     * 
     * Tempat: QueuesController@updateStatus() atau sejenisnya
     * Event: Status antrian berubah (waiting → called → served)
     * 
     * @example
     * public function updateStatus(Request $request, Queue $queue)
     * {
     *     $validated = $request->validate([
     *         'queue_status' => 'required|in:waiting,called,served,skipped',
     *     ]);
     * 
     *     $oldStatus = $queue->queue_status;
     *     $queue->update(['queue_status' => $validated['queue_status']]);
     * 
     *     // ✅ KIRIM NOTIFIKASI KE ADMIN TENTANG PERUBAHAN STATUS
     *     $notificationService = app(NotificationService::class);
     *     $notificationService->notifyAdminQueueStatusChanged($queue);
     * 
     *     // ✅ JIKA DIPANGGIL, NOTIFIKASI KE PASIEN
     *     if ($validated['queue_status'] === 'called') {
     *         $notificationService->notifyPatientQueueCalled($queue);
     *     }
     * 
     *     return redirect(...)->with('success', 'Status antrian diperbarui');
     * }
     */
    public function exampleUpdateQueueStatus(Request $request, Queue $queue)
    {
        $validated = $request->validate([
            'queue_status' => 'required|in:waiting,called,served,skipped',
        ]);

        $queue->update(['queue_status' => $validated['queue_status']]);

        // ✅ KIRIM NOTIFIKASI KE ADMIN
        $this->notificationService->notifyAdminQueueStatusChanged($queue);

        // ✅ JIKA DIPANGGIL, KIRIM KE PASIEN JUGA
        if ($validated['queue_status'] === 'called') {
            $this->notificationService->notifyPatientQueueCalled($queue);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status antrian diperbarui',
        ]);
    }

    /**
     * ============================================
     * CONTOH 5: MENGUBAH JADWAL DOKTER
     * ============================================
     * 
     * Tempat: SchedulesController@update()
     * Event: Jadwal praktik dokter diubah
     * 
     * @example
     * public function update(Request $request, Schedule $schedule)
     * {
     *     $validated = $request->validate([
     *         'day_of_week' => 'required|string',
     *         'start_time' => 'required|date_format:H:i',
     *         'end_time' => 'required|date_format:H:i',
     *         'quota' => 'required|integer|min:1',
     *     ]);
     * 
     *     $schedule->update($validated);
     * 
     *     // ✅ KIRIM NOTIFIKASI KE DOKTER
     *     $notificationService = app(NotificationService::class);
     *     $notificationService->notifyDoctorScheduleChanged($schedule);
     * 
     *     return redirect(...)->with('success', 'Jadwal diperbarui');
     * }
     */
    public function exampleUpdateSchedule(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|string',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i',
            'quota'       => 'required|integer|min:1',
        ]);

        $schedule->update($validated);

        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyDoctorScheduleChanged($schedule);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal diperbarui',
        ]);
    }

    /**
     * ============================================
     * INTEGRASI LANGSUNG DI MODEL (ALTERNATIF)
     * ============================================
     * 
     * Jika Anda lebih suka menggunakan Model Observers,
     * Anda bisa membuat observer untuk mengirim notifikasi otomatis.
     * 
     * @example Buat AppointmentObserver:
     * 
     * class AppointmentObserver
     * {
     *     public function created(Appointment $appointment)
     *     {
     *         $notificationService = app(NotificationService::class);
     *         $notificationService->notifyAdminsNewReservation($appointment);
     *         $notificationService->notifyDoctorNewReservation($appointment);
     *     }
     * 
     *     public function updated(Appointment $appointment)
     *     {
     *         if ($appointment->isDirty('approval_status')) {
     *             if ($appointment->approval_status === 'approved') {
     *                 $notificationService = app(NotificationService::class);
     *                 $notificationService->notifyPatientReservationApproved($appointment);
     *             }
     *         }
     *     }
     * }
     * 
     * Kemudian daftar di AppServiceProvider:
     * Appointment::observe(AppointmentObserver::class);
     */
}
