@extends('layouts.app')

@section('title', 'FIFA Connect Status - Med Predictor')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">FIFA Connect Status</h1>
            <p class="mt-2 text-gray-600">Monitor the connection status and data flow with FIFA Connect API</p>
        </div>

        <!-- Connection Status Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Connection Status</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($status === 'connected')
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-green-900">Connected</h4>
                                <p class="text-sm text-green-600">FIFA Connect API is accessible and responding</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-red-900">Disconnected</h4>
                                <p class="text-sm text-red-600">Unable to connect to FIFA Connect API</p>
                                @if($error)
                                    <p class="text-xs text-red-500 mt-1">Error: {{ $error }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($responseTime)
                            <p class="text-sm text-gray-500">Response Time</p>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($responseTime * 1000, 2) }}ms</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- API Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">API Configuration</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Base URL</dt>
                            <dd class="mt-1 text-sm text-gray-900">https://api.fifa.com/v1</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">API Version</dt>
                            <dd class="mt-1 text-sm text-gray-900">v1</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cache Timeout</dt>
                            <dd class="mt-1 text-sm text-gray-900">1 hour</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Request Timeout</dt>
                            <dd class="mt-1 text-sm text-gray-900">30 seconds</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Available Endpoints</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-900">GET /players - List players</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-900">GET /players/{id} - Player details</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-900">GET /players/{id}/stats - Player statistics</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-900">GET /search - Search players</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm text-gray-900">POST /sync - Sync player data</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sample Data -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Sample Player Data</h3>
                <p class="text-sm text-gray-600 mt-1">Example of data structure available through FIFA Connect API</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($samplePlayers as $player)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-indigo-600">
                                                {{ substr($player['first_name'], 0, 1) }}{{ substr($player['last_name'], 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $player['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $player['fifa_connect_id'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $player['position'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="font-medium">{{ $player['overall_rating'] }}</span>
                                    <span class="text-gray-500 ml-1">/ 99</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $player['nationality'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $player['club_logo_url'] ? 'Club Available' : 'No Club' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a href="{{ route('fifa.players') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                View All Players
            </a>
            
            <a href="{{ route('fifa.sync') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Sync Data
            </a>
            
            <button onclick="testConnectivity()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Test Connection
            </button>
        </div>

        <!-- Clubs, Associations, and Stakeholders -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Clubs & Associations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($clubs as $club)
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center space-x-4 mb-2">
                        <img src="{{ $club->logo_url }}" alt="{{ $club->name }} Logo" class="h-12 w-12 rounded-full border">
                        <div>
                            <div class="font-bold text-lg">{{ $club->name }}</div>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($club->association)
                                    <img src="{{ $club->association->association_logo_url }}" alt="{{ $club->association->name }} Logo" class="h-6 w-6 rounded-full border">
                                    <span class="text-sm text-gray-600">{{ $club->association->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">No Association</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="font-semibold text-gray-700 mb-1">Stakeholders</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($club->users ?? [] as $user)
                                <div class="flex flex-col items-center">
                                    <img src="{{ $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full border" title="{{ $user->name }}">
                                    <span class="text-xs mt-1 text-gray-600">{{ $user->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function testConnectivity() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Testing...
    `;
    button.disabled = true;
    
    fetch('{{ route("fifa.connectivity.api") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Connection successful! Response time: ' + (data.response_time ? (data.response_time * 1000).toFixed(2) + 'ms' : 'N/A'));
            } else {
                alert('❌ Connection failed: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('❌ Connection failed: ' + error.message);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
}
</script>
@endsection 