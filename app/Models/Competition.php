<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'type',
        'season_id',
        'start_date',
        'end_date',
        'registration_deadline',
        'min_teams',
        'max_teams',
        'format',
        'status',
        'description',
        'rules',
        'entry_fee',
        'prize_pool',
        'association_id',
        'fifa_connect_id',
        'require_federation_license',
        'fixtures_validated',
        'fixtures_validated_at',
        'validated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'min_teams' => 'integer',
        'max_teams' => 'integer',
        'entry_fee' => 'decimal:2',
        'prize_pool' => 'decimal:2',
        'require_federation_license' => 'boolean',
        'fixtures_validated' => 'boolean',
        'fixtures_validated_at' => 'datetime',
    ];

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function seasonRelation(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function fifaConnectId(): BelongsTo
    {
        return $this->belongsTo(FifaConnectId::class, 'fifa_connect_id');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'competition_club')
            ->withTimestamps();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'competition_team')
            ->withTimestamps();
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class, 'competition_id');
    }

    public function gameMatches()
    {
        return $this->hasMany(GameMatch::class, 'competition_id');
    }

    public function getFifaIdAttribute(): ?string
    {
        return $this->fifaConnectId?->fifa_id;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'upcoming' => 'blue',
            'active' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'league' => 'Championnat',
            'cup' => 'Coupe',
            'friendly' => 'Match amical',
            'international' => 'Compétition internationale',
            'tournament' => 'Tournoi',
            'playoff' => 'Playoff',
            'exhibition' => 'Match d\'exhibition',
            default => ucfirst($this->type),
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return match($this->format) {
            'round_robin' => 'Aller-retour',
            'knockout' => 'Élimination directe',
            'mixed' => 'Mixte',
            'single_round' => 'Match unique',
            'group_stage' => 'Phase de groupes + élimination',
            default => ucfirst($this->format),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
} 