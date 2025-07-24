<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'start_date',
        'end_date',
        'registration_start_date',
        'registration_end_date',
        'status',
        'is_current',
        'description',
        'settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start_date' => 'date',
        'registration_end_date' => 'date',
        'is_current' => 'boolean',
        'settings' => 'array',
    ];

    // Relationships
    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCurrent(): bool
    {
        return $this->is_current;
    }

    public function isRegistrationOpen(): bool
    {
        $now = now();
        return $now->between($this->registration_start_date, $this->registration_end_date);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'upcoming' => 'blue',
            'active' => 'green',
            'completed' => 'gray',
            'archived' => 'red',
            default => 'gray',
        };
    }

    public function getDurationAttribute(): string
    {
        return $this->start_date->format('M Y') . ' - ' . $this->end_date->format('M Y');
    }

    public function getCompetitionsCountAttribute(): int
    {
        return $this->competitions()->count();
    }

    public function getPlayersCountAttribute(): int
    {
        return $this->players()->count();
    }

    // Static methods
    public static function getCurrentSeason(): ?self
    {
        return static::current()->first();
    }

    public static function createNewSeason(array $data): self
    {
        // Deactivate current season if exists
        static::current()->update(['is_current' => false]);

        // Create new season
        $season = static::create($data);

        // Set as current
        $season->update(['is_current' => true]);

        return $season;
    }

    // Audit trail methods
    public function getAuditIdentifier(): string
    {
        return "Season #{$this->id} ({$this->name})";
    }

    public function getAuditData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'status' => $this->status,
            'is_current' => $this->is_current,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
        ];
    }
} 