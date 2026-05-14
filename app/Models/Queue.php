<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Queue Model
 *
 * Manages appointment queues with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 *
 * @refactor Resolved merge conflict and standardized naming conventions
 */
class Queue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'appointment_id',
        'queue_number',
        'queue_status',
        'called_at',
        'served_at',
    ];

    /**
     * Get the appointment associated with the queue.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
