<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'association_id',
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
        'founded_year',
        'capacity',
        'colors',
        'logo_url',
        'website',
        'description',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'budget_allocation' => 'decimal:2',
        'founded_year' => 'integer',
        'capacity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    public function lineups(): HasMany
    {
        return $this->hasMany(Lineup::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'team_players')
            ->withPivot(['joined_date', 'role', 'squad_number', 'status'])
            ->withTimestamps();
    }

    public function competitions(): BelongsToMany
    {
        return $this->belongsToMany(Competition::class, 'competition_team')
            ->withPivot(['joined_at', 'season_id'])
            ->withTimestamps();
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchModel::class, 'home_team_id')
            ->orWhere('away_team_id', $this->id);
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }

    public function matchRosters()
    {
        return $this->hasMany(\App\Models\MatchRoster::class, 'team_id');
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

    // Audit Trail Methods
    public function getAuditTrailIdentifier(): string
    {
        return "Team: {$this->name} (ID: {$this->id})";
    }

    public function getAuditTrailData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'club_id' => $this->club_id,
            'association_id' => $this->association_id,
            'status' => $this->status,
            'type' => $this->type,
        ];
    }

    // Methods
    public function getSquadSize(): int
    {
        return $this->players()->count();
    }

    public function getAverageRating(): float
    {
        $average = $this->players()->avg('players.overall_rating');
        return $average ?? 0.0;
    }

    public function getFormationPlayers(string $position): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->where('position', $position)->get();
    }

    public function getBestLineup(): array
    {
        $formation = $this->formation ?? '4-4-2';
        $positions = $this->parseFormation($formation);
        $lineup = [];

        foreach ($positions as $position => $count) {
            $players = $this->players()
                ->where('position', $position)
                ->orderBy('overall_rating', 'desc')
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
        $players = $this->players()->get();
        
        $analysis = [
            'total_players' => $players->count(),
            'average_age' => $players->avg('age') ?? 0.0,
            'average_rating' => $players->avg('overall_rating') ?? 0.0,
            'positions' => $players->groupBy('position')->map->count(),
            'nationalities' => $players->groupBy('nationality')->map->count(),
            'value_distribution' => [
                'total_value' => $players->sum('value_eur') ?? 0.0,
                'average_value' => $players->avg('value_eur') ?? 0.0
            ]
        ];

        return $analysis;
    }

    public function getTeamStrength(): array
    {
        $players = $this->players()->get();
        
        $strengths = [
            'attack' => $players->whereIn('position', ['ST', 'RW', 'LW', 'CAM'])->avg('overall_rating') ?? 0.0,
            'midfield' => $players->whereIn('position', ['CM', 'CDM', 'CAM'])->avg('overall_rating') ?? 0.0,
            'defense' => $players->whereIn('position', ['CB', 'RB', 'LB'])->avg('overall_rating') ?? 0.0,
            'goalkeeping' => $players->where('position', 'GK')->avg('overall_rating') ?? 0.0,
        ];

        return $strengths;
    }
} 