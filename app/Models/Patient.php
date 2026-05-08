<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Patient Model
 *
 * Manages patient information with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Standardized naming conventions and added documentation
 */
class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth',
        'blood_type',
        'identity_number',
    ];

    /**
     * Get the user associated with the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
