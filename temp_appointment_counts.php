<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Appointment;
use Carbon\Carbon;
$today = Carbon::today()->toDateString();
echo "today={$today}\n";
$appointments = Appointment::orderBy('appointment_date')->get(['id','appointment_date','created_at','status']);
$countsByDate = $appointments->groupBy(function ($item) {
    return substr($item->appointment_date, 0, 10);
})->map->count();
$countsByCreated = $appointments->groupBy(function ($item) {
    return substr($item->created_at, 0, 10);
})->map->count();
echo "Counts by appointment_date:\n";
print_r($countsByDate->toArray());
echo "Counts by created_at:\n";
print_r($countsByCreated->toArray());
echo "Statuses total:\n";
print_r(Appointment::selectRaw('status, count(*) as count')->groupBy('status')->get()->toArray());
