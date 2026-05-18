<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Notifications\Notification;

class ScheduleChangedForDoctor extends Notification
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
        $schedule = $this->schedule;
        $doctor = $schedule->doctor;

        // Day labels in Indonesian
        $dayLabels = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $dayOfWeekText = $dayLabels[$schedule->day_of_week] ?? $schedule->day_of_week;

        return [
            'role'       => 'dokter',
            'event'      => 'schedule_changed',
            'title'      => 'Jadwal konsultasi diperbarui',
            'message'    => "Jadwal praktik Anda pada {$dayOfWeekText} telah diperbarui.",
            'icon'       => 'fas fa-calendar-day',
            'level'      => 'info',
            'doctor_id'  => $doctor->id,
            'url'        => route('dokter.dashboard'),
        ];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(private Schedule $schedule)
    {
    }
}
