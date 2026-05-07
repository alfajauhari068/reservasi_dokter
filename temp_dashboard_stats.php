<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$today = \Carbon\Carbon::today()->toDateString();
echo "today={$today}\n";
$stats = App\Models\Appointment::selectRaw(
    'COUNT(*) as total, SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending, '
    . 'SUM(CASE WHEN status = \'approved\' THEN 1 ELSE 0 END) as approved, '
    . 'SUM(CASE WHEN status = \'done\' THEN 1 ELSE 0 END) as done, '
    . 'SUM(CASE WHEN status = \'cancelled\' THEN 1 ELSE 0 END) as cancelled'
)->whereDate('appointment_date', $today)->first();
print_r($stats->toArray());
