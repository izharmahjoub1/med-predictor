<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'game_matches';

    protected $fillable = [
        'competition_id',
        'home_team_id',
        'away_team_id',
        'match_date',
        'home_score',
        'away_score',
        'status',
        'venue',
        'referee',
        'fifa_connect_id',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'away_team_id');
    }

    public function fifaConnect(): BelongsTo
    {
        return $this->belongsTo(FifaConnectId::class, 'fifa_connect_id');
    }
} 