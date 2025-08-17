<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Intégration FIFA Connect</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">⚽ Test Intégration FIFA Connect</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Test de l'API -->
            <div class="bg-gray-800 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-4">🔌 Test API FIFA Connect</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">ID du Joueur</label>
                        <input type="number" id="playerId" value="1" class="w-full px-3 py-2 bg-gray-700 rounded-md text-white">
                    </div>
                    <button onclick="testAPI()" class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md font-medium">
                        Tester l'API FIFA
                    </button>
                </div>
                
                <div id="api-result" class="mt-4 p-4 bg-gray-700 rounded-md">
                    <p class="text-gray-400">Cliquez sur "Tester l'API FIFA" pour commencer</p>
                </div>
            </div>
            
            <!-- Test des fonctions JavaScript -->
            <div class="bg-gray-800 rounded-xl p-6">
                <h2 class="text-xl font-bold mb-4">🧪 Test des Fonctions JavaScript</h2>
                <div class="space-y-4">
                    <button onclick="testFunctions()" class="w-full bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md font-medium">
                        Tester les Fonctions
                    </button>
                    <button onclick="testChartGeneration()" class="w-full bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md font-medium">
                        Tester la Génération de Graphiques
                    </button>
                </div>
                
                <div id="function-result" class="mt-4 p-4 bg-gray-700 rounded-md">
                    <p class="text-gray-400">Cliquez sur les boutons pour tester</p>
                </div>
            </div>
        </div>
        
        <!-- Graphiques de test -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">📈 Graphique de Performance (Test)</h3>
                <div class="h-64">
                    <canvas id="testPerformanceChart"></canvas>
                </div>
            </div>
            
            <div class="bg-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">🎯 Radar des Compétences (Test)</h3>
                <div class="h-64">
                    <canvas id="testSkillsRadar"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== FONCTIONS DE TEST =====
        
        /**
         * Tester l'API FIFA Connect
         */
        async function testAPI() {
            const playerId = document.getElementById('playerId').value;
            const resultDiv = document.getElementById('api-result');
            
            resultDiv.innerHTML = '<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto mb-2"></div><p class="text-center">Test de l\'API en cours...</p>';
            
            try {
                const response = await fetch(`/api/player-performance/${playerId}`);
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                
                const data = await response.json();
                
                resultDiv.innerHTML = `
                    <div class="text-green-400 font-bold mb-2">✅ API FIFA Connect fonctionne !</div>
                    <div class="text-sm space-y-1">
                        <div><strong>Rating Global:</strong> ${data.overall_rating}</div>
                        <div><strong>Buts:</strong> ${data.goals_scored}</div>
                        <div><strong>Passes:</strong> ${data.assists}</div>
                        <div><strong>Matchs:</strong> ${data.matches_played}</div>
                        <div><strong>Rating Attaque:</strong> ${data.attacking_rating}</div>
                        <div><strong>Rating Défense:</strong> ${data.defending_rating}</div>
                    </div>
                `;
                
                console.log('✅ API testée avec succès:', data);
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-400 font-bold mb-2">❌ Erreur API</div>
                    <div class="text-sm text-red-300">${error.message}</div>
                `;
                console.error('❌ Erreur lors du test API:', error);
            }
        }
        
        /**
         * Tester les fonctions JavaScript
         */
        function testFunctions() {
            const resultDiv = document.getElementById('function-result');
            const tests = [];
            
            // Test de getCurrentPlayerId
            try {
                const playerId = getCurrentPlayerId();
                tests.push(`✅ getCurrentPlayerId: ${playerId || 'URL simulée'}`);
            } catch (error) {
                tests.push(`❌ getCurrentPlayerId: ${error.message}`);
            }
            
            // Test de generateMatchRatings
            try {
                const ratings = generateMatchRatings(85, 80);
                tests.push(`✅ generateMatchRatings: ${ratings.length} ratings générés`);
            } catch (error) {
                tests.push(`❌ generateMatchRatings: ${error.message}`);
            }
            
            // Test de generateGoalsAssistsData
            try {
                const data = generateGoalsAssistsData(15, 8, 25);
                tests.push(`✅ generateGoalsAssistsData: ${data.length} matchs générés`);
            } catch (error) {
                tests.push(`❌ generateGoalsAssistsData: ${error.message}`);
            }
            
            resultDiv.innerHTML = `
                <div class="text-green-400 font-bold mb-2">🧪 Tests des Fonctions</div>
                <div class="text-sm space-y-1">
                    ${tests.map(test => `<div>${test}</div>`).join('')}
                </div>
            `;
        }
        
        /**
         * Tester la génération de graphiques
         */
        function testChartGeneration() {
            const resultDiv = document.getElementById('function-result');
            
            try {
                // Créer un graphique de test
                const performanceCtx = document.getElementById('testPerformanceChart');
                if (performanceCtx) {
                    new Chart(performanceCtx, {
                        type: 'line',
                        data: {
                            labels: ['J1', 'J2', 'J3', 'J4', 'J5'],
                            datasets: [{
                                label: 'Performance Test',
                                data: [85, 87, 89, 88, 90],
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
                
                // Créer un radar de test
                const skillsCtx = document.getElementById('testSkillsRadar');
                if (skillsCtx) {
                    new Chart(skillsCtx, {
                        type: 'radar',
                        data: {
                            labels: ['Attaque', 'Défense', 'Physique', 'Technique', 'Mental'],
                            datasets: [{
                                label: 'Compétences Test',
                                data: [88, 85, 78, 82, 80],
                                borderColor: '#8B5CF6',
                                backgroundColor: 'rgba(139, 92, 244, 0.2)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
                
                resultDiv.innerHTML = `
                    <div class="text-green-400 font-bold mb-2">📊 Graphiques de Test Créés</div>
                    <div class="text-sm text-gray-300">Les graphiques de test sont maintenant visibles ci-dessus</div>
                `;
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="text-red-400 font-bold mb-2">❌ Erreur Graphiques</div>
                    <div class="text-sm text-red-300">${error.message}</div>
                `;
            }
        }
        
        // ===== FONCTIONS SIMULÉES POUR LES TESTS =====
        
        /**
         * Simuler getCurrentPlayerId pour les tests
         */
        function getCurrentPlayerId() {
            // Simuler une URL de portail joueur
            return '1';
        }
        
        /**
         * Simuler generateMatchRatings pour les tests
         */
        function generateMatchRatings(overallRating, formPercentage) {
            const baseRating = overallRating || 75;
            const form = formPercentage || 75;
            
            return Array.from({ length: 8 }, () => {
                const formVariation = (form - 75) / 100;
                const randomVariation = (Math.random() - 0.5) * 0.1;
                const finalRating = baseRating + (formVariation * 5) + (randomVariation * 100);
                return Math.max(50, Math.min(99, Math.round(finalRating)));
            });
        }
        
        /**
         * Simuler generateGoalsAssistsData pour les tests
         */
        function generateGoalsAssistsData(goals, assists, matches) {
            const goalsPerMatch = matches > 0 ? goals / matches : 0;
            const assistsPerMatch = matches > 0 ? assists / matches : 0;
            
            return Array.from({ length: 8 }, () => {
                const goalsVariation = (Math.random() - 0.5) * 0.5;
                const assistsVariation = (Math.random() - 0.5) * 0.5;
                
                const matchGoals = Math.max(0, Math.round((goalsPerMatch + goalsVariation) * 10) / 10);
                const matchAssists = Math.max(0, Math.round((assistsPerMatch + assistsVariation) * 10) / 10);
                
                return matchGoals + matchAssists;
            });
        }
        
        console.log('🧪 Page de test FIFA Connect chargée');
    </script>
</body>
</html>

