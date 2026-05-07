<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'queue_number')) {
                $table->unsignedInteger('queue_number')->nullable()->after('booking_code');
            }
            if (! Schema::hasColumn('appointments', 'queue_date')) {
                $table->date('queue_date')->nullable()->after('queue_number');
            }
            if (! Schema::hasIndex('appointments', 'appointments_doctor_date_index')) {
                $table->index(['doctor_id', 'appointment_date'], 'appointments_doctor_date_index');
            }
            if (! Schema::hasIndex('appointments', 'appointments_doctor_date_queue_unique')) {
                $table->unique(['doctor_id', 'queue_date', 'queue_number'], 'appointments_doctor_date_queue_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('appointments_doctor_date_queue_unique');
            $table->dropColumn(['queue_number', 'queue_date']);
        });
    }
};
