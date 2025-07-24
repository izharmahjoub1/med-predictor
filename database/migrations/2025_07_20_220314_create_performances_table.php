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
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->date('match_date');
            $table->integer('distance_covered')->comment('Distance covered in meters');
            $table->integer('sprint_count')->default(0);
            $table->decimal('max_speed', 5, 2)->comment('Maximum speed in km/h');
            $table->decimal('avg_speed', 5, 2)->comment('Average speed in km/h');
            $table->integer('passes_completed')->nullable();
            $table->integer('passes_attempted')->nullable();
            $table->integer('tackles_won')->nullable();
            $table->integer('tackles_attempted')->nullable();
            $table->integer('shots_on_target')->nullable();
            $table->integer('shots_total')->nullable();
            $table->integer('goals_scored')->nullable();
            $table->integer('assists')->nullable();
            $table->integer('yellow_cards')->nullable();
            $table->integer('red_cards')->nullable();
            $table->integer('minutes_played')->nullable();
            $table->decimal('rating', 3, 2)->comment('Performance rating out of 10');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['player_id', 'match_date']);
            $table->index('match_date');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
