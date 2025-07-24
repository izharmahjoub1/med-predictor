<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>{{ __('landing.title') }}</title>
    <meta name="description" content="Plateforme innovante combinant IA, football et santé. Compliance FIFA, analyse prédictive et suivi médical des joueurs.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 25%, #06b6d4 50%, #10b981 75%, #059669 100%);
        }
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            to { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .fifa-compliant-badge {
            background: linear-gradient(45deg, #1e3a8a, #3b82f6);
            border: 2px solid #fbbf24;
        }
    </style>
</head>
<body class="font-sans antialiased" x-data="{ showAccountRequestModal: false }">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logos/fit.png') }}" alt="FIT Logo" class="h-12 w-auto">
                    <div class="hidden md:block">
                        <span class="text-xl font-bold text-gray-900">{{ __('landing.platform') }}</span>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium">{{ __('landing.nav.features') }}</a>
                    <a href="#fifa-compliance" class="text-gray-700 hover:text-blue-600 font-medium">{{ __('landing.nav.fifa_compliance') }}</a>
                    <a href="#ai-health" class="text-gray-700 hover:text-blue-600 font-medium">{{ __('landing.nav.ai_health') }}</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium">{{ __('landing.nav.contact') }}</a>
                </div>
                
                <!-- Language Switcher and CTA Buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2 mr-4">
                        <a href="{{ url('lang/fr') }}" class="px-3 py-1 rounded border text-sm font-semibold {{ app()->getLocale() == 'fr' ? 'bg-blue-900 text-white' : 'bg-white text-blue-900 hover:bg-blue-50' }} transition-colors">
                            FR
                        </a>
                        <a href="{{ url('lang/en') }}" class="px-3 py-1 rounded border text-sm font-semibold {{ app()->getLocale() == 'en' ? 'bg-blue-900 text-white' : 'bg-white text-blue-900 hover:bg-blue-50' }} transition-colors">
                            EN
                        </a>
                    </div>
                    
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">{{ __('landing.login') }}</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">{{ __('landing.start') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient hero-pattern min-h-screen flex items-center relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full floating-animation"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-white/10 rounded-full floating-animation" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-white/10 rounded-full floating-animation" style="animation-delay: 2s;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-white">
                    <div class="mb-6">
                        <span class="fifa-compliant-badge text-white px-4 py-2 rounded-full text-sm font-semibold inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('landing.hero.fifa_compliant') }}
                        </span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight text-blue-900">{{ __('landing.hero.title') }}</h1>
                    
                    <p class="text-xl mb-8 text-blue-900 leading-relaxed">{{ __('landing.hero.subtitle') }}</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="showAccountRequestModal = true" class="bg-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors pulse-glow">{{ __('landing.hero.request_account') }}</button>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-700 transition-colors">{{ __('landing.hero.start_free') }}</a>
                        <a href="#demo" class="border-2 border-white text-blue-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-blue-900 transition-colors">{{ __('landing.hero.view_demo') }}</a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="mt-12 flex items-center space-x-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">500+</div>
                            <div class="text-sm text-blue-900">{{ __('landing.hero.active_clubs') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">50K+</div>
                            <div class="text-sm text-blue-900">{{ __('landing.hero.players_monitored') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">99.9%</div>
                            <div class="text-sm text-blue-900">{{ __('landing.hero.uptime') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Visual -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="grid grid-cols-2 gap-6">
                            <!-- AI Analytics Card -->
                            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-white">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <h3 class="font-bold">
                                        {{ __('landing.features.ai_analytics') }}
                                    </h3>
                                </div>
                                <p class="text-sm opacity-90">
                                    {{ __('landing.features.ai_analytics_desc') }}
                                </p>
                            </div>
                            
                            <!-- Health Monitoring Card -->
                            <div class="bg-gradient-to-br from-green-500 to-teal-600 rounded-xl p-6 text-white">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <h3 class="font-bold">
                                        {{ __('landing.features.medical_monitoring') }}
                                    </h3>
                                </div>
                                <p class="text-sm opacity-90">
                                    {{ __('landing.features.medical_monitoring_desc') }}
                                </p>
                            </div>
                            
                            <!-- FIFA Compliance Card -->
                            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="font-bold">{{ __('landing.fifa') }}</h3>
                                </div>
                                <p class="text-sm opacity-90">
                                    {{ __('landing.features.fifa_compliance_desc') }}
                                </p>
                            </div>
                            
                            <!-- Performance Card -->
                            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-xl p-6 text-white">
                                <div class="flex items-center mb-4">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <h3 class="font-bold">
                                        {{ __('landing.features.performance') }}
                                    </h3>
                                </div>
                                <p class="text-sm opacity-90">
                                    {{ __('landing.features.performance_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('landing.partners.title') }}</h2>
            <p class="text-gray-600">{{ __('landing.partners.subtitle') }}</p>
        </div>
            
            <div class="flex justify-center items-center space-x-12 opacity-60">
                <div class="h-16 flex items-center justify-center bg-blue-900 text-white font-bold text-2xl px-6 rounded-lg">
                    FIFA
                </div>
                <img src="{{ asset('images/logos/fit.png') }}" alt="FIT" class="h-16 w-auto">
                <div class="h-16 flex items-center justify-center bg-blue-600 text-white font-bold text-sm px-4 rounded-lg text-center">
                    The Blue<br>Healthtech
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('landing.features.title') }}</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('landing.features.subtitle') }}</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- AI Analytics -->
                <div class="feature-card bg-white rounded-xl p-8 border border-gray-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('landing.features.ai_analytics') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('landing.features.ai_analytics_desc') }}</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• {{ __('landing.injury_prediction') }}</li>
                        <li>• {{ __('landing.performance_optimization') }}</li>
                        <li>• {{ __('landing.real_time_analysis') }}</li>
                    </ul>
                </div>
                
                <!-- Health Monitoring -->
                <div class="feature-card bg-white rounded-xl p-8 border border-gray-200">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('landing.features.medical_monitoring') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('landing.features.medical_monitoring_desc') }}</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• Real-time monitoring</li>
                        <li>• Automatic alerts</li>
                        <li>• Medical reports</li>
                    </ul>
                </div>
                
                <!-- FIFA Compliance -->
                <div class="feature-card bg-white rounded-xl p-8 border border-gray-200">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('landing.features.fifa_compliance') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('landing.features.fifa_compliance_desc') }}</p>
                    <ul class="text-sm text-gray-500 space-y-2">
                        <li>• FIFA standards respected</li>
                        <li>• Native integration</li>
                        <li>• Official certification</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FIFA Compliance Section -->
    <section id="fifa-compliance" class="py-20 bg-gradient-to-r from-blue-900 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="mb-6">
                        <span class="bg-yellow-500 text-blue-900 px-4 py-2 rounded-full text-sm font-semibold">
                            FIFA CERTIFIED
                        </span>
                    </div>
                    <h2 class="text-4xl font-bold mb-6">
                        {{ __('landing.fifa_compliance.title') }}
                    </h2>
                    <p class="text-xl mb-8 text-blue-100">
                        {{ __('landing.fifa_compliance.desc') }}
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold">
                                    {{ __('landing.fifa_compliance.standards_title') }}
                                </h3>
                                <p class="text-blue-200">
                                    {{ __('landing.fifa_compliance.standards_desc') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold">
                                    {{ __('landing.fifa_compliance.integration_title') }}
                                </h3>
                                <p class="text-blue-200">
                                    {{ __('landing.fifa_compliance.integration_desc') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-400 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold">
                                    {{ __('landing.fifa_compliance.certification_title') }}
                                </h3>
                                <p class="text-blue-200">
                                    {{ __('landing.fifa_compliance.certification_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="text-center">
                        <div class="h-24 flex items-center justify-center bg-blue-900 text-white font-bold text-4xl mx-auto mb-6 px-8 rounded-lg">
                            FIFA
                        </div>
                        <h3 class="text-2xl font-bold mb-4">
                            {{ __('landing.fifa_compliance.standards_respected') }}
                        </h3>
                        <div class="space-y-4 text-left">
                            <div class="flex items-center justify-between">
                                <span>
                                    {{ __('landing.fifa_compliance.data_format') }}
                                </span>
                                <span class="text-green-400">
                                    {{ __('landing.fifa_compliance.compliant') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>
                                    {{ __('landing.fifa_compliance.security_protocols') }}
                                </span>
                                <span class="text-green-400">
                                    {{ __('landing.fifa_compliance.validated') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>
                                    {{ __('landing.fifa_compliance.api_integration') }}
                                </span>
                                <span class="text-green-400">
                                    {{ __('landing.fifa_compliance.certified') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>
                                    {{ __('landing.fifa_compliance.compliance_audit') }}
                                </span>
                                <span class="text-green-400">
                                    {{ __('landing.fifa_compliance.approved') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI & Health Section -->
    <section id="ai-health" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ __('landing.ai_health.title') }}
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('landing.ai_health.subtitle') }}
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        {{ __('landing.ai_health.prediction_title') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('landing.ai_health.prediction_desc') }}
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-teal-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        {{ __('landing.ai_health.monitoring_title') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('landing.ai_health.monitoring_desc') }}
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-lg">
                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        {{ __('landing.ai_health.analytics_title') }}
                    </h3>
                    <p class="text-gray-600">
                        {{ __('landing.ai_health.analytics_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-6">{{ __('landing.cta.title') }}</h2>
            <p class="text-xl mb-8 text-blue-100">{{ __('landing.cta.subtitle') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors">{{ __('landing.cta.start_free') }}</a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-blue-600 transition-colors">{{ __('landing.cta.contact_team') }}</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <img src="{{ asset('images/logos/fit.png') }}" alt="FIT" class="h-12 w-auto mb-4">
                    <p class="text-gray-400">
                        {{ __('landing.footer.description') }}
                    </p>
                </div>
                
                <div>
                    <h3 class="font-bold mb-4">{{ __('landing.footer.product') }}</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.features') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.pricing') }}</a></li>
                        <li><a href="#" class="hover:text-white">API</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.documentation') }}</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold mb-4">{{ __('landing.footer.company') }}</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.about') }}</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.careers') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.contact') }}</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold mb-4">{{ __('landing.footer.support') }}</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.help_center') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.community') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.status') }}</a></li>
                        <li><a href="#" class="hover:text-white">{{ __('landing.footer.security') }}</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">{{ __('landing.footer.copyright') }}</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Account Request Modal -->
    <div x-show="showAccountRequestModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         @click.self="showAccountRequestModal = false"
         @keydown.escape.window="showAccountRequestModal = false"
         @close-modal.window="showAccountRequestModal = false">
        
        <div x-show="showAccountRequestModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Header with title -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('landing.account_request.title') }}</h2>
            </div>

            <!-- Form Content -->
            @include('components.account-request-form')
        </div>
    </div>
</body>
</html> 