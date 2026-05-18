<?php

namespace App\Notifications;

use App\Models\Queue;
use Illuminate\Notifications\Notification;

class QueueCalledForPatient extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $queue = $this->queue;
        $appointment = $queue->appointment;
        $doctor = $appointment->doctor;

        return [
            'role'           => 'pasien',
            'event'          => 'queue_called',
            'title'          => 'Giliran Anda dipanggil',
            'message'        => "Antrian #{$queue->queue_number} untuk dr. {$doctor->user->name} sedang dipanggil, segera ke ruang periksa.",
            'icon'           => 'fas fa-bullhorn',
            'level'          => 'info',
            'appointment_id' => $appointment->id,
            'queue_id'       => $queue->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'booking_code'   => $appointment->booking_code,
            'url'            => route('pasien.reservasi.show', $appointment->id),
        ];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(private Queue $queue)
    {
    }
}
