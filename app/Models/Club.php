<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'country',
        'city',
        'stadium',
        'founded_year',
        'logo_url',
        'website',
        'email',
        'phone',
        'address',
        'fifa_connect_id',
        'association_id',
        'league',
        'division',
        'status',
        'budget_eur',
        'wage_budget_eur',
        'transfer_budget_eur',
        'reputation',
        'facilities_level',
        'youth_development',
        'scouting_network',
        'medical_team',
        'coaching_staff',
        'last_updated',
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'budget_eur' => 'decimal:2',
        'wage_budget_eur' => 'decimal:2',
        'transfer_budget_eur' => 'decimal:2',
        'reputation' => 'integer',
        'facilities_level' => 'integer',
        'youth_development' => 'integer',
        'scouting_network' => 'integer',
        'medical_team' => 'integer',
        'coaching_staff' => 'integer',
        'last_updated' => 'datetime',
    ];

    // Relationships
    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function lineups(): HasMany
    {
        return $this->hasMany(Lineup::class);
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function playerLicenses(): HasMany
    {
        return $this->hasMany(PlayerLicense::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class, 'entity_id')->where('entity_type', 'club');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByLeague($query, $league)
    {
        return $query->where('league', $league);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // Methods
    public function getTotalSquadValue(): float
    {
        $sum = $this->players()->sum('value_eur');
        return $sum ?? 0.0;
    }

    public function getAverageSquadRating(): float
    {
        $average = $this->players()->avg('overall_rating');
        return $average ?? 0.0;
    }

    public function getSquadSize(): int
    {
        return $this->players()->count();
    }

    public function getAvailableBudget(): float
    {
        return $this->budget_eur - $this->getTotalSquadValue();
    }

    public function getWageSpending(): float
    {
        $sum = $this->players()->sum('wage_eur');
        return $sum ?? 0.0;
    }

    public function getRemainingWageBudget(): float
    {
        return $this->wage_budget_eur - $this->getWageSpending();
    }

    public function canAffordPlayer(Player $player): bool
    {
        return $this->getAvailableBudget() >= $player->value_eur &&
               $this->getRemainingWageBudget() >= $player->wage_eur;
    }

    public function getSquadByPosition(string $position): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->where('position', $position)->orderBy('overall_rating', 'desc')->get();
    }

    public function getBestPlayers(int $limit = 11): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->orderBy('overall_rating', 'desc')->limit($limit)->get();
    }

    public function getYouthPlayers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->whereRaw('YEAR(CURDATE()) - YEAR(date_of_birth) <= 21')->get();
    }

    public function getExperiencedPlayers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->whereRaw('YEAR(CURDATE()) - YEAR(date_of_birth) >= 28')->get();
    }

    public function getInjuredPlayers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->whereHas('healthRecords', function ($query) {
            $query->where('status', 'injured');
        })->get();
    }

    public function getAvailablePlayers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->players()->whereDoesntHave('healthRecords', function ($query) {
            $query->where('status', 'injured');
        })->get();
    }

    // Logo and Image Methods
    public function getLogoUrl(): string
    {
        if ($this->logo_url) {
            return $this->logo_url;
        }
        
        // Return a default club logo based on the club name or country
        return asset('images/defaults/club-logo.png');
    }

    public function getLogoAlt(): string
    {
        return $this->name . ' Logo';
    }

    public function hasLogo(): bool
    {
        return !empty($this->logo_url);
    }

    public function getDisplayName(): string
    {
        return $this->short_name ?: $this->name;
    }

    public function getFullDisplayName(): string
    {
        $display = $this->name;
        if ($this->country) {
            $display .= ' (' . $this->country . ')';
        }
        return $display;
    }
}
