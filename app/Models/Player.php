<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'fifa_connect_id',
        'name',
        'first_name',
        'last_name',
        'date_of_birth',
        'nationality',
        'position',
        'club_id',
        'association_id',
        'height',
        'weight',
        'preferred_foot',
        'weak_foot',
        'skill_moves',
        'international_reputation',
        'work_rate',
        'body_type',
        'real_face',
        'release_clause_eur',
        'player_face_url',
        'club_logo_url',
        'nation_flag_url',
        'overall_rating',
        'potential_rating',
        'value_eur',
        'wage_eur',
        'age',
        'contract_valid_until',
        'fifa_version',
        'last_updated'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'contract_valid_until' => 'date',
        'last_updated' => 'datetime',
        'height' => 'integer',
        'weight' => 'integer',
        'weak_foot' => 'integer',
        'skill_moves' => 'integer',
        'international_reputation' => 'integer',
        'release_clause_eur' => 'float',
        'overall_rating' => 'integer',
        'potential_rating' => 'integer',
        'value_eur' => 'float',
        'wage_eur' => 'float',
        'age' => 'integer',
        'real_face' => 'boolean'
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    public function fifaConnectId(): BelongsTo
    {
        return $this->belongsTo(FifaConnectId::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function medicalPredictions(): HasMany
    {
        return $this->hasMany(MedicalPrediction::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(\App\Models\PlayerLicense::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getBmiAttribute(): float
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return 0;
    }

    public function getBmiCategoryAttribute(): string
    {
        $bmi = $this->bmi;
        if ($bmi < 18.5) return 'Insuffisance pondérale';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Surpoids';
        return 'Obésité';
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }
}
