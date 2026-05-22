<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;

$appointments = Appointment::with(['queue', 'patient.user', 'doctor.user'])
    ->where('status', 'completed')
    ->whereHas('queue')
    ->orderByDesc('id')
    ->get();

foreach ($appointments as $a) {
    echo sprintf(
        "Appointment %d status=%s approval=%s queue_status=%s patient=%s doctor=%s\n",
        $a->id,
        $a->status,
        $a->approval_status,
        $a->queue?->queue_status ?? 'NULL',
        $a->patient?->full_name ?? $a->patient?->identity_number ?? 'NULL',
        $a->doctor?->user?->name ?? 'NULL'
    );
}
