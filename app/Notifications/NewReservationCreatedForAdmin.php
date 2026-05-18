<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Notification;

class NewReservationCreatedForAdmin extends Notification
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
        $appointment = $this->appointment;
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        return [
            'role'           => 'admin',
            'event'          => 'reservation_created',
            'title'          => 'Reservasi baru dibuat',
            'message'        => "Pasien {$patient->user->name} membuat reservasi ke {$doctor->user->name}.",
            'icon'           => 'fas fa-calendar-plus',
            'level'          => 'info',
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'booking_code'   => $appointment->booking_code,
            'scheduled_at'   => $appointment->appointment_date?->format('Y-m-d H:i:s'),
            'url'            => route('admin.appointments.index'),
        ];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(private Appointment $appointment)
    {
    }
}
