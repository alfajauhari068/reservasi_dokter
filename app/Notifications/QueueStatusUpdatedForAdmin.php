<?php

namespace App\Notifications;

use App\Models\Queue;
use Illuminate\Notifications\Notification;

class QueueStatusUpdatedForAdmin extends Notification
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
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        // Determine level based on status
        $level = 'info';
        if ($queue->queue_status === 'skipped') {
            $level = 'warning';
        } elseif ($queue->queue_status === 'served') {
            $level = 'success';
        }

        // Status display text
        $statusLabels = [
            'waiting' => 'menunggu',
            'called'  => 'dipanggil',
            'served'  => 'dilayani',
            'skipped' => 'dilewati',
        ];
        $statusText = $statusLabels[$queue->queue_status] ?? $queue->queue_status;

        return [
            'role'           => 'admin',
            'event'          => 'queue_status_updated',
            'title'          => 'Status antrian diperbarui',
            'message'        => "Antrian #{$queue->queue_number} ({$patient->user->name}) sekarang {$statusText}.",
            'icon'           => 'fas fa-bullhorn',
            'level'          => $level,
            'appointment_id' => $appointment->id,
            'queue_id'       => $queue->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'booking_code'   => $appointment->booking_code,
            'url'            => route('admin.queues.index'),
        ];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(private Queue $queue)
    {
    }
}
