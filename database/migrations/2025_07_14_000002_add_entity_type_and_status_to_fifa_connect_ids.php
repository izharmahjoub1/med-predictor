<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fifa_connect_ids', function (Blueprint $table) {
            if (!Schema::hasColumn('fifa_connect_ids', 'entity_type')) {
                $table->string('entity_type')->default('player')->after('fifa_id');
            }
            if (!Schema::hasColumn('fifa_connect_ids', 'status')) {
                $table->string('status')->default('active')->after('entity_type');
            }
        });
    }

    public function down()
    {
        Schema::table('fifa_connect_ids', function (Blueprint $table) {
            if (Schema::hasColumn('fifa_connect_ids', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('fifa_connect_ids', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}; 