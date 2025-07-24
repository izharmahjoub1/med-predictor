<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - FIT - Football Intelligence & Tracking</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @php
        $manifest = file_exists(public_path('build/manifest.json')) ? json_decode(file_get_contents(public_path('build/manifest.json')), true) : [];
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . ltrim($cssFile, '/')) }}">
    @endif
    @if($jsFile)
        <script type="module" src="{{ asset('build/' . ltrim($jsFile, '/')) }}"></script>
    @endif
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <!-- FIT Logo Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <div class="text-4xl font-bold">
                    <span class="text-gray-800">F</span>
                    <span class="bg-blue-600 text-white px-1 relative">
                        I
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 20 20" fill="none">
                            <path d="M2 10 L6 6 L10 10 L14 6 L18 10" stroke="white" stroke-width="2" fill="none"/>
                        </svg>
                    </span>
                    <span class="text-gray-800">T</span>
                </div>
                <div class="ml-4 text-sm text-blue-600 font-semibold uppercase tracking-wide">
                    <div>FOOTBALL</div>
                    <div>INTELLIGENCE</div>
                    <div>& TRACKING</div>
                </div>
            </div>
        </div>

        <!-- FIFA Connect Badge -->
        <div class="mb-6">
            <div class="bg-green-100 border border-green-300 rounded-lg px-4 py-2">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800">FIFA Connect Compliant</span>
                </div>
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logos/fit.png') }}" alt="FIT Logo" style="height:56px;width:auto;">
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Profile Selection Display -->
            @if(request()->has('profile'))
                @php
                    $profiles = [
                        'association' => [
                            'icon' => '🏛️',
                            'title' => 'Association Portal',
                            'description' => 'Manage multiple clubs, oversee competitions, and coordinate regional football activities.',
                            'color' => 'blue'
                        ],
                        'player' => [
                            'icon' => '⚽',
                            'title' => 'Player Portal',
                            'description' => 'Track your performance, view health records, and manage your football career.',
                            'color' => 'green'
                        ],
                        'referee' => [
                            'icon' => '🟨',
                            'title' => 'Referee Portal',
                            'description' => 'Manage match assignments, submit reports, and coordinate officiating activities.',
                            'color' => 'yellow'
                        ],
                        'medical' => [
                            'icon' => '🏥',
                            'title' => 'Medical Portal',
                            'description' => 'Manage health records, conduct assessments, and provide medical support.',
                            'color' => 'red'
                        ]
                    ];
                    $profile = $profiles[request('profile')] ?? null;
                @endphp
                
                @if($profile)
                    <div class="mb-6 p-4 bg-{{ $profile['color'] }}-50 border border-{{ $profile['color'] }}-200 rounded-lg">
                        <div class="flex items-center mb-3">
                            <div class="text-2xl mr-3">{{ $profile['icon'] }}</div>
                            <div>
                                <h3 class="text-lg font-semibold text-{{ $profile['color'] }}-900">{{ $profile['title'] }}</h3>
                                <p class="text-sm text-{{ $profile['color'] }}-800">{{ $profile['description'] }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/login" class="text-xs text-{{ $profile['color'] }}-600 hover:text-{{ $profile['color'] }}-800 underline">
                                ← Choose different profile
                            </a>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Association Info Display -->
            @if(request()->has('association') || request()->has('access_type'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Access Information</h3>
                    @if(request()->has('association'))
                        <p class="text-sm text-blue-800 mb-1">
                            <strong>Association:</strong> 
                            @php
                                $associations = [
                                    'england' => 'England - The Football Association (FA)',
                                    'spain' => 'Spain - Real Federación Española de Fútbol',
                                    'france' => 'France - Fédération Française de Football',
                                    'germany' => 'Germany - Deutscher Fußball-Bund',
                                    'brazil' => 'Brazil - Confederação Brasileira de Futebol',
                                    'usa' => 'USA - United States Soccer Federation',
                                    'italy' => 'Italy - Federazione Italiana Giuoco Calcio',
                                    'portugal' => 'Portugal - Federação Portuguesa de Futebol',
                                ];
                                echo $associations[request('association')] ?? ucfirst(request('association'));
                            @endphp
                        </p>
                    @endif
                    @if(request()->has('access_type'))
                        <p class="text-sm text-blue-800">
                            <strong>Access Type:</strong> {{ ucfirst(request('access_type')) }} Portal
                        </p>
                    @endif
                </div>
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Hidden fields for association and access type -->
                <input type="hidden" name="association" value="{{ request('association') }}">
                <input type="hidden" name="access_type" value="{{ request('access_type') }}">
                <input type="hidden" name="profile" value="{{ request('profile') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <input id="password" class="block mt-1 w-full"
                           type="password"
                           name="password"
                           required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                © 2024 The Blue Healthtech Ltd. All rights reserved.
            </p>
            <p class="text-xs text-gray-500 mt-1">
                Powered by The Blue Healthtech Ltd
            </p>
        </div>
    </div>

    <script>
        // Set hidden fields from URL parameters or session storage
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const association = urlParams.get('association') || sessionStorage.getItem('selectedAssociation');
            const accessType = urlParams.get('access_type') || sessionStorage.getItem('selectedAccessType');
            const profile = urlParams.get('profile') || sessionStorage.getItem('selectedProfile');
            if (association) {
                document.querySelector('input[name="association"]').value = association;
            }
            if (accessType) {
                document.querySelector('input[name="access_type"]').value = accessType;
            }
            if (profile) {
                document.querySelector('input[name="profile"]').value = profile;
            }
            // Pré-remplissage email/password depuis l'URL
            const login = urlParams.get('login');
            const password = urlParams.get('password');
            if (login) {
                const emailInput = document.getElementById('email');
                if (emailInput) emailInput.value = login;
            }
            if (password) {
                const passwordInput = document.getElementById('password');
                if (passwordInput) passwordInput.value = password;
            }
            // Ensure CSRF token is properly set
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput && csrfToken) {
                csrfInput.value = csrfToken;
            }
        });
    </script>
</body>
</html>
