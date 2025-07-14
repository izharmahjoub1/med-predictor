<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthRecord extends Model
{
    protected $fillable = [
        'user_id',
        'player_id',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'temperature',
        'weight',
        'height',
        'bmi',
        'blood_type',
        'allergies',
        'medications',
        'medical_history',
        'symptoms',
        'diagnosis',
        'treatment_plan',
        'risk_score',
        'prediction_confidence',
        'record_date',
        'next_checkup_date',
        'status'
    ];

    protected $casts = [
        'record_date' => 'datetime',
        'next_checkup_date' => 'datetime',
        'allergies' => 'array',
        'medications' => 'array',
        'medical_history' => 'array',
        'symptoms' => 'array',
        'risk_score' => 'float',
        'prediction_confidence' => 'float',
        'blood_pressure_systolic' => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'heart_rate' => 'integer',
        'temperature' => 'float',
        'weight' => 'float',
        'height' => 'float',
        'bmi' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(MedicalPrediction::class);
    }

    public function getRiskLevelAttribute(): string
    {
        if ($this->risk_score < 0.3) return 'Faible';
        if ($this->risk_score < 0.6) return 'Modéré';
        return 'Élevé';
    }

    public function getBmiCategoryAttribute(): string
    {
        if ($this->bmi < 18.5) return 'Insuffisance pondérale';
        if ($this->bmi < 25) return 'Normal';
        if ($this->bmi < 30) return 'Surpoids';
        return 'Obésité';
    }
}
