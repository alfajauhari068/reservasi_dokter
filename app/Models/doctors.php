<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctors extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function specialization() {
        return $this->belongsTo(Specializations::class);
    }
    public function schedules() {
        return $this->hasMany(Schedules::class);
    }
}
