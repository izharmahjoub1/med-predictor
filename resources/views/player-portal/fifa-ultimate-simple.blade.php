<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->player->first_name ?? 'Joueur' }} {{ Auth::user()->player->last_name ?? '' }} - FIFA Ultimate Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* FIFA Ultimate Team Styles */
        .fifa-ultimate-card {
            background: linear-gradient(135deg, #1a237e 0%, #303f9f 25%, #3f51b5 50%, #5c6bc0 75%, #9c27b0 100%);
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fifa-ultimate-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 35px 70px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.3);
        }

        .fifa-ultimate-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 50%),
                linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 50%, rgba(255,255,255,0.1) 100%);
            z-index: 1;
        }

        .fifa-card-content {
            position: relative;
            z-index: 2;
        }

        .fifa-rating-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffb300 50%, #ff8f00 100%);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4), inset 0 2px 4px rgba(255,255,255,0.3);
            border: 3px solid #fff;
            position: relative;
        }

        .fifa-rating-badge::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 50%);
            border-radius: 12px;
        }

        .fifa-position-badge {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .fifa-stat-bar {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            overflow: hidden;
            height: 10px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }

        .fifa-stat-fill {
            background: linear-gradient(90deg, #4caf50 0%, #8bc34a 50%, #cddc39 100%);
            height: 100%;
            border-radius: 12px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            position: relative;
        }

        .fifa-stat-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(90deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 100%);
            border-radius: 12px 12px 0 0;
        }

        .player-photo-container {
            position: relative;
            filter: drop-shadow(0 15px 35px rgba(0,0,0,0.4));
        }

        .player-photo {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid rgba(255,255,255,0.3);
        }

        .fifa-ultimate-card:hover .player-photo {
            transform: scale(1.05) rotateY(5deg);
            border-color: rgba(255,255,255,0.5);
        }

        .club-logo, .nation-flag {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }

        .club-logo:hover, .nation-flag:hover {
            transform: scale(1.1);
        }

        .fifa-nav-tab {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .fifa-nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .fifa-nav-tab:hover::before {
            left: 100%;
        }

        .fifa-nav-tab.active {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }

        .fifa-nav-tab:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .health-score-ring {
            stroke-dasharray: 283; /* 2 * π * 45 */
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 2s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 0 8px rgba(76, 175, 80, 0.5));
        }

        .chart-container {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        .floating-animation:nth-child(2) {
            animation-delay: -2s;
        }

        .floating-animation:nth-child(3) {
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(-5px) rotate(-1deg); }
        }

        .pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(255,255,255,0.3), 0 0 40px rgba(76, 175, 80, 0.2);
            }
            50% { 
                box-shadow: 0 0 30px rgba(255,255,255,0.5), 0 0 60px rgba(76, 175, 80, 0.4);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .market-value-badge {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 50%, #e65100 100%);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.3);
        }

        /* Responsive design amélioré */
        @media (max-width: 768px) {
            .fifa-ultimate-card {
                margin: 10px;
                border-radius: 16px;
            }
            
            .player-photo {
                width: 120px !important;
                height: 150px !important;
            }
            
            .fifa-rating-badge {
                width: 60px !important;
                height: 60px !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 min-h-screen">
    <div id="fifa-app" class="container mx-auto px-4 py-8">
        <!-- Navigation retour -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('player-portal.dashboard') }}" class="flex items-center text-white hover:text-blue-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au Dashboard
            </a>
            <div class="flex items-center space-x-4">
                <button @click="refreshData" 
                        :disabled="isLoading"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                    <i class="fas fa-sync-alt mr-2" :class="{'animate-spin': isLoading}"></i>
                    Actualiser
                </button>
                <div class="text-sm text-white/70">
                    {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        @if(Auth::user() && Auth::user()->player)
        <!-- Hero Zone - FIFA Ultimate Team Card -->
        <div class="fifa-ultimate-card p-8 mb-8 text-white">
            <div class="fifa-card-content">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Section Photo & Rating (4 colonnes) -->
                    <div class="lg:col-span-4">
                        <div class="flex flex-col items-center lg:items-start space-y-6">
                            <!-- Photo du joueur avec animations -->
                            <div class="player-photo-container floating-animation">
                                <img src="{{ Auth::user()->player->player_picture ?: 'https://via.placeholder.com/200x250/4285f4/ffffff?text=' . substr(Auth::user()->player->first_name ?? 'J', 0, 1) . substr(Auth::user()->player->last_name ?? 'P', 0, 1) }}" 
                                     alt="Photo de {{ Auth::user()->player->first_name }}"
                                     class="player-photo w-48 h-60 object-cover rounded-2xl pulse-glow">
                                
                                <!-- Rating FIFA en overlay -->
                                <div class="absolute -top-6 -right-6 fifa-rating-badge w-24 h-24 flex items-center justify-center">
                                    <div class="text-center relative z-10">
                                        <div class="text-3xl font-black text-black">{{ Auth::user()->player->overall_rating ?? 75 }}</div>
                                        <div class="text-xs font-bold text-black">OVR</div>
                                    </div>
                                </div>

                                <!-- Position en overlay -->
                                <div class="absolute -bottom-6 -left-6 fifa-position-badge text-gray-800 px-4 py-2 rounded-xl font-bold">
                                    {{ Auth::user()->player->position ?? 'CAM' }}
                                </div>

                                <!-- Badge de potentiel -->
                                <div class="absolute top-2 left-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    POT {{ Auth::user()->player->potential ?? 82 }}
                                </div>
                            </div>

                            <!-- Club et nation -->
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2 floating-animation">
                                    <img src="{{ Auth::user()->player->club->logo_url ?? 'https://via.placeholder.com/48x48/ffffff/4285f4?text=FC' }}" 
                                         alt="Logo club" 
                                         class="club-logo w-12 h-12 object-contain rounded">
                                    <div>
                                        <div class="font-bold text-sm">{{ Auth::user()->player->club->name ?? 'Free Agent' }}</div>
                                        <div class="text-xs opacity-75">{{ Auth::user()->player->club->league ?? 'Ligue 1' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 floating-animation">
                                    <img src="{{ Auth::user()->player->nation_flag_url ?? 'https://via.placeholder.com/32x24/ffffff/4285f4?text=FR' }}" 
                                         alt="Drapeau"
                                         class="nation-flag w-8 h-6 rounded shadow-sm">
                                    <div>
                                        <div class="font-bold text-sm">{{ Auth::user()->player->nationality ?? 'France' }}</div>
                                        <div class="text-xs opacity-75">{{ (Auth::user()->player->international_reputation ?? 1) * 5 }} sél.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Identité & Statistiques FIFA (5 colonnes) -->
                    <div class="lg:col-span-5">
                        <div class="space-y-6">
                            <!-- Nom et informations principales -->
                            <div>
                                <h1 class="text-5xl lg:text-6xl font-black mb-2 leading-none">
                                    {{ strtoupper(Auth::user()->player->last_name ?? 'PLAYER') }}
                                </h1>
                                <h2 class="text-3xl lg:text-4xl font-bold text-white/90 mb-4">
                                    {{ Auth::user()->player->first_name ?? 'First' }}
                                </h2>
                                
                                <!-- Informations détaillées -->
                                <div class="grid grid-cols-4 gap-4 mb-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ Auth::user()->player->age ?? 25 }}</div>
                                        <div class="text-xs opacity-75">ÂGE</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ Auth::user()->player->height ?? 180 }}</div>
                                        <div class="text-xs opacity-75">TAILLE</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ Auth::user()->player->weight ?? 75 }}</div>
                                        <div class="text-xs opacity-75">POIDS</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ Auth::user()->player->weak_foot ?? 3 }}<span class="text-sm">★</span></div>
                                        <div class="text-xs opacity-75">PIED</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Barres de statistiques FIFA avec animations -->
                            <div class="space-y-3">
                                <div v-for="(stat, index) in fifaStats" :key="stat.name" 
                                     class="flex items-center justify-between">
                                    <span class="font-bold text-sm w-8">{{ stat.name }}</span>
                                    <div class="flex-1 mx-3">
                                        <div class="fifa-stat-bar">
                                            <div class="fifa-stat-fill" 
                                                 :style="{ width: stat.animated_value + '%' }"></div>
                                        </div>
                                    </div>
                                    <span class="font-bold text-sm w-8 text-right">{{ stat.value }}</span>
                                </div>
                            </div>

                            <!-- Pied fort et compétences -->
                            <div class="flex items-center justify-between">
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ Auth::user()->player->skill_moves ?? 3 }}<span class="text-yellow-400">★</span></div>
                                    <div class="text-xs opacity-75">GESTES</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ Auth::user()->player->preferred_foot ?? 'Droit' }}</div>
                                    <div class="text-xs opacity-75">PIED FORT</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold">{{ Auth::user()->player->work_rate ?? 'H/M' }}</div>
                                    <div class="text-xs opacity-75">CADENCE</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Score de Santé FIT & Marché (3 colonnes) -->
                    <div class="lg:col-span-3">
                        <div class="text-center space-y-6">
                            <!-- Score de santé FIT avec graphique circulaire -->
                            <div class="bg-white/15 rounded-3xl p-6 backdrop-blur-sm border border-white/20">
                                <div class="text-sm font-bold opacity-75 mb-4">SCORE DE SANTÉ FIT</div>
                                <div class="relative mb-4">
                                    <svg class="w-32 h-32 mx-auto transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="6"/>
                                        <circle cx="50" cy="50" r="45" fill="none" stroke="#4ade80" stroke-width="6" 
                                                class="health-score-ring" 
                                                :style="{ strokeDashoffset: 283 - (283 * {{ Auth::user()->player->ghs_overall_score ?? 85 }} / 100) }"
                                                stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center transform rotate-0">
                                        <div class="text-center">
                                            <div class="text-4xl font-black">{{ Auth::user()->player->ghs_overall_score ?? 85 }}</div>
                                            <div class="text-xs font-bold opacity-75">FIT</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sous-scores de santé -->
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span>💚 Santé</span>
                                        <span class="font-bold">{{ Auth::user()->player->ghs_physical_score ?? 85 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>🧠 Mental</span>
                                        <span class="font-bold">{{ Auth::user()->player->ghs_mental_score ?? 78 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>😴 Sommeil</span>
                                        <span class="font-bold">{{ Auth::user()->player->ghs_sleep_score ?? 81 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>🤝 Social</span>
                                        <span class="font-bold">{{ Auth::user()->player->ghs_civic_score ?? 92 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Risque de blessure -->
                            <div class="bg-white/15 rounded-xl p-4 backdrop-blur-sm border border-white/20">
                                <div class="text-xs font-bold opacity-75 mb-2">RISQUE BLESSURE</div>
                                <div class="text-3xl font-black mb-1 text-green-400">
                                    {{ Auth::user()->player->injury_risk_score ?? 15 }}%
                                </div>
                                <div class="text-xs font-semibold text-green-400">
                                    {{ Auth::user()->player->injury_risk_level ?? 'FAIBLE' }}
                                </div>
                                <div class="mt-2 w-full bg-white/20 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-green-400 transition-all duration-1000" 
                                         style="width: {{ Auth::user()->player->injury_risk_score ?? 15 }}%;"></div>
                                </div>
                            </div>

                            <!-- Valeur marchande -->
                            <div class="market-value-badge rounded-xl p-4 text-white">
                                <div class="text-xs font-bold mb-1">VALEUR ESTIMÉE</div>
                                <div class="text-2xl font-black">€{{ number_format((Auth::user()->player->value_eur ?? 1500000) / 1000000, 1) }}M</div>
                                <div class="text-xs font-semibold">
                                    <span class="text-green-300">
                                        <i class="fas fa-arrow-up"></i> 
                                        +{{ rand(5, 15) }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Disponibilité -->
                            <div class="bg-white/15 rounded-xl p-3 backdrop-blur-sm border border-white/20">
                                <div class="text-xs font-bold opacity-75 mb-1">DISPONIBILITÉ</div>
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                    <span class="text-sm font-bold">{{ Auth::user()->player->match_availability ? 'DISPONIBLE' : 'INDISPONIBLE' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8">
            <div class="flex flex-wrap justify-center space-x-2 p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/20">
                <button v-for="tab in navigationTabs" :key="tab.id" 
                        @click="activeTab = tab.id"
                        :class="['fifa-nav-tab px-6 py-3 rounded-xl font-medium transition-all duration-300', 
                                activeTab === tab.id ? 'active text-white' : 'text-white/80 hover:text-white']">
                    <i :class="tab.icon + ' mr-2'"></i>
                    {{ tab.name }}
                </button>
            </div>
        </div>

        <!-- Contenu des Onglets -->
        <div class="space-y-8">
            <!-- Onglet Performance -->
            <div v-show="activeTab === 'performance'" class="space-y-6">
                <h2 class="text-3xl font-bold text-white gradient-text mb-6">Performance & Évolution</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Graphique d'évolution des performances -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Évolution des Performances</h3>
                        <canvas id="performanceChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Comparaison par rapport à la moyenne -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Comparaison vs Moyenne</h3>
                        <canvas id="comparisonChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Onglet Santé & Bien-être -->
            <div v-show="activeTab === 'health'" class="space-y-6">
                <h2 class="text-3xl font-bold text-white gradient-text mb-6">Santé & Bien-être</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Score de santé dans le temps -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Évolution du Score FIT</h3>
                        <canvas id="healthScoreChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Répartition des scores de santé -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Répartition Santé</h3>
                        <canvas id="healthBreakdownChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Onglet Historique -->
            <div v-show="activeTab === 'history'" class="space-y-6">
                <h2 class="text-3xl font-bold text-white gradient-text mb-6">Historique & Carrière</h2>
                
                <div class="chart-container p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Timeline de Carrière</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="font-bold text-blue-600">2024</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold">{{ Auth::user()->player->club->name ?? 'Club Actuel' }}</div>
                                <div class="text-sm text-gray-600">Saison 2023-24</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="font-bold text-green-600">8</div>
                                    <div class="text-xs text-gray-500">Buts</div>
                                </div>
                                <div>
                                    <div class="font-bold text-blue-600">12</div>
                                    <div class="text-xs text-gray-500">Passes</div>
                                </div>
                                <div>
                                    <div class="font-bold text-purple-600">25</div>
                                    <div class="text-xs text-gray-500">Matchs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Marché & Tendances -->
            <div v-show="activeTab === 'market'" class="space-y-6">
                <h2 class="text-3xl font-bold text-white gradient-text mb-6">Marché & Tendances</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Évolution de la valeur marchande -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Évolution Valeur Marchande</h3>
                        <canvas id="marketValueChart" width="400" height="200"></canvas>
                    </div>

                    <!-- Prédictions futures -->
                    <div class="chart-container p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Prédictions Futures</h3>
                        <canvas id="predictionsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Message d'erreur -->
        <div class="bg-red-600/20 border border-red-500/50 text-white px-6 py-4 rounded-lg backdrop-blur-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-red-400"></i>
                <div>
                    <strong>Erreur d'accès :</strong> Aucun joueur associé à votre compte.
                    <p class="text-sm mt-1 opacity-75">Contactez l'administrateur pour résoudre ce problème.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    activeTab: 'performance',
                    isLoading: false,
                    charts: {},
                    navigationTabs: [
                        { id: 'performance', name: 'Performance', icon: 'fas fa-chart-line' },
                        { id: 'health', name: 'Santé & Bien-être', icon: 'fas fa-heartbeat' },
                        { id: 'history', name: 'Historique', icon: 'fas fa-history' },
                        { id: 'market', name: 'Marché & Tendances', icon: 'fas fa-trending-up' }
                    ],
                    fifaStats: [
                        { name: 'VIT', value: 85, animated_value: 0 },
                        { name: 'TIR', value: 78, animated_value: 0 },
                        { name: 'PAS', value: 82, animated_value: 0 },
                        { name: 'DRI', value: 75, animated_value: 0 },
                        { name: 'DEF', value: 65, animated_value: 0 },
                        { name: 'PHY', value: 80, animated_value: 0 }
                    ]
                }
            },
            mounted() {
                this.initializeAnimations();
                this.$nextTick(() => {
                    this.initializeCharts();
                });
            },
            methods: {
                initializeAnimations() {
                    // Animer les barres de statistiques FIFA
                    setTimeout(() => {
                        this.fifaStats.forEach((stat, index) => {
                            setTimeout(() => {
                                stat.animated_value = stat.value;
                            }, index * 100);
                        });
                    }, 1000);
                },
                
                initializeCharts() {
                    this.createPerformanceChart();
                    this.createComparisonChart();
                    this.createHealthScoreChart();
                    this.createHealthBreakdownChart();
                    this.createMarketValueChart();
                    this.createPredictionsChart();
                },

                createPerformanceChart() {
                    const ctx = document.getElementById('performanceChart');
                    if (!ctx) return;

                    this.charts.performance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                            datasets: [{
                                label: 'Performance Global',
                                data: [75, 78, 76, 82, 85, 88],
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, max: 100 } }
                        }
                    });
                },

                createComparisonChart() {
                    const ctx = document.getElementById('comparisonChart');
                    if (!ctx) return;

                    this.charts.comparison = new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: ['Vitesse', 'Tir', 'Passe', 'Dribble', 'Défense', 'Physique'],
                            datasets: [{
                                label: 'Joueur',
                                data: [85, 78, 82, 75, 65, 80],
                                borderColor: '#8b5cf6',
                                backgroundColor: 'rgba(139, 92, 246, 0.2)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { r: { beginAtZero: true, max: 100 } }
                        }
                    });
                },

                createHealthScoreChart() {
                    const ctx = document.getElementById('healthScoreChart');
                    if (!ctx) return;

                    this.charts.healthScore = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                            datasets: [{
                                label: 'Score FIT Global',
                                data: [82, 85, 83, 87, 89, 85],
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, max: 100 } }
                        }
                    });
                },

                createHealthBreakdownChart() {
                    const ctx = document.getElementById('healthBreakdownChart');
                    if (!ctx) return;

                    this.charts.healthBreakdown = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Santé Physique', 'Santé Mentale', 'Sommeil', 'Social'],
                            datasets: [{
                                data: [{{ Auth::user()->player->ghs_physical_score ?? 85 }}, {{ Auth::user()->player->ghs_mental_score ?? 78 }}, {{ Auth::user()->player->ghs_sleep_score ?? 81 }}, {{ Auth::user()->player->ghs_civic_score ?? 92 }}],
                                backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                },

                createMarketValueChart() {
                    const ctx = document.getElementById('marketValueChart');
                    if (!ctx) return;

                    this.charts.marketValue = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                            datasets: [{
                                label: 'Valeur (millions €)',
                                data: [1.2, 1.35, 1.28, 1.45, 1.5, 1.6],
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                },

                createPredictionsChart() {
                    const ctx = document.getElementById('predictionsChart');
                    if (!ctx) return;

                    this.charts.predictions = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Court terme', 'Moyen terme', 'Long terme'],
                            datasets: [{
                                label: 'Performance Prédite',
                                data: [88, 92, 85],
                                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true, max: 100 } }
                        }
                    });
                },

                refreshData() {
                    this.isLoading = true;
                    setTimeout(() => {
                        this.isLoading = false;
                        this.initializeAnimations();
                        Object.values(this.charts).forEach(chart => {
                            if (chart && chart.update) chart.update();
                        });
                    }, 2000);
                }
            },
            beforeUnmount() {
                Object.values(this.charts).forEach(chart => {
                    if (chart && chart.destroy) chart.destroy();
                });
            }
        }).mount('#fifa-app');
    </script>
</body>
</html>



