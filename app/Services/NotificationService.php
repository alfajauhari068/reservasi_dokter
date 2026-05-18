<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\NewReservationCreatedForAdmin;
use App\Notifications\NewReservationForDoctor;
use App\Notifications\QueueStatusUpdatedForAdmin;
use App\Notifications\ScheduleChangedForDoctor;
use App\Notifications\ReservationApprovedForPatient;
use App\Notifications\ReservationCancelledForPatient;
use App\Notifications\QueueCalledForPatient;

class NotificationService
{
    /**
     * Notifikasi ke semua admin ketika ada reservasi baru
     */
    public function notifyAdminsNewReservation(Appointment $appointment): void
    {
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewReservationCreatedForAdmin($appointment));
        }
    }

    /**
     * Notifikasi ke dokter ketika ada reservasi baru untuk dia
     */
    public function notifyDoctorNewReservation(Appointment $appointment): void
    {
        $doctor = $appointment->doctor;
        if ($doctor && $doctor->user) {
            $doctor->user->notify(new NewReservationForDoctor($appointment));
        }
    }

    /**
     * Notifikasi ke admin ketika status antrian berubah
     */
    public function notifyAdminQueueStatusChanged(Queue $queue): void
    {
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        foreach ($admins as $admin) {
            $admin->notify(new QueueStatusUpdatedForAdmin($queue));
        }
    }

    /**
     * Notifikasi ke pasien ketika antrian dipanggil
     */
    public function notifyPatientQueueCalled(Queue $queue): void
    {
        $patient = $queue->appointment->patient;
        if ($patient && $patient->user) {
            $patient->user->notify(new QueueCalledForPatient($queue));
        }
    }

    /**
     * Notifikasi ke dokter ketika jadwal berubah
     */
    public function notifyDoctorScheduleChanged(Schedule $schedule): void
    {
        $doctor = $schedule->doctor;
        if ($doctor && $doctor->user) {
            $doctor->user->notify(new ScheduleChangedForDoctor($schedule));
        }
    }

    /**
     * Notifikasi ke pasien ketika reservasi disetujui
     */
    public function notifyPatientReservationApproved(Appointment $appointment): void
    {
        $patient = $appointment->patient;
        if ($patient && $patient->user) {
            $patient->user->notify(new ReservationApprovedForPatient($appointment));
        }
    }

    /**
     * Notifikasi ke pasien ketika reservasi dibatalkan
     */
    public function notifyPatientReservationCancelled(Appointment $appointment): void
    {
        $patient = $appointment->patient;
        if ($patient && $patient->user) {
            $patient->user->notify(new ReservationCancelledForPatient($appointment));
        }
    }

    /**
     * Menandai notifikasi sebagai telah dibaca
     */
    public function markAsRead($notificationId): void
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Menandai semua notifikasi sebagai telah dibaca
     */
    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    /**
     * Menghapus notifikasi
     */
    public function deleteNotification($notificationId): void
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }

    /**
     * Menghapus semua notifikasi
     */
    public function deleteAllNotifications(): void
    {
        auth()->user()->notifications()->delete();
    }

    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca
     */
    public function getUnreadCount(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    /**
     * Mendapatkan notifikasi terakhir (limit)
     */
    public function getLatestNotifications($limit = 10)
    {
        return auth()->user()->notifications()
            ->latest()
            ->limit($limit)
            ->get();
    }
}
