<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class doctors extends Model
{
    use HasFactory;

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

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function specialization() {
        return $this->belongsTo(Specializations::class);
    }
    public function schedules() {
        return $this->hasMany(schedules::class, 'doctor_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
