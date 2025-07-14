<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Welcome, {{ auth()->user()->name }}!</h3>
                            <p class="text-gray-600">{{ auth()->user()->getRoleDisplay() }} at {{ auth()->user()->getEntityName() }}</p>
                            <p class="text-sm text-gray-500">FIFA Connect ID: {{ auth()->user()->fifa_connect_id }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Last Login</div>
                            <div class="text-sm font-medium">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'First time' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module Navigation -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Player Registration Module -->
                @if(auth()->user()->canAccessModule('player_registration'))
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold">Player Registration</h3>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <p class="text-blue-100 mb-4">Manage player registrations, licenses, and FIFA Connect integration</p>
                        <div class="space-y-2">
                            <a href="{{ route('player-registration.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('player-registration.players.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                View Players
                            </a>
                            <a href="{{ route('player-registration.players.create') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Register Player
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Competition Management Module -->
                @if(auth()->user()->canAccessModule('competition_management'))
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold">Competition Management</h3>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <p class="text-green-100 mb-4">Organize competitions, manage teams, and track matches</p>
                        <div class="space-y-2">
                            <a href="{{ route('competition-management.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('competition-management.competitions.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                View Competitions
                            </a>
                            <a href="{{ route('competition-management.competitions.create') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Create Competition
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Healthcare Module -->
                @if(auth()->user()->canAccessModule('healthcare'))
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold">Healthcare</h3>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <p class="text-red-100 mb-4">Monitor player health, generate predictions, and manage medical records</p>
                        <div class="space-y-2">
                            <a href="{{ route('healthcare.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('healthcare.records.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Health Records
                            </a>
                            <a href="{{ route('healthcare.predictions') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-2 px-4 rounded transition duration-200">
                                Predictions
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Players</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['total_players'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Active Competitions</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['active_competitions'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Health Records</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['total_health_records'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">FIFA Connect</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['fifa_connect_status'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span>System initialized with FIFA Connect integration</span>
                            <span class="ml-auto text-gray-400">{{ now()->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <span>Role-based access control activated</span>
                            <span class="ml-auto text-gray-400">{{ now()->subMinutes(5)->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                            <span>Modular system architecture implemented</span>
                            <span class="ml-auto text-gray-400">{{ now()->subMinutes(10)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
