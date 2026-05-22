<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Queue;

$today = \Illuminate\Support\Carbon::today()->toDateString();
echo "Today: $today\n";
$queues = Queue::with('appointment.patient.user')->get();
echo "Queues count: " . $queues->count() . "\n";
foreach ($queues as $q) {
    $appointment = $q->appointment;
    $appDate = $appointment ? $appointment->appointment_date : null;
    $appDateStr = $appDate ? $appDate->format('Y-m-d H:i:s') : 'NULL';
    $patientName = $appointment?->patient?->user?->name ?? $appointment?->patient?->full_name ?? 'NULL';
    echo "Q{$q->id}: status={$q->queue_status}, app_date={$appDateStr}, patient={$patientName}, appointment_id={$q->appointment_id}\n";
}
