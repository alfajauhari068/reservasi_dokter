<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MedicalRecord Model
 *
 * Manages medical records with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Added documentation and standardized naming conventions
 */
class MedicalRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'symptoms',
        'prescription',
        'doctor_notes',
        'vital_signs',
        'follow_up_date',
    ];

    /**
     * Get the appointment associated with the medical record.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
