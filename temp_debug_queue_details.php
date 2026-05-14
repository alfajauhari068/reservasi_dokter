<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Queue;

$queues = Queue::with(['appointment.patient.user', 'appointment.doctor.user'])->orderByDesc('id')->take(20)->get();

foreach ($queues as $q) {
    echo sprintf(
        "Queue %d appointment_id=%s queue_status=%s created_at=%s patient=%s doctor=%s\n",
        $q->id,
        $q->appointment_id,
        $q->queue_status,
        $q->created_at?->format('Y-m-d H:i:s') ?? 'NULL',
        $q->appointment?->patient?->full_name ?? $q->appointment?->patient?->identity_number ?? 'NULL',
        $q->appointment?->doctor?->user?->name ?? 'NULL'
    );
}
