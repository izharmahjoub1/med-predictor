<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FIT - Football Intelligence & Tracking') }} - {{ ucfirst($footballType) }} Medical</title>

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
                                        {{ ucfirst($footballType) }} Medical Management
                                    </h1>
                                    <p class="text-sm text-gray-600">Health records and medical clearances</p>
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
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Medical Module</h2>
                <p class="text-gray-600 mb-4">
                    This is the medical management module for {{ ucfirst($footballType) }} football.
                    Here you can manage health records, medical clearances, and fitness assessments.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="font-medium text-green-900">Medical Clearances</h3>
                        <p class="text-2xl font-bold text-green-600">1,156</p>
                        <p class="text-sm text-green-700">Valid clearances</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="font-medium text-yellow-900">Pending Assessments</h3>
                        <p class="text-2xl font-bold text-yellow-600">34</p>
                        <p class="text-sm text-yellow-700">Awaiting medical review</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4">
                        <h3 class="font-medium text-red-900">Medical Suspensions</h3>
                        <p class="text-2xl font-bold text-red-600">12</p>
                        <p class="text-sm text-red-700">Temporarily suspended</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex space-x-4">
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            New Medical Record
                        </button>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Schedule Assessment
                        </button>
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Health Report
                        </button>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-md font-medium text-gray-900 mb-4">Recent Medical Activities</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Medical clearance approved for John Smith</span>
                            <span class="text-xs text-gray-500">2 hours ago</span>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Fitness assessment scheduled for Sarah Johnson</span>
                            <span class="text-xs text-gray-500">4 hours ago</span>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-700">Medical suspension issued for Mike Wilson</span>
                            <span class="text-xs text-gray-500">1 day ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 