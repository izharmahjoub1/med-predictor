<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'match_id',
        'competition_id',
        'team_id',
        'performance_date',
        
        // Dimensions physiques
        'physical_score',
        'endurance_score',
        'strength_score',
        'speed_score',
        'agility_score',
        'recovery_time',
        'fatigue_level',
        'muscle_mass',
        'body_fat_percentage',
        'vo2_max',
        'lactate_threshold',
        
        // Dimensions techniques
        'technical_score',
        'passing_accuracy',
        'shooting_accuracy',
        'dribbling_skill',
        'tackling_effectiveness',
        'heading_accuracy',
        'crossing_accuracy',
        'free_kick_accuracy',
        'penalty_accuracy',
        
        // Dimensions tactiques
        'tactical_score',
        'positioning_awareness',
        'decision_making',
        'game_intelligence',
        'team_work_rate',
        'pressing_intensity',
        'defensive_organization',
        'attacking_movement',
        
        // Dimensions mentales
        'mental_score',
        'confidence_level',
        'stress_management',
        'focus_concentration',
        'motivation_level',
        'leadership_qualities',
        'pressure_handling',
        'mental_toughness',
        
        // Dimensions sociales
        'social_score',
        'team_cohesion',
        'communication_skills',
        'coachability',
        'discipline_level',
        'professional_attitude',
        'media_handling',
        'fan_interaction',
        
        // Score composite
        'overall_performance_score',
        'performance_trend',
        'improvement_areas',
        'strengths_highlighted',
        
        // Métadonnées
        'data_source', // 'match', 'training', 'assessment', 'fifa_connect', 'medical_device'
        'assessment_method', // 'coach_evaluation', 'ai_analysis', 'sensor_data', 'video_analysis'
        'data_confidence_level',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'performance_date' => 'datetime',
        'physical_score' => 'decimal:2',
        'endurance_score' => 'decimal:2',
        'strength_score' => 'decimal:2',
        'speed_score' => 'decimal:2',
        'agility_score' => 'decimal:2',
        'recovery_time' => 'integer',
        'fatigue_level' => 'decimal:2',
        'muscle_mass' => 'decimal:2',
        'body_fat_percentage' => 'decimal:2',
        'vo2_max' => 'decimal:2',
        'lactate_threshold' => 'decimal:2',
        'technical_score' => 'decimal:2',
        'passing_accuracy' => 'decimal:2',
        'shooting_accuracy' => 'decimal:2',
        'dribbling_skill' => 'decimal:2',
        'tackling_effectiveness' => 'decimal:2',
        'heading_accuracy' => 'decimal:2',
        'crossing_accuracy' => 'decimal:2',
        'free_kick_accuracy' => 'decimal:2',
        'penalty_accuracy' => 'decimal:2',
        'tactical_score' => 'decimal:2',
        'positioning_awareness' => 'decimal:2',
        'decision_making' => 'decimal:2',
        'game_intelligence' => 'decimal:2',
        'team_work_rate' => 'decimal:2',
        'pressing_intensity' => 'decimal:2',
        'defensive_organization' => 'decimal:2',
        'attacking_movement' => 'decimal:2',
        'mental_score' => 'decimal:2',
        'confidence_level' => 'decimal:2',
        'stress_management' => 'decimal:2',
        'focus_concentration' => 'decimal:2',
        'motivation_level' => 'decimal:2',
        'leadership_qualities' => 'decimal:2',
        'pressure_handling' => 'decimal:2',
        'mental_toughness' => 'decimal:2',
        'social_score' => 'decimal:2',
        'team_cohesion' => 'decimal:2',
        'communication_skills' => 'decimal:2',
        'coachability' => 'decimal:2',
        'discipline_level' => 'decimal:2',
        'professional_attitude' => 'decimal:2',
        'media_handling' => 'decimal:2',
        'fan_interaction' => 'decimal:2',
        'overall_performance_score' => 'decimal:2',
        'performance_trend' => 'decimal:2',
        'improvement_areas' => 'array',
        'strengths_highlighted' => 'array',
        'data_confidence_level' => 'decimal:2',
        'notes' => 'array'
    ];

    // Relationships
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(PerformanceRecommendation::class);
    }

    // Scopes
    public function scopeByPlayer($query, $playerId)
    {
        return $query->where('player_id', $playerId);
    }

    public function scopeByCompetition($query, $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('performance_date', [$startDate, $endDate]);
    }

    public function scopeByDataSource($query, $source)
    {
        return $query->where('data_source', $source);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('performance_date', '>=', now()->subDays($days));
    }

    // Methods
    public function calculateOverallScore(): float
    {
        $weights = [
            'physical' => 0.25,
            'technical' => 0.25,
            'tactical' => 0.20,
            'mental' => 0.20,
            'social' => 0.10
        ];

        $score = ($this->physical_score * $weights['physical']) +
                ($this->technical_score * $weights['technical']) +
                ($this->tactical_score * $weights['tactical']) +
                ($this->mental_score * $weights['mental']) +
                ($this->social_score * $weights['social']);

        return round($score, 2);
    }

    public function getPerformanceLevel(): string
    {
        $score = $this->overall_performance_score;
        
        if ($score >= 90) return 'Exceptional';
        if ($score >= 80) return 'Excellent';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Average';
        if ($score >= 50) return 'Below Average';
        return 'Poor';
    }

    public function getPerformanceColor(): string
    {
        return match($this->getPerformanceLevel()) {
            'Exceptional' => 'green',
            'Excellent' => 'blue',
            'Good' => 'yellow',
            'Average' => 'orange',
            'Below Average' => 'red',
            'Poor' => 'gray',
            default => 'gray'
        };
    }

    public function getTrendDirection(): string
    {
        if ($this->performance_trend > 0) return 'up';
        if ($this->performance_trend < 0) return 'down';
        return 'stable';
    }

    public function getTrendColor(): string
    {
        return match($this->getTrendDirection()) {
            'up' => 'green',
            'down' => 'red',
            'stable' => 'gray'
        };
    }

    public function getTopStrengths(int $limit = 3): array
    {
        $scores = [
            'Physical' => $this->physical_score,
            'Technical' => $this->technical_score,
            'Tactical' => $this->tactical_score,
            'Mental' => $this->mental_score,
            'Social' => $this->social_score
        ];

        arsort($scores);
        return array_slice($scores, 0, $limit, true);
    }

    public function getImprovementAreas(int $limit = 3): array
    {
        $scores = [
            'Physical' => $this->physical_score,
            'Technical' => $this->technical_score,
            'Tactical' => $this->tactical_score,
            'Mental' => $this->mental_score,
            'Social' => $this->social_score
        ];

        asort($scores);
        return array_slice($scores, 0, $limit, true);
    }
} 