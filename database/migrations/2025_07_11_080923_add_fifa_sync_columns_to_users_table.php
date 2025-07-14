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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('fifa_sync_status', ['pending', 'synced', 'failed'])->default('pending')->after('fifa_connect_id');
            $table->timestamp('fifa_sync_date')->nullable()->after('fifa_sync_status');
            
            // Index for sync status
            $table->index(['fifa_sync_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['fifa_sync_status']);
            $table->dropColumn(['fifa_sync_status', 'fifa_sync_date']);
        });
    }
};
