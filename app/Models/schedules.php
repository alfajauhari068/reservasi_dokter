<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'quota',
        'is_active',
    ];

    public function doctor() {
        return $this->belongsTo(doctors::class, 'doctor_id');
    }
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
