<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Notifications\Notification;

class NewReservationForDoctor extends Notification
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
        $scheduledDate = $appointment->appointment_date?->format('d-m-Y H:i') ?? 'tidak terjadwal';

        return [
            'role'           => 'dokter',
            'event'          => 'reservation_assigned',
            'title'          => 'Reservasi baru untuk Anda',
            'message'        => "Pasien {$patient->user->name} dijadwalkan pada {$scheduledDate}.",
            'icon'           => 'fas fa-user-plus',
            'level'          => 'info',
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'doctor_id'      => $appointment->doctor_id,
            'booking_code'   => $appointment->booking_code,
            'scheduled_at'   => $appointment->appointment_date?->format('Y-m-d H:i:s'),
            'url'            => route('dokter.reservasi.show', $appointment->id),
        ];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(private Appointment $appointment)
    {
    }
}
