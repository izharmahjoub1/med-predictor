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
            $table->enum('role', [
                'club_admin', 
                'club_manager', 
                'club_medical', 
                'association_admin', 
                'association_registrar', 
                'association_medical', 
                'system_admin'
            ])->default('club_admin')->after('password');
            $table->enum('entity_type', ['club', 'association'])->nullable()->after('role');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            $table->string('fifa_connect_id')->nullable()->after('entity_id');
            $table->json('permissions')->nullable()->after('fifa_connect_id');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('permissions');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->integer('login_count')->default(0)->after('last_login_at');

            // Indexes
            $table->index(['role', 'entity_type', 'entity_id']);
            $table->index(['fifa_connect_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'entity_type', 'entity_id']);
            $table->dropIndex(['fifa_connect_id']);
            $table->dropIndex(['status']);
            
            $table->dropColumn([
                'role',
                'entity_type',
                'entity_id',
                'fifa_connect_id',
                'permissions',
                'status',
                'last_login_at',
                'login_count'
            ]);
        });
    }
};
