<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FIT - Football Intelligence & Tracking') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>window.LARAVEL_LOCALE = '{{ app()->getLocale() }}';</script>
    <!-- Alpine.js via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <!-- FIT Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/logos/fit.png') }}" alt="FIT Logo" class="h-12 w-auto">
                        <div class="ml-4">
                            <div class="text-3xl font-bold">
                                <span class="text-gray-800">F</span>
                                <span class="bg-blue-600 text-white px-1 relative">
                                    I
                                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 20 20" fill="none">
                                        <path d="M2 10 L6 6 L10 10 L14 6 L18 10" stroke="white" stroke-width="2" fill="none"/>
                                    </svg>
                                </span>
                                <span class="text-gray-800">T</span>
                            </div>
                            <div class="text-sm text-blue-600 font-semibold uppercase tracking-wide">
                                <div>{{ __('welcome.slogan') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Language Selector and Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Language Selector -->
                        <div class="relative" id="language-selector">
                            <button id="language-button" class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                </svg>
                                <span id="current-language">{{ app()->getLocale() === 'fr' ? 'Français' : 'English' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="language-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                                <a href="{{ route('language.switch', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
                            </div>
                        </div>

                        <!-- FIFA Connect Badge -->
                        <div class="bg-green-100 border border-green-300 rounded-lg px-3 py-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-medium text-green-800">{{ __('welcome.fifa_connect') }}</span>
                            </div>
                        </div>

                        <!-- Login Button -->
                        @auth
                            <span class="text-gray-700">{{ __('welcome.hello_user', ['name' => auth()->user()->name]) }}</span>
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                {{ __('welcome.access_dashboard') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                                    {{ __('welcome.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                {{ __('welcome.connect') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">{{ __('welcome.welcome_title') }}</h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    {{ __('welcome.welcome_subtitle') }}
                </p>
                <div class="flex justify-center space-x-4">
                    @if(isset($tempUser))
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.access_dashboard') }}
                        </a>
                        <a href="{{ route('logout') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.logout') }}
                        </a>
                    @elseif(auth()->check())
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.access_dashboard') }}
                        </a>
                        <a href="{{ route('logout') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.logout') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.connect') }}
                        </a>
                        <a href="#account-request" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                            {{ __('welcome.request_account') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Football Type Cards -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">{{ __('welcome.football_types') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- 11-a-side Football -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="selectFootballType('11aside')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.football_11') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('welcome.football_11_description') }}</p>
                        </div>
                    </div>

                    <!-- Women's Football -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="selectFootballType('womens')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.women_football') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('welcome.women_football_description') }}</p>
                        </div>
                    </div>

                    <!-- Futsal -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="selectFootballType('futsal')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.futsal') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('welcome.futsal_description') }}</p>
                        </div>
                    </div>

                    <!-- Beach Soccer -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="selectFootballType('beach')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.beach_soccer') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('welcome.beach_soccer_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Request Section -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-16">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('welcome.account_request_title') }}</h2>
                    <p class="text-lg text-gray-600">{{ __('welcome.account_request_description') }}</p>
                </div>
                
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('registration-requests.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.first_name') }}</label>
                                <input type="text" name="first_name" id="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.last_name') }}</label>
                                <input type="text" name="last_name" id="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.email') }}</label>
                                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.phone') }} ({{ __('welcome.optional') }})</label>
                                <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.organization_name') }}</label>
                                <input type="text" name="organization_name" id="organization_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="organization_type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.organization_type') }}</label>
                                <select name="organization_type" id="organization_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">{{ __('welcome.select_type') }}</option>
                                    <option value="club">{{ __('welcome.club') }}</option>
                                    <option value="association">{{ __('welcome.association') }}</option>
                                    <option value="federation">{{ __('welcome.federation') }}</option>
                                    <option value="league">{{ __('welcome.league') }}</option>
                                    <option value="academy">{{ __('welcome.academy') }}</option>
                                    <option value="medical_center">{{ __('welcome.medical_center') }}</option>
                                    <option value="training_center">{{ __('welcome.training_center') }}</option>
                                    <option value="other">{{ __('welcome.other') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="football_type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.football_type') }}</label>
                                <select name="football_type" id="football_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">{{ __('welcome.select_football_type') }}</option>
                                    <option value="11-a-side">{{ __('welcome.football_11_vs_11') }}</option>
                                    <option value="futsal">{{ __('welcome.futsal') }}</option>
                                    <option value="women">{{ __('welcome.women_football') }}</option>
                                    <option value="beach-soccer">{{ __('welcome.beach_soccer_type') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="fifa_connect_type" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.fifa_connect_role') }}</label>
                                <select name="fifa_connect_type" id="fifa_connect_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">{{ __('welcome.select_role') }}</option>
                                    <option value="club_admin">{{ __('welcome.club_admin') }}</option>
                                    <option value="club_manager">{{ __('welcome.club_manager') }}</option>
                                    <option value="club_medical">{{ __('welcome.club_medical') }}</option>
                                    <option value="association_admin">{{ __('welcome.association_admin') }}</option>
                                    <option value="association_registrar">{{ __('welcome.association_registrar') }}</option>
                                    <option value="association_medical">{{ __('welcome.association_medical') }}</option>
                                    <option value="referee">{{ __('welcome.referee') }}</option>
                                    <option value="assistant_referee">{{ __('welcome.assistant_referee') }}</option>
                                    <option value="fourth_official">{{ __('welcome.fourth_official') }}</option>
                                    <option value="var_official">{{ __('welcome.var_official') }}</option>
                                    <option value="match_commissioner">{{ __('welcome.match_commissioner') }}</option>
                                    <option value="match_official">{{ __('welcome.match_official') }}</option>
                                    <option value="team_doctor">{{ __('welcome.team_doctor') }}</option>
                                    <option value="physiotherapist">{{ __('welcome.physiotherapist') }}</option>
                                    <option value="sports_scientist">{{ __('welcome.sports_scientist') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.country') }}</label>
                                <input type="text" name="country" id="country" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.city') }} ({{ __('welcome.optional') }})</label>
                                <input type="text" name="city" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="association_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.fifa_association') }}</label>
                            <select name="association_id" id="association_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('welcome.select_association') }}</option>
                                @foreach(\App\Models\Association::all() as $association)
                                    <option value="{{ $association->id }}">{{ $association->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('welcome.description') }} ({{ __('welcome.optional') }})</label>
                            <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('welcome.description_placeholder') }}"></textarea>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                                {{ __('welcome.submit_request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.competition_management') }}</h3>
                    <p class="text-gray-600">{{ __('welcome.competition_description') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.performance_tracking') }}</h3>
                    <p class="text-gray-600">{{ __('welcome.performance_description') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('welcome.player_health') }}</h3>
                    <p class="text-gray-600">{{ __('welcome.player_health_description') }}</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        © 2024 The Blue Healthtech Ltd. {{ __('welcome.all_rights_reserved') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('welcome.powered_by') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function selectFootballType(type) {
            // Store the selected football type
            sessionStorage.setItem('selectedFootballType', type);
            
            // Redirect to profile selector
            window.location.href = '{{ route("profile-selector") }}?footballType=' + type;
        }

        // Simple language selector
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing language selector...');
            
            const languageButton = document.getElementById('language-button');
            const languageDropdown = document.getElementById('language-dropdown');
            
            if (languageButton && languageDropdown) {
                console.log('Language selector elements found, setting up...');
                
                let isOpen = false;
                
                // Toggle dropdown on button click
                languageButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Language button clicked');
                    
                    isOpen = !isOpen;
                    
                    if (isOpen) {
                        languageDropdown.classList.remove('hidden');
                        console.log('Dropdown opened');
                    } else {
                        languageDropdown.classList.add('hidden');
                        console.log('Dropdown closed');
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!languageButton.contains(e.target) && !languageDropdown.contains(e.target) && isOpen) {
                        console.log('Clicking outside, closing dropdown');
                        isOpen = false;
                        languageDropdown.classList.add('hidden');
                    }
                });
                
                // Add click handlers to language links
                const languageLinks = languageDropdown.querySelectorAll('a');
                languageLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Language link clicked:', this.href);
                        const currentLang = document.getElementById('current-language').textContent;
                        const newLang = this.textContent.trim();
                        console.log('Changing language from', currentLang, 'to', newLang);
                        
                        // Extract locale from href
                        const href = this.getAttribute('href');
                        const locale = href.split('/').pop();
                        
                        // Make AJAX request to change language
                        fetch(href, {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Cache-Control': 'no-cache'
                            }
                        })
                        .then(response => {
                            console.log('Language change response:', response.status);
                            // Force page reload after successful change
                            window.location.reload(true);
                        })
                        .catch(error => {
                            console.error('Error changing language:', error);
                            // Fallback to direct navigation
                            window.location.href = href;
                        });
                    });
                });
                
                console.log('Language selector initialized successfully');
            } else {
                console.log('Language selector elements not found');
            }
            
            // Check if there's a language change message in the URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('lang_changed')) {
                console.log('Language change detected');
                // You could show a toast notification here
            }
        });
    </script>
</body>
</html>
