@extends('layouts.app')

@section('title', 'Dashboard - Med Predictor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête du dashboard avec logos -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📊 Dashboard</h1>
                <p class="text-gray-600 mt-2">Vue d'ensemble de Med Predictor</p>
            </div>
            
            <!-- Club and Association Logos -->
            <div class="flex items-center space-x-6">
                @if($association)
                <div class="text-center">
                    <div class="w-24 h-24 bg-white rounded-lg shadow-lg p-3 border-2 border-gray-200 flex items-center justify-center mb-2">
                        @if($association->association_logo_url)
                            <img src="{{ $association->association_logo_url }}" 
                                 alt="{{ $association->name }} Logo" 
                                 class="w-full h-full object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full bg-blue-100 rounded flex items-center justify-center text-blue-600 font-bold text-lg" style="display: none;">
                                {{ substr($association->name, 0, 2) }}
                            </div>
                        @else
                            <div class="w-full h-full bg-blue-100 rounded flex items-center justify-center text-blue-600 font-bold text-lg">
                                {{ substr($association->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-700">{{ $association->name }}</p>
                </div>
                @endif
                
                @if($club)
                <div class="text-center">
                    <div class="w-24 h-24 bg-white rounded-lg shadow-lg p-3 border-2 border-gray-200 flex items-center justify-center mb-2">
                        @if($club->club_logo_url)
                            <img src="{{ $club->club_logo_url }}" 
                                 alt="{{ $club->name }} Logo" 
                                 class="w-full h-full object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full bg-red-100 rounded flex items-center justify-center text-red-600 font-bold text-lg" style="display: none;">
                                {{ substr($club->name, 0, 2) }}
                            </div>
                        @else
                            <div class="w-full h-full bg-red-100 rounded flex items-center justify-center text-red-600 font-bold text-lg">
                                {{ substr($club->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-700">{{ $club->name }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- User Info Card with Logos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Welcome, {{ auth()->user()->name }}!</h3>
                        <p class="text-gray-600">{{ auth()->user()->getRoleDisplay() }}</p>
                        @if($club)
                            <p class="text-sm text-gray-500">Club: {{ $club->name }}</p>
                        @endif
                        @if($association)
                            <p class="text-sm text-gray-500">Association: {{ $association->name }}</p>
                        @endif
                        <p class="text-sm text-gray-500">FIFA Connect ID: {{ auth()->user()->fifa_connect_id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Last Login</div>
                    <div class="text-sm font-medium">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'First time' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Joueurs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_players'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Dossiers Médicaux</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_health_records'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Prédictions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_predictions'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Utilisateurs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique des dossiers par statut -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dossiers par Statut</h3>
            <div class="space-y-3">
                @foreach(['active' => 'green', 'archived' => 'gray', 'pending' => 'yellow'] as $status => $color)
                    @php
                        $count = $healthRecordsByStatus[$status] ?? 0;
                        $total = array_sum($healthRecordsByStatus);
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ ucfirst($status) }}</span>
                            <span class="text-gray-500">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Graphique des prédictions par type -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Prédictions par Type</h3>
            <div class="space-y-3">
                @foreach(['Blessure' => 'blue', 'Performance' => 'green', 'Récupération' => 'purple', 'Prévention' => 'orange'] as $type => $color)
                    @php
                        $count = $predictionsByType[$type] ?? 0;
                        $total = array_sum($predictionsByType);
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">{{ $type }}</span>
                            <span class="text-gray-500">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Alertes médicales -->
    @if($medicalAlerts->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">🚨 Alertes Médicales Récentes</h3>
                <span class="text-sm text-red-600 font-medium">{{ $medicalAlerts->count() }} alertes</span>
            </div>
            <div class="space-y-3">
                @foreach($medicalAlerts as $alert)
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-medium text-red-800">{{ $alert->player->full_name ?? 'N/A' }}</h4>
                                <p class="text-sm text-red-600">{{ $alert->diagnosis ?? 'Aucun diagnostic' }}</p>
                                <p class="text-xs text-red-500">{{ $alert->record_date->format('d/m/Y') }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Risque: {{ number_format($alert->risk_score * 100, 1) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top joueurs par dossiers -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 - Joueurs par Dossiers</h3>
            <div class="space-y-3">
                @foreach($topPlayersByRecords as $player)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold text-sm">
                                    {{ substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $player->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $player->position }} • {{ $player->nationality }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-blue-600">{{ $player->health_records_count }} dossiers</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top prédictions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 - Prédictions les Plus Fiables</h3>
            <div class="space-y-3">
                @foreach($topPredictions as $prediction)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $prediction->prediction_type }}</p>
                            <p class="text-xs text-gray-500">{{ $prediction->healthRecord->player->full_name ?? 'N/A' }}</p>
                        </div>
                        <span class="text-sm font-semibold text-green-600">{{ number_format($prediction->confidence_score * 100, 1) }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Graphique des tendances mensuelles -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tendances Mensuelles</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($monthlyStats as $stat)
                @php
                    $maxHealthRecords = max(array_column($monthlyStats, 'health_records'));
                    $maxPredictions = max(array_column($monthlyStats, 'predictions'));
                    $healthHeight = $maxHealthRecords > 0 ? max(10, ($stat['health_records'] / $maxHealthRecords) * 200) : 10;
                    $predictionHeight = $maxPredictions > 0 ? max(10, ($stat['predictions'] / $maxPredictions) * 200) : 10;
                @endphp
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-blue-200 rounded-t" style="height: {{ $healthHeight }}px"></div>
                    <div class="w-full bg-green-200 rounded-t" style="height: {{ $predictionHeight }}px"></div>
                    <p class="text-xs text-gray-500 mt-2 text-center">{{ $stat['month'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center space-x-4 mt-4">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-200 rounded mr-2"></div>
                <span class="text-xs text-gray-600">Dossiers</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-200 rounded mr-2"></div>
                <span class="text-xs text-gray-600">Prédictions</span>
            </div>
        </div>
    </div>
</div>

<script>
// Animation des statistiques
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.text-2xl');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        const increment = target / 50;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
});
</script>
@endsection 