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
        Schema::create('player_season_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->integer('matches_played')->default(0);
            $table->integer('minutes_played')->default(0);
            $table->integer('goals')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->timestamps();
            $table->unique(['player_id', 'competition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_season_stats');
    }
};
