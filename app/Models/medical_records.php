<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class medical_records extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'prescription',
        'doctor_notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
