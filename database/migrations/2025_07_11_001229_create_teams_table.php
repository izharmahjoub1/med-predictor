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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['first_team', 'reserve', 'youth', 'academy']);
            $table->string('formation', 10)->default('4-4-2');
            $table->string('tactical_style')->nullable();
            $table->text('playing_philosophy')->nullable();
            $table->string('coach_name')->nullable();
            $table->string('assistant_coach')->nullable();
            $table->string('fitness_coach')->nullable();
            $table->string('goalkeeper_coach')->nullable();
            $table->string('scout')->nullable();
            $table->string('medical_staff')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('season', 10);
            $table->string('competition_level')->nullable();
            $table->decimal('budget_allocation', 15, 2)->nullable();
            $table->string('training_facility')->nullable();
            $table->string('home_ground')->nullable();
            $table->timestamps();

            $table->index(['club_id', 'type']);
            $table->index(['club_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
