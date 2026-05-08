<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Notification Model
 *
 * Manages user notifications with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Added documentation and standardized naming conventions
 */
class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'appointment_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    /**
     * Get the user associated with the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointment associated with the notification.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
