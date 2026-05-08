<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Doctor Model
 *
 * Manages doctor information with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Resolved merge conflict and standardized naming conventions
 */
class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'specialization_id',
        'license_number',
        'experience_years',
        'consultation_fee',
        'bio',
        'photo',
        'is_available',
    ];

    /**
     * Get the user associated with the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialization associated with the doctor.
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
