<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fifa_connect_ids', function (Blueprint $table) {
            if (!Schema::hasColumn('fifa_connect_ids', 'fifa_id')) {
                $table->string('fifa_id')->unique()->after('id');
            }
        });

        Schema::table('players', function (Blueprint $table) {
            if (!Schema::hasColumn('players', 'status')) {
                $table->string('status')->nullable()->after('club_id');
            }
        });
    }

    public function down()
    {
        Schema::table('fifa_connect_ids', function (Blueprint $table) {
            if (Schema::hasColumn('fifa_connect_ids', 'fifa_id')) {
                $table->dropColumn('fifa_id');
            }
        });

        Schema::table('players', function (Blueprint $table) {
            if (Schema::hasColumn('players', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}; 