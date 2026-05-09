<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

$request = Request::create('/', 'GET', [
    'start_date' => '2026-05-01',
    'end_date' => '2026-05-31',
]);

$service = new App\Services\VisitReportService();
$query = $service->buildReportQuery($request);

$reports = $query->select([
    'appointments.id',
    'appointments.appointment_date',
    'schedules.start_time as appointment_time',
    'appointments.booking_code',
    'appointments.complaint as symptoms',
    'patient_users.name as patient_name',
    'doctor_users.name as doctor_name',
    'doctors.id as doctor_id',
    'specializations.name as specialization_name',
    'queues.queue_number',
    'queues.queue_status',
    'queues.called_at',
    'queues.served_at',
])->orderBy('appointments.appointment_date', 'desc')
  ->orderBy('schedules.start_time', 'desc')
  ->get();

echo "reports=" . $reports->count() . "\n";

echo "\nDoctors list:\n";
$doctors = App\Models\Doctor::with(['user', 'specialization'])->get();
foreach ($doctors as $doctor) {
    $appointmentCount = App\Models\Appointment::where('doctor_id', $doctor->id)
        ->whereBetween('appointment_date', ['2026-05-01', '2026-05-31'])
        ->count();
    echo $doctor->id . ' | ' . ($doctor->user->name ?? 'N/A') . ' | ' . ($doctor->specialization->name ?? 'N/A') . ' | appointments=' . $appointmentCount . "\n";
}

echo "\nDr. Reja appointment details:\n";
$reja = App\Models\Doctor::whereHas('user', function($q) { $q->where('name', 'Dr. Reja'); })->first();
if ($reja) {
    $rejaAppointments = App\Models\Appointment::where('doctor_id', $reja->id)
        ->with('queue')
        ->whereBetween('appointment_date', ['2026-05-01', '2026-05-31'])
        ->get();
    foreach ($rejaAppointments as $appt) {
        echo 'appt=' . $appt->id . ' | date=' . $appt->appointment_date . ' | status=' . $appt->status . ' | queue=' . ($appt->queue ? $appt->queue->queue_status : 'NULL') . ' | called_at=' . ($appt->queue?->called_at) . ' | served_at=' . ($appt->queue?->served_at) . "\n";
    }
}

echo "\nReports rows:\n";
foreach ($reports as $r) {
    echo $r->doctor_id . ' | ' . $r->doctor_name . ' | ' . $r->specialization_name . ' | ' . $r->appointment_date . ' | ' . $r->queue_status . ' | ' . $r->called_at . ' | ' . $r->served_at . "\n";
}
