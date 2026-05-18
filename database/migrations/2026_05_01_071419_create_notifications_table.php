<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Schema default Laravel Database Notifications
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('id')->primary();
            
            // Polymorphic relationship to notifiable (User)
            $table->morphs('notifiable');
            
            // Notification type/class name
            $table->string('type');
            
            // JSON data containing notification content
            $table->json('data');
            
            // Read timestamp (null = unread)
            $table->timestamp('read_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['notifiable_type', 'notifiable_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
