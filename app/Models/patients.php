<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patients extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth',
        'blood_type',
        'identity_number',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function appointments() {
        return $this->hasMany(Appointments::class);
    }
}
