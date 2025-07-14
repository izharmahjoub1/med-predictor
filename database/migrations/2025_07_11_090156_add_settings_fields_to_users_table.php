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
            $table->string('timezone')->nullable()->after('login_count');
            $table->string('language')->default('fr')->after('timezone');
            $table->boolean('notifications_email')->default(true)->after('language');
            $table->boolean('notifications_sms')->default(false)->after('notifications_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'language', 'notifications_email', 'notifications_sms']);
        });
    }
};
