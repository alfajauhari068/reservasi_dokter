<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

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

    /**
     * Calculate the next queue number for a given specialization and date.
     */
    public static function nextNumberForSpecialization(?int $specializationId, string $date): int
    {
        $maxQueue = self::selectRaw('MAX(queues.queue_number) as max_queue')
            ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->whereDate('appointments.appointment_date', $date)
            ->when($specializationId !== null, function ($query) use ($specializationId) {
                $query->where('doctors.specialization_id', $specializationId);
            }, function ($query) {
                $query->whereNull('doctors.specialization_id');
            })
            ->value('max_queue');

        return ($maxQueue ? $maxQueue + 1 : 1);
    }

    /**
     * Normalize queue numbers so each specialization starts from 1 and increments sequentially.
     */
    public static function normalizeBySpecialization(string $date): void
    {
        $queues = self::select([
                'queues.id as queue_id',
                'queues.appointment_id',
                'queues.queue_number',
                'appointments.id as appointment_id',
                'appointments.queue_number as appointment_queue_number',
                'doctors.specialization_id'
            ])
            ->join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->whereDate('appointments.appointment_date', $date)
            ->orderByRaw('COALESCE(doctors.specialization_id, 0)')
            ->orderBy('doctors.specialization_id')
            ->orderBy('appointments.created_at')
            ->get();

        $currentSpecialization = null;
        $nextNumber = 0;

        foreach ($queues as $queue) {
            if ($queue->specialization_id !== $currentSpecialization) {
                $currentSpecialization = $queue->specialization_id;
                $nextNumber = 1;
            } else {
                $nextNumber++;
            }

            if ($queue->queue_number !== $nextNumber || $queue->appointment_queue_number !== $nextNumber) {
                self::where('id', $queue->queue_id)->update(['queue_number' => $nextNumber]);
                Appointment::where('id', $queue->appointment_id)->update(['queue_number' => $nextNumber]);
            }
        }
    }
}
