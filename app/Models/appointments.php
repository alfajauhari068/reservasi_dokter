<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointments extends Model {
    
    use HasFactory;
    public function patient() {
        return $this->belongsTo(Patients::class);
    }
    public function doctor() {
        return $this->belongsTo(Doctors::class);
    }
    public function schedule() {
        return $this->belongsTo(Schedules::class);
    }
    public function queue() {
        return $this->hasOne(Queues::class);
    }
    public function medicalRecord() {
        return $this->hasOne(Medical_records::class);
    }
    public function notifications() {
        return $this->hasMany(Notifications::class);
    }
}
