<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\Specialization;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SpecializationsSeeder::class);

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@klinik.test'],
            [
                'name' => 'Admin Klinik',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Doctors with Doctor Model records
        $doctorSpecs = ['Umum', 'Gigi', 'Mata'];
        
        foreach ($doctorSpecs as $index => $spec) {
            $userName = 'dokter' . ($index + 1) . '@klinik.test';
            $doctorUser = User::updateOrCreate(
                ['email' => $userName],
                [
                    'name' => 'Dr. ' . $this->getDoctorName($index),
                    'password' => Hash::make('password'),
                    'role' => 'dokter',
                ]
            );

            // Create Doctor record if not exists
            $specialization = Specialization::where('name', $spec)->first();
            if ($specialization) {
                Doctor::updateOrCreate(
                    ['user_id' => $doctorUser->id],
                    [
                        'specialization_id' => $specialization->id,
                        'license_number' => 'LIC-' . Str::upper(Str::random(8)),
                        'experience_years' => 5 + $index,
                        'consultation_fee' => 100000 + ($index * 50000),
                        'bio' => 'Dokter spesialis ' . $spec,
                        'is_available' => true,
                    ]
                );
            }
        }

        // Create Patients
        for ($i = 1; $i <= 3; $i++) {
            $patientUser = User::updateOrCreate(
                ['email' => 'pasien' . $i . '@klinik.test'],
                [
                    'name' => 'Pasien Contoh ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'pasien',
                ]
            );

            Patient::updateOrCreate(
                ['user_id' => $patientUser->id],
                [
                    'phone' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'address' => 'Jalan Contoh ' . $i,
                ]
            );
        }

        // Create sample appointments - HARI INI
        $doctors = Doctor::with('user')->limit(2)->get();
        $patients = Patient::with('user')->limit(2)->get();

        if ($doctors->count() > 0 && $patients->count() > 0) {
            $queueNumber = 1;
            foreach ($patients as $patientIndex => $patient) {
                foreach ($doctors as $doctorIndex => $doctor) {
                    $appointment = Appointment::updateOrCreate(
                        ['booking_code' => 'BOOK-' . Str::upper(Str::random(8)) . '-' . $patient->id . '-' . now()->timestamp],
                        [
                            'patient_id' => $patient->id,
                            'doctor_id' => $doctor->id,
                            'schedule_id' => 1,
                            'appointment_date' => now()->toDateString(), // HARI INI
                            'complaint' => 'Checkup rutin',
                            'status' => 'approved',
                        ]
                    );

                    // Create Queue for each appointment
                    Queue::updateOrCreate(
                        ['appointment_id' => $appointment->id],
                        [
                            'queue_number' => $queueNumber,
                            'queue_status' => 'waiting',
                            'called_at' => null,
                            'served_at' => null,
                        ]
                    );

                    $queueNumber++;
                }
            }
        }
    }

    private function getDoctorName($index): string
    {
        $names = ['Budi Santoso', 'Siti Nurhaliza', 'Ahmad Wijaya'];
        return $names[$index] ?? 'Dokter ' . $index;
    }
}