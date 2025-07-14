@extends('layouts.app')

@section('title', 'Nouveau Dossier Médical - Med Predictor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🏥 Nouveau Dossier Médical</h1>
            <p class="text-gray-600 mt-2">Créer un nouveau dossier médical avec prédiction automatique</p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Informations du Patient</h2>
            </div>
            
            <form action="{{ route('legacy.health-records.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sélection du joueur -->
                    <div class="md:col-span-2">
                        <label for="player_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Joueur (optionnel)
                        </label>
                        <select name="player_id" id="player_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner un joueur</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                    {{ $player->full_name }} - {{ $player->nationality }}
                                </option>
                            @endforeach
                        </select>
                        @error('player_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date d'enregistrement -->
                    <div>
                        <label for="record_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'enregistrement *
                        </label>
                        <input type="date" name="record_date" id="record_date" 
                               value="{{ old('record_date', now()->format('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('record_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prochaine visite -->
                    <div>
                        <label for="next_checkup_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Prochaine visite
                        </label>
                        <input type="date" name="next_checkup_date" id="next_checkup_date" 
                               value="{{ old('next_checkup_date') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('next_checkup_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Signes Vitaux</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Pression artérielle -->
                        <div>
                            <label for="blood_pressure_systolic" class="block text-sm font-medium text-gray-700 mb-2">
                                Pression systolique (mmHg)
                            </label>
                            <input type="number" name="blood_pressure_systolic" id="blood_pressure_systolic" 
                                   value="{{ old('blood_pressure_systolic') }}" min="70" max="200"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('blood_pressure_systolic')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="blood_pressure_diastolic" class="block text-sm font-medium text-gray-700 mb-2">
                                Pression diastolique (mmHg)
                            </label>
                            <input type="number" name="blood_pressure_diastolic" id="blood_pressure_diastolic" 
                                   value="{{ old('blood_pressure_diastolic') }}" min="40" max="130"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('blood_pressure_diastolic')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="heart_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Rythme cardiaque (bpm)
                            </label>
                            <input type="number" name="heart_rate" id="heart_rate" 
                                   value="{{ old('heart_rate') }}" min="40" max="200"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('heart_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Température -->
                        <div>
                            <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">
                                Température (°C)
                            </label>
                            <input type="number" name="temperature" id="temperature" step="0.1"
                                   value="{{ old('temperature') }}" min="35" max="42"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('temperature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Poids -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                Poids (kg)
                            </label>
                            <input type="number" name="weight" id="weight" step="0.1"
                                   value="{{ old('weight') }}" min="30" max="200"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Taille -->
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                                Taille (cm)
                            </label>
                            <input type="number" name="height" id="height"
                                   value="{{ old('height') }}" min="100" max="250"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Médicales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Groupe sanguin -->
                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Groupe sanguin
                            </label>
                            <select name="blood_type" id="blood_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionner</option>
                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('blood_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Allergies -->
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                Allergies (séparées par des virgules)
                            </label>
                            <input type="text" name="allergies" id="allergies" 
                                   value="{{ old('allergies') }}"
                                   placeholder="Ex: Pénicilline, Arachides, Latex"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('allergies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Médicaments -->
                    <div class="mt-6">
                        <label for="medications" class="block text-sm font-medium text-gray-700 mb-2">
                            Médicaments actuels (séparés par des virgules)
                        </label>
                        <input type="text" name="medications" id="medications" 
                               value="{{ old('medications') }}"
                               placeholder="Ex: Aspirine, Vitamine D, Oméga-3"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('medications')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Antécédents médicaux -->
                    <div class="mt-6">
                        <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">
                            Antécédents médicaux (séparés par des virgules)
                        </label>
                        <input type="text" name="medical_history" id="medical_history" 
                               value="{{ old('medical_history') }}"
                               placeholder="Ex: Diabète, Hypertension, Chirurgie cardiaque"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('medical_history')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Symptômes -->
                    <div class="mt-6">
                        <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">
                            Symptômes actuels (séparés par des virgules)
                        </label>
                        <input type="text" name="symptoms" id="symptoms" 
                               value="{{ old('symptoms') }}"
                               placeholder="Ex: Fatigue, Maux de tête, Essoufflement"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('symptoms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Diagnostic -->
                    <div class="mt-6">
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                            Diagnostic
                        </label>
                        <textarea name="diagnosis" id="diagnosis" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Diagnostic médical...">{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plan de traitement -->
                    <div class="mt-6">
                        <label for="treatment_plan" class="block text-sm font-medium text-gray-700 mb-2">
                            Plan de traitement
                        </label>
                        <textarea name="treatment_plan" id="treatment_plan" rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Plan de traitement recommandé...">{{ old('treatment_plan') }}</textarea>
                        @error('treatment_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('legacy.health-records.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                        Créer le dossier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 