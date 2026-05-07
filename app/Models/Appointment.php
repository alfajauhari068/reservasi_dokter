<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'complaint',
        'status',
        'booking_code',
        'queue_number',
        'queue_date',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'queue_date' => 'date',
    ];

    public function patient() {
        return $this->belongsTo(patients::class);
    }
    public function doctor() {
        return $this->belongsTo(doctors::class, 'doctor_id');
    }
    public function schedule() {
        return $this->belongsTo(schedules::class);
    }
    public function queue() {
        return $this->hasOne(queues::class);
    }
    public function medicalRecord() {
        return $this->hasOne(medical_records::class);
    }
    public function notifications() {
        return $this->hasMany(notifications::class);
    }
}
