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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('fifa_connect_id')->nullable();
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->string('position');
            $table->foreignId('club_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('association_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('preferred_foot')->nullable();
            $table->integer('weak_foot')->nullable();
            $table->integer('skill_moves')->nullable();
            $table->integer('international_reputation')->nullable();
            $table->string('work_rate')->nullable();
            $table->string('body_type')->nullable();
            $table->boolean('real_face')->default(false);
            $table->decimal('release_clause_eur', 15, 2)->nullable();
            $table->string('player_face_url')->nullable();
            $table->string('club_logo_url')->nullable();
            $table->string('nation_flag_url')->nullable();
            $table->integer('overall_rating')->nullable();
            $table->integer('potential_rating')->nullable();
            $table->decimal('value_eur', 15, 2)->nullable();
            $table->decimal('wage_eur', 15, 2)->nullable();
            $table->integer('age')->nullable();
            $table->date('contract_valid_until')->nullable();
            $table->string('fifa_version')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
            
            $table->index(['name', 'nationality']);
            $table->index(['club_id', 'position']);
            $table->index(['overall_rating', 'potential_rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
