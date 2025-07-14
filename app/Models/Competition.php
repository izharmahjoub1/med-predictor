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
        'type',
        'season',
        'start_date',
        'end_date',
        'description',
        'max_teams',
        'format',
        'status',
        'association_id',
        'fifa_connect_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_teams' => 'integer',
    ];

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    public function fifaConnectId(): BelongsTo
    {
        return $this->belongsTo(FifaConnectId::class, 'fifa_connect_id');
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'competition_club')
            ->withTimestamps();
    }

    public function matches()
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
            'league' => 'League',
            'cup' => 'Cup',
            'friendly' => 'Friendly',
            'international' => 'International',
            default => ucfirst($this->type),
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return match($this->format) {
            'round_robin' => 'Round Robin',
            'knockout' => 'Knockout',
            'mixed' => 'Mixed',
            default => ucfirst($this->format),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
} 