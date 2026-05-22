<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Appointment Model
 * 
 * Manages doctor appointments with proper PascalCase naming convention.
 * All relationships use proper model class references in PascalCase.
 * 
 * @refactor Resolved merge conflict and standardized naming conventions
 */
class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'complaint',
        'status',
        'booking_code',
        'queue_number',
        'queue_date',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'appointment_date' => 'date',
        'queue_date' => 'date',
    ];

    /**
     * Get the patient associated with the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the schedule associated with the appointment.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the queue associated with the appointment. 
     */
    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    /**
     * Get the medical record associated with the appointment.
     */
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    /**
     * Get the notifications for the appointment.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user (admin) who approved this appointment.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Register model event callbacks.
     */
    protected static function booted()
    {
        static::updated(function (Appointment $appointment) {
            if ($appointment->wasChanged('status') && $appointment->status === 'completed') {
                if ($appointment->queue) {
                    $appointment->queue->update([
                        'queue_status' => 'served',
                        'called_at' => $appointment->queue->called_at ?? now(),
                        'served_at' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Check if appointment is waiting for admin approval
     */
    public function isWaitingApproval()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if appointment is approved by admin
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if appointment is rejected by admin
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }
}
