<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Association extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'country',
        'confederation',
        'fifa_ranking',
        'association_logo_url',
        'nation_flag_url',
        'fifa_version',
        'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'fifa_ranking' => 'integer'
    ];

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' (' . $this->country . ')';
    }

    // Logo and Image Methods
    public function getLogoUrl(): string
    {
        if ($this->association_logo_url) {
            return $this->association_logo_url;
        }
        
        // Return a default association logo
        return asset('images/defaults/association-logo.png');
    }

    public function getFlagUrl(): string
    {
        if ($this->nation_flag_url) {
            return $this->nation_flag_url;
        }
        
        // Return a default flag based on country
        return asset('images/defaults/flag.png');
    }

    public function getLogoAlt(): string
    {
        return $this->name . ' Association Logo';
    }

    public function getFlagAlt(): string
    {
        return $this->country . ' Flag';
    }

    public function hasLogo(): bool
    {
        return !empty($this->association_logo_url);
    }

    public function hasFlag(): bool
    {
        return !empty($this->nation_flag_url);
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
