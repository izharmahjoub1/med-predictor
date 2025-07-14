<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Player;
use App\Models\MedicalPrediction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HealthRecordController extends Controller
{
    public function index(): View
    {
        $healthRecords = HealthRecord::with(['user', 'player'])
            ->orderBy('record_date', 'desc')
            ->paginate(15);

        return view('health-records.index', compact('healthRecords'));
    }

    public function create(): View
    {
        $players = Player::orderBy('name')->get();
        return view('health-records.create', compact('players'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => 'nullable|exists:players,id',
            'blood_pressure_systolic' => 'nullable|integer|min:70|max:200',
            'blood_pressure_diastolic' => 'nullable|integer|min:40|max:130',
            'heart_rate' => 'nullable|integer|min:40|max:200',
            'temperature' => 'nullable|numeric|min:35|max:42',
            'weight' => 'nullable|numeric|min:30|max:200',
            'height' => 'nullable|numeric|min:100|max=250',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|array',
            'medications' => 'nullable|array',
            'medical_history' => 'nullable|array',
            'symptoms' => 'nullable|array',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'record_date' => 'required|date',
            'next_checkup_date' => 'nullable|date|after:record_date',
        ]);

        // Calculer le BMI si poids et taille sont fournis
        if (isset($validated['weight']) && isset($validated['height'])) {
            $heightInMeters = $validated['height'] / 100;
            $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        $healthRecord = HealthRecord::create($validated);

        // Générer une prédiction médicale automatiquement
        $this->generateMedicalPrediction($healthRecord);

        return redirect()->route('health-records.show', $healthRecord)
            ->with('success', 'Dossier médical créé avec succès et prédiction générée.');
    }

    public function show(HealthRecord $healthRecord): View
    {
        $healthRecord->load(['user', 'player', 'predictions']);
        
        return view('health-records.show', compact('healthRecord'));
    }

    public function edit(HealthRecord $healthRecord): View
    {
        $players = Player::orderBy('name')->get();
        return view('health-records.edit', compact('healthRecord', 'players'));
    }

    public function update(Request $request, HealthRecord $healthRecord): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => 'nullable|exists:players,id',
            'blood_pressure_systolic' => 'nullable|integer|min:70|max:200',
            'blood_pressure_diastolic' => 'nullable|integer|min:40|max:130',
            'heart_rate' => 'nullable|integer|min:40|max:200',
            'temperature' => 'nullable|numeric|min:35|max:42',
            'weight' => 'nullable|numeric|min:30|max:200',
            'height' => 'nullable|numeric|min:100|max=250',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|array',
            'medications' => 'nullable|array',
            'medical_history' => 'nullable|array',
            'symptoms' => 'nullable|array',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'record_date' => 'required|date',
            'next_checkup_date' => 'nullable|date|after:record_date',
        ]);

        // Recalculer le BMI si nécessaire
        if (isset($validated['weight']) && isset($validated['height'])) {
            $heightInMeters = $validated['height'] / 100;
            $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
        }

        $healthRecord->update($validated);

        return redirect()->route('health-records.show', $healthRecord)
            ->with('success', 'Dossier médical mis à jour avec succès.');
    }

    public function destroy(HealthRecord $healthRecord): RedirectResponse
    {
        $healthRecord->delete();
        return redirect()->route('health-records.index')
            ->with('success', 'Dossier médical supprimé avec succès.');
    }

    public function generatePrediction(HealthRecord $healthRecord): JsonResponse
    {
        $prediction = $this->generateMedicalPrediction($healthRecord);
        
        return response()->json([
            'success' => true,
            'prediction' => $prediction,
            'message' => 'Prédiction médicale générée avec succès.'
        ]);
    }

    private function generateMedicalPrediction(HealthRecord $healthRecord): MedicalPrediction
    {
        // Logique de prédiction médicale basée sur les données
        $riskFactors = $this->calculateRiskFactors($healthRecord);
        $predictedCondition = $this->predictCondition($healthRecord);
        $riskProbability = $this->calculateRiskProbability($riskFactors);
        $confidenceScore = $this->calculateConfidenceScore($healthRecord);

        return MedicalPrediction::create([
            'health_record_id' => $healthRecord->id,
            'player_id' => $healthRecord->player_id,
            'user_id' => auth()->id(),
            'prediction_type' => 'health_condition',
            'predicted_condition' => $predictedCondition,
            'risk_probability' => $riskProbability,
            'confidence_score' => $confidenceScore,
            'prediction_factors' => $riskFactors,
            'recommendations' => $this->generateRecommendations($riskFactors, $predictedCondition),
            'prediction_date' => now(),
            'valid_until' => now()->addDays(30),
            'status' => 'active',
            'ai_model_version' => '1.0',
            'prediction_notes' => [
                'generated_at' => now()->toISOString(),
                'model_version' => '1.0',
                'data_points_analyzed' => count($riskFactors)
            ]
        ]);
    }

    private function calculateRiskFactors(HealthRecord $healthRecord): array
    {
        $factors = [];

        // Analyse de la pression artérielle
        if ($healthRecord->blood_pressure_systolic && $healthRecord->blood_pressure_diastolic) {
            if ($healthRecord->blood_pressure_systolic > 140 || $healthRecord->blood_pressure_diastolic > 90) {
                $factors[] = 'hypertension';
            }
        }

        // Analyse du rythme cardiaque
        if ($healthRecord->heart_rate) {
            if ($healthRecord->heart_rate > 100) {
                $factors[] = 'tachycardie';
            } elseif ($healthRecord->heart_rate < 60) {
                $factors[] = 'bradycardie';
            }
        }

        // Analyse du BMI
        if ($healthRecord->bmi) {
            if ($healthRecord->bmi > 30) {
                $factors[] = 'obésité';
            } elseif ($healthRecord->bmi > 25) {
                $factors[] = 'surpoids';
            } elseif ($healthRecord->bmi < 18.5) {
                $factors[] = 'insuffisance_pondérale';
            }
        }

        // Analyse de la température
        if ($healthRecord->temperature) {
            if ($healthRecord->temperature > 38) {
                $factors[] = 'fièvre';
            }
        }

        return $factors;
    }

    private function predictCondition(HealthRecord $healthRecord): string
    {
        $riskFactors = $this->calculateRiskFactors($healthRecord);
        
        if (in_array('hypertension', $riskFactors)) {
            return 'Risque cardiovasculaire';
        }
        
        if (in_array('obésité', $riskFactors)) {
            return 'Risque métabolique';
        }
        
        if (in_array('fièvre', $riskFactors)) {
            return 'Infection possible';
        }
        
        if (empty($riskFactors)) {
            return 'État de santé normal';
        }
        
        return 'Surveillance recommandée';
    }

    private function calculateRiskProbability(array $riskFactors): float
    {
        $baseRisk = 0.1;
        $riskPerFactor = 0.15;
        
        return min(1.0, $baseRisk + (count($riskFactors) * $riskPerFactor));
    }

    private function calculateConfidenceScore(HealthRecord $healthRecord): float
    {
        $dataPoints = 0;
        $totalPoints = 8; // Nombre total de points de données possibles
        
        if ($healthRecord->blood_pressure_systolic) $dataPoints++;
        if ($healthRecord->blood_pressure_diastolic) $dataPoints++;
        if ($healthRecord->heart_rate) $dataPoints++;
        if ($healthRecord->temperature) $dataPoints++;
        if ($healthRecord->weight) $dataPoints++;
        if ($healthRecord->height) $dataPoints++;
        if ($healthRecord->blood_type) $dataPoints++;
        if ($healthRecord->medical_history) $dataPoints++;
        
        return round($dataPoints / $totalPoints, 4);
    }

    private function generateRecommendations(array $riskFactors, string $predictedCondition): array
    {
        $recommendations = [];
        
        if (in_array('hypertension', $riskFactors)) {
            $recommendations[] = 'Surveillance régulière de la pression artérielle';
            $recommendations[] = 'Consultation cardiologique recommandée';
        }
        
        if (in_array('obésité', $riskFactors)) {
            $recommendations[] = 'Programme de perte de poids supervisé';
            $recommendations[] = 'Consultation nutritionnelle';
        }
        
        if (in_array('fièvre', $riskFactors)) {
            $recommendations[] = 'Surveillance de la température';
            $recommendations[] = 'Consultation médicale si persistance';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Maintenir les bonnes pratiques de santé';
            $recommendations[] = 'Contrôle médical annuel recommandé';
        }
        
        return $recommendations;
    }
}
