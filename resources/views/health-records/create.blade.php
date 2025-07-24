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
            
            <div class="p-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Fonctionnalité en développement
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>La création de dossiers médicaux est actuellement en cours de développement. Cette fonctionnalité sera bientôt disponible.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('health-records.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        ← Retour à la liste
                    </a>
                    
                    <button disabled class="bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                        Création temporairement indisponible
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 