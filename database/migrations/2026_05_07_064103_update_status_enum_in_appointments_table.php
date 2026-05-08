<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'approved', 'done'])->default('pending')->change();
        });

        // Update existing status values
        DB::table('appointments')->where('status', 'approved')->update(['status' => 'in_progress']);
        DB::table('appointments')->where('status', 'done')->update(['status' => 'completed']);

        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'cancelled', 'done'])->default('pending')->change();
        });

        // Reverse status values
        DB::table('appointments')->where('status', 'in_progress')->update(['status' => 'approved']);
        DB::table('appointments')->where('status', 'completed')->update(['status' => 'done']);
    }
};
