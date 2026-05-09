<?php

// File temporary untuk debug dashboard
// Akses di: /temp-debug-dashboard

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;

Route::get('/temp-debug-dashboard', function () {
    echo "<h2>DEBUG DASHBOARD DATA</h2>";
    
    echo "<h3>Total Dokter di Database</h3>";
    $totalDoctors = Doctor::count();
    echo "Total: " . $totalDoctors . "<br>";
    
    echo "<h3>Total Appointment di Database</h3>";
    $totalAppointments = Appointment::count();
    echo "Total: " . $totalAppointments . "<br>";
    
    echo "<h3>Total Users (Role = Dokter)</h3>";
    $doctorUsers = User::where('role', 'doctor')->count();
    echo "Total: " . $doctorUsers . "<br>";
    
    echo "<h3>Daftar Semua Dokter</h3>";
    $doctors = Doctor::with('user', 'specialization')->get();
    foreach ($doctors as $doctor) {
        echo "ID: " . $doctor->id . " | Nama: " . ($doctor->user->name ?? 'N/A') . " | Spesialis: " . ($doctor->specialization->name ?? 'N/A') . "<br>";
    }
    
    echo "<h3>Daftar Semua Appointment</h3>";
    $appointments = Appointment::with('patient', 'doctor.user')->limit(10)->get();
    foreach ($appointments as $appt) {
        echo "ID: " . $appt->id . " | Pasien: " . ($appt->patient->user->name ?? 'N/A') . " | Dokter: " . ($appt->doctor->user->name ?? 'N/A') . " | Status: " . $appt->status . " | Tanggal: " . $appt->appointment_date . "<br>";
    }
    
    echo "<h3>Query: Dokter dengan Appointment (ALL TIME)</h3>";
    $doctorStats = Doctor::selectRaw('
            doctors.id,
            users.name as doctor_name,
            specializations.name as specialization_name,
            COUNT(appointments.id) as total_appointments
        ')
        ->join('users', 'doctors.user_id', '=', 'users.id')
        ->leftJoin('specializations', 'doctors.specialization_id', '=', 'specializations.id')
        ->leftJoin('appointments', 'doctors.id', '=', 'appointments.doctor_id')
        ->groupBy('doctors.id', 'users.name', 'specializations.name')
        ->orderBy('total_appointments', 'desc')
        ->get();
    
    echo "Hasil: " . count($doctorStats) . " dokter<br>";
    foreach ($doctorStats as $stat) {
        echo "Dokter: " . $stat->doctor_name . " | Spesialis: " . $stat->specialization_name . " | Total Appointments: " . $stat->total_appointments . "<br>";
    }
});
