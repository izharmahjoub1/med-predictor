<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FIT - Football Intelligence & Tracking') }} - {{ ucfirst($footballType) }} Licensing</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">FIT</span>
                                </div>
                                <div class="ml-3">
                                    <h1 class="text-2xl font-bold text-gray-900">
                                        {{ ucfirst($footballType) }} Licensing Management
                                    </h1>
                                    <p class="text-sm text-gray-600">Manage player licenses and compliance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/{{ $footballType }}/dashboard" class="text-gray-600 hover:text-gray-900 text-sm font-medium">← Back to Dashboard</a>
                        <a href="/" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Change Format</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Licensing Module</h2>
                <p class="text-gray-600 mb-4">
                    This is the licensing management module for {{ ucfirst($footballType) }} football.
                    Here you can manage player licenses, renewals, and compliance requirements.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="font-medium text-blue-900">Active Licenses</h3>
                        <p class="text-2xl font-bold text-blue-600">1,247</p>
                        <p class="text-sm text-blue-700">Valid licenses</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="font-medium text-yellow-900">Pending Renewals</h3>
                        <p class="text-2xl font-bold text-yellow-600">23</p>
                        <p class="text-sm text-yellow-700">Due within 30 days</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <h3 class="font-medium text-red-900">Expired Licenses</h3>
                        <p class="text-2xl font-bold text-red-600">8</p>
                        <p class="text-sm text-red-700">Require immediate attention</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex space-x-4">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            New License Application
                        </button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Bulk Renewal
                        </button>
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Export Report
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 