<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'type', // first_team, reserve, youth, academy
        'formation',
        'tactical_style',
        'playing_philosophy',
        'coach_name',
        'assistant_coach',
        'fitness_coach',
        'goalkeeper_coach',
        'scout',
        'medical_staff',
        'status',
        'season',
        'competition_level',
        'budget_allocation',
        'training_facility',
        'home_ground',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'budget_allocation' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function lineups(): HasMany
    {
        return $this->hasMany(Lineup::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(TeamPlayer::class);
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(TeamCompetition::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySeason($query, $season)
    {
        return $query->where('season', $season);
    }

    // Methods
    public function getSquadSize(): int
    {
        return $this->players()->count();
    }

    public function getAverageRating(): float
    {
        $average = $this->players()->join('players', 'team_players.player_id', '=', 'players.id')
            ->avg('players.overall_rating');
        return $average ?? 0.0;
    }

    public function getFormationPlayers(string $position): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->whereHas('player', function ($query) use ($position) {
            $query->where('position', $position);
        })->get();
    }

    public function getBestLineup(): array
    {
        $formation = $this->formation ?? '4-4-2';
        $positions = $this->parseFormation($formation);
        $lineup = [];

        foreach ($positions as $position => $count) {
            $players = $this->players()
                ->whereHas('player', function ($query) use ($position) {
                    $query->where('position', $position);
                })
                ->orderBy('players.overall_rating', 'desc')
                ->limit($count)
                ->get();

            $lineup[$position] = $players;
        }

        return $lineup;
    }

    public function parseFormation(string $formation): array
    {
        $parts = explode('-', $formation);
        $positions = [];

        // Default 4-4-2 formation
        if (count($parts) === 2) {
            $positions = [
                'GK' => 1,
                'CB' => $parts[0],
                'CM' => $parts[1],
                'ST' => 1
            ];
        } elseif (count($parts) === 3) {
            $positions = [
                'GK' => 1,
                'CB' => $parts[0],
                'CM' => $parts[1],
                'ST' => $parts[2]
            ];
        } elseif (count($parts) === 4) {
            $positions = [
                'GK' => 1,
                'CB' => $parts[0],
                'CM' => $parts[1],
                'CAM' => $parts[2],
                'ST' => $parts[3]
            ];
        }

        return $positions;
    }

    public function getTacticalAnalysis(): array
    {
        $players = $this->players()->with('player')->get();
        
        $analysis = [
            'total_players' => $players->count(),
            'average_age' => $players->avg(function ($teamPlayer) {
                return $teamPlayer->player->age;
            }) ?? 0.0,
            'average_rating' => $players->avg(function ($teamPlayer) {
                return $teamPlayer->player->overall_rating;
            }) ?? 0.0,
            'positions' => $players->groupBy(function ($teamPlayer) {
                return $teamPlayer->player->position;
            })->map->count(),
            'nationalities' => $players->groupBy(function ($teamPlayer) {
                return $teamPlayer->player->nationality;
            })->map->count(),
            'value_distribution' => [
                'total_value' => $players->sum(function ($teamPlayer) {
                    return $teamPlayer->player->value_eur;
                }) ?? 0.0,
                'average_value' => $players->avg(function ($teamPlayer) {
                    return $teamPlayer->player->value_eur;
                }) ?? 0.0
            ]
        ];

        return $analysis;
    }

    public function getTeamStrength(): array
    {
        $players = $this->players()->with('player')->get();
        
        $strengths = [
            'attack' => $players->whereIn('player.position', ['ST', 'RW', 'LW', 'CAM'])->avg('player.overall_rating') ?? 0.0,
            'midfield' => $players->whereIn('player.position', ['CM', 'CDM', 'CAM'])->avg('player.overall_rating') ?? 0.0,
            'defense' => $players->whereIn('player.position', ['CB', 'RB', 'LB'])->avg('player.overall_rating') ?? 0.0,
            'goalkeeping' => $players->where('player.position', 'GK')->avg('player.overall_rating') ?? 0.0,
        ];

        return $strengths;
    }
} 