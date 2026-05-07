<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
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
        return $this->belongsTo(Specialization::class);
    }
    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
