@extends('layouts.app')

@section('title', 'Nouveau Dossier Médical - Med Predictor')

@push('scripts')
<!-- Vue.js for TabView Component -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="{{ asset('js/components/TabView.js') }}"></script>
@endpush

<style>
/* Tab Styles */
.tab-view-container {
    @apply w-full;
}

.tabs-nav {
    @apply flex border-b-2 border-gray-200 bg-white rounded-t-lg overflow-hidden shadow-sm;
}

.tab-button {
    @apply flex items-center space-x-2 px-6 py-4 border-none bg-transparent cursor-pointer transition-all duration-200 font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50;
}

.tab-button.active {
    @apply text-blue-600 border-b-2 border-blue-600 bg-blue-50 font-semibold;
}

.tab-icon {
    @apply text-lg;
}

.tab-label {
    @apply text-sm;
}

.tab-content {
    @apply bg-white border border-gray-200 rounded-b-lg shadow-sm;
}

.tab-panel {
    @apply p-6;
}

/* Dental Chart Styles */
.dental-chart-container {
    position: relative;
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chart-header {
    margin-bottom: 20px;
}

.chart-wrapper {
    display: flex;
    justify-content: center;
    margin: 20px 0;
    overflow-x: auto;
}

.dental-svg-container {
    position: relative;
    min-width: 800px;
}

.dental-tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 12px;
    pointer-events: none;
    white-space: nowrap;
    z-index: 1000;
    display: none;
}

.tooltip-content {
    line-height: 1.4;
}

/* SVG Styles */
.tooth {
    cursor: pointer;
    transition: all 0.2s ease;
}

.tooth:hover {
    opacity: 0.8;
}

.tooth-surface {
    cursor: pointer;
    transition: fill 0.2s ease;
}

.status-healthy {
    fill: #ffffff;
    stroke: #cccccc;
}

.status-caries {
    fill: #ff4d4d;
    stroke: #cc0000;
}

.status-restoration {
    fill: #4d94ff;
    stroke: #0066cc;
}

.status-crown {
    stroke: #4d94ff;
    stroke-width: 3px;
    fill-opacity: 0.1;
}

.status-missing {
    fill: #808080;
    opacity: 0.5;
}

/* Postural Assessment Styles */
.postural-chart-container {
    @apply bg-white rounded-lg shadow-sm;
}

.toolbar {
    @apply bg-white border-b border-gray-200 p-4;
}

.chart-area {
    @apply bg-gray-50 p-4;
}

/* Responsive design */
@media (max-width: 768px) {
    .tabs-nav {
        @apply flex-wrap;
    }
    
    .tab-button {
        @apply px-4 py-3 text-xs;
    }
    
    .tab-icon {
        @apply text-base;
    }
    
    .tab-label {
        @apply hidden sm:inline;
    }
}
</style>

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🏥 Nouvelle Visite Médicale</h1>
            <p class="text-gray-600 mt-2">Enregistrer une nouvelle visite médicale - les données seront ajoutées au dossier existant du joueur</p>
        </div>

        <form action="{{ route('health-records.store') }}" method="POST" class="space-y-8">
            @csrf
            
            @if($visit)
                <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">🏥 Visite Associée</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-800">Athlète:</span>
                            <span class="text-blue-700">{{ $visit->athlete->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Date de visite:</span>
                            <span class="text-blue-700">{{ $visit->visit_date->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Type:</span>
                            <span class="text-blue-700">{{ ucfirst(str_replace('_', ' ', $visit->visit_type)) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Médecin:</span>
                            <span class="text-blue-700">{{ $visit->doctor->name ?? 'Non assigné' }}</span>
                        </div>
                        @if($visit->administrative_data && isset($visit->administrative_data['complaint_data']))
                        <div class="md:col-span-2">
                            <span class="font-medium text-blue-800">Motif de consultation:</span>
                            <span class="text-blue-700">{{ $visit->administrative_data['complaint_data']['complaint'] ?? 'Non spécifié' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Interface à Onglets -->
            <div id="health-records-tabs" class="bg-white rounded-lg shadow-lg">
                <!-- Barre de navigation des onglets -->
                <div class="tabs-nav">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id" 
                        :class="{ 
                            'active': activeTab === tab.id,
                            'tab-button': true
                        }"
                        class="tab-button"
                    >
                        <span class="tab-icon">@{{ tab.icon }}</span>
                        <span class="tab-label">@{{ tab.label }}</span>
                    </button>
                </div>

                <!-- Contenu des onglets -->
                <div class="tab-content">
                    <!-- Onglet 1: Informations Générales -->
                    <div v-show="activeTab === 'general'" class="tab-panel">
                        @include('health-records.partials.tab-general')
                    </div>

                    <!-- Onglet 2: Assistant IA -->
                    <div v-show="activeTab === 'ai-assistant'" class="tab-panel">
                        @include('health-records.partials.tab-ai-assistant')
                    </div>

                    <!-- Onglet 3: Catégories Médicales -->
                    <div v-show="activeTab === 'medical-categories'" class="tab-panel">
                        @include('health-records.partials.tab-medical-categories')
                    </div>

                    <!-- Onglet 4: Contrôle Anti-Dopage -->
                    <div v-show="activeTab === 'doping-control'" class="tab-panel">
                        @include('health-records.partials.tab-doping-control')
                    </div>

                    <!-- Onglet 5: Évaluations Physiques -->
                    <div v-show="activeTab === 'physical-assessments'" class="tab-panel">
                        @include('health-records.partials.tab-physical-assessments')
                    </div>

                    <!-- Onglet 6: Évaluation Posturale -->
                    <div v-show="activeTab === 'postural-assessment'" class="tab-panel">
                        @include('health-records.partials.tab-postural-assessment')
                    </div>

                    <!-- Onglet 7: Vaccinations -->
                    <div v-show="activeTab === 'vaccinations'" class="tab-panel">
                        @include('health-records.partials.tab-vaccinations')
                    </div>

                    <!-- Onglet 8: Imagerie Médicale -->
                    <div v-show="activeTab === 'medical-imaging'" class="tab-panel">
                        @include('health-records.partials.tab-medical-imaging')
                    </div>

                    <!-- Onglet 9: Notes et Observations -->
                    <div v-show="activeTab === 'notes-observations'" class="tab-panel">
                        @include('health-records.partials.tab-notes-observations')
                    </div>
                </div>
            </div>

            <!-- Boutons de soumission -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <button 
                    type="button" 
                    onclick="history.back()"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
                >
                    ← Retour
                </button>
                
                <div class="flex space-x-4">
                    <button 
                        type="button" 
                        id="save-draft-btn"
                        class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors"
                    >
                        💾 Sauvegarder brouillon
                    </button>
                    
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        ✅ Enregistrer la visite
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Configuration des onglets
const tabs = [
    { id: 'general', label: 'Informations Générales', icon: '👤' },
    { id: 'ai-assistant', label: 'Assistant IA', icon: '🤖' },
    { id: 'medical-categories', label: 'Catégories Médicales', icon: '🏥' },
    { id: 'doping-control', label: 'Contrôle Anti-Dopage', icon: '🧪' },
    { id: 'physical-assessments', label: 'Évaluations Physiques', icon: '💪' },
    { id: 'postural-assessment', label: 'Évaluation Posturale', icon: '🦴' },
    { id: 'vaccinations', label: 'Vaccinations', icon: '💉' },
    { id: 'medical-imaging', label: 'Imagerie Médicale', icon: '📷' },
    { id: 'notes-observations', label: 'Notes et Observations', icon: '📝' }
];

// Initialisation de Vue.js
const { createApp, ref } = Vue;

createApp({
    setup() {
        const activeTab = ref('general');

        return {
            tabs,
            activeTab
        };
    }
}).mount('#health-records-tabs');
</script>

@endsection 