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
        if (Schema::hasTable('notifications')) {
            $columns = Schema::getColumnListing('notifications');

            if (! in_array('notifiable_type', $columns) || ! in_array('notifiable_id', $columns) || ! in_array('data', $columns)) {
                $renameTo = 'legacy_notifications';
                if (Schema::hasTable($renameTo)) {
                    $renameTo = 'legacy_notifications_' . time();
                }

                Schema::rename('notifications', $renameTo);
            }
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->morphs('notifiable');
                $table->string('type');
                $table->json('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['notifiable_type', 'notifiable_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::dropIfExists('notifications');
        }
    }
};
