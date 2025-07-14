<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Med Predictor - FIFA Connect</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700">
            <div class="max-w-6xl mx-auto px-8 py-12">
                <div class="text-center text-white mb-12">
                    <h1 class="text-5xl font-bold mb-4">🏥 Med Predictor</h1>
                    <p class="text-xl opacity-90">Intelligence artificielle pour la prédiction médicale avec intégration FIFA Connect</p>
                </div>

                <div class="bg-white rounded-xl shadow-2xl p-8 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">État du Service</h2>
                    <div class="space-y-3">
                        <p class="flex items-center text-gray-700">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                            Serveur Laravel opérationnel
                        </p>
                        <p class="flex items-center text-gray-700">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                            Base de données connectée
                        </p>
                        <p class="flex items-center text-gray-700">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                            FIFA Connect API v3.3 prête
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="bg-white rounded-xl shadow-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">🎯 Prédiction Médicale</h3>
                        <p class="text-gray-600 leading-relaxed">Analyse avancée des données de santé pour prédire les risques médicaux et optimiser les soins.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">⚽ FIFA Connect</h3>
                        <p class="text-gray-600 leading-relaxed">Intégration avec l'API FIFA pour analyser les données des joueurs et leur impact sur la santé.</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">📊 Analytics</h3>
                        <p class="text-gray-600 leading-relaxed">Tableaux de bord interactifs et rapports détaillés pour le suivi des prédictions médicales.</p>
                    </div>
                </div>

                <div class="text-center">
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="/test" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 transform hover:-translate-y-1">
                            Test Route
                        </a>
                        <a href="/test-json" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 transform hover:-translate-y-1">
                            Test JSON
                        </a>
                        <a href="/api/fifa/connectivity" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 transform hover:-translate-y-1">
                            FIFA Connect
                        </a>
                        <a href="/api/fifa/test" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 transform hover:-translate-y-1">
                            API Test
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
