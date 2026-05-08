<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$appointments = App\Models\Appointment::orderBy('appointment_date')->limit(10)->get(['id', 'appointment_date', 'status']);
foreach ($appointments as $appointment) {
    echo $appointment->id . ' | ' . $appointment->appointment_date . ' | ' . $appointment->status . "\n";
}
