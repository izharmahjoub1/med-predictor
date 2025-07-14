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
            // Add separate club and association foreign keys
            $table->unsignedBigInteger('club_id')->nullable()->after('entity_id');
            $table->unsignedBigInteger('association_id')->nullable()->after('club_id');
            
            // Add foreign key constraints
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('set null');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('set null');
            
            // Add indexes for performance
            $table->index(['club_id']);
            $table->index(['association_id']);
            $table->index(['club_id', 'association_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['club_id']);
            $table->dropForeign(['association_id']);
            
            // Drop indexes
            $table->dropIndex(['club_id']);
            $table->dropIndex(['association_id']);
            $table->dropIndex(['club_id', 'association_id']);
            
            // Drop columns
            $table->dropColumn(['club_id', 'association_id']);
        });
    }
};
