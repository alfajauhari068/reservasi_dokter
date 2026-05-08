<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Schedule Model
 *
 * Manages doctor schedules with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Resolved merge conflict and standardized naming conventions
 */
class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'quota',
        'is_active',
    ];

    /**
     * Get the doctor associated with the schedule.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the appointments for the schedule.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
