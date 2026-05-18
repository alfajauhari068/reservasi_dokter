<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DebugNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'role' => 'debug',
            'event' => 'debug_notification',
            'title' => 'Notifikasi Debug',
            'message' => 'Ini adalah notifikasi dummy untuk memverifikasi penyimpanan notifikasi ke database dan tampilan UI.',
            'icon' => 'fas fa-bug',
            'level' => 'info',
            'url' => route('notifications.index'),
        ];
    }
}
