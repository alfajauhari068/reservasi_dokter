<?php

namespace App\Listeners;

use App\Events\AppointmentRequested;
use App\Models\Notification;
use App\Models\User;

class NotifyAdminOnAppointmentRequest
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event - Send notification to all admin users
     */
    public function handle(AppointmentRequested $event): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->get();

        // Create notification for each admin
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'appointment_id' => $event->appointment->id,
                'type' => 'appointment_request',
                'title' => 'Permohonan Reservasi Baru',
                'message' => "Pasien {$event->appointment->patient->user->name} mengajukan permohonan reservasi dengan dokter {$event->appointment->doctor->user->name} pada {$event->appointment->appointment_date->format('d M Y')}. Kode booking: {$event->appointment->booking_code}",
                'is_read' => false,
            ]);
        }
    }
}
