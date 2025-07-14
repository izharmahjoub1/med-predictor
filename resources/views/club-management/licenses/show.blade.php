@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header with Logos -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <!-- Club Logo -->
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                            @if($license->club->hasLogo())
                                <img src="{{ $license->club->getLogoUrl() }}" 
                                     alt="{{ $license->club->getLogoAlt() }}" 
                                     class="w-14 h-14 rounded-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                                License Details for {{ $license->player->name }}
                            </h2>
                            <p class="text-gray-600">{{ $license->club->name }} • {{ $license->club->country }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('club-management.licenses.print', $license) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print License
                        </a>
                        <a href="{{ route('club-management.licenses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Licenses
                        </a>
                    </div>
                </div>

                <!-- Player and License Info Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Player Info Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-xl font-bold text-white">{{ substr($license->player->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">{{ $license->player->name }}</h3>
                                <p class="text-blue-600 font-medium">{{ $license->player->position }}</p>
                                <p class="text-sm text-gray-600">{{ $license->player->age }} years old</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p><strong class="text-gray-700">Nationality:</strong> {{ $license->player->nationality ?? 'N/A' }}</p>
                            <p><strong class="text-gray-700">Overall Rating:</strong> {{ $license->player->overall_rating ?? 'N/A' }}</p>
                            <p><strong class="text-gray-700">Value:</strong> €{{ number_format($license->player->value_eur ?? 0) }}</p>
                        </div>
                    </div>
                    <!-- License Info Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6">
                        <h3 class="font-semibold text-lg mb-4 text-gray-900">License Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-medium">{{ ucfirst($license->license_type) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">
                                    @if($license->status === 'active')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @elseif($license->status === 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif($license->status === 'expired')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($license->status) }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">License Number:</span>
                                <span class="font-medium">{{ $license->license_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Category:</span>
                                <span class="font-medium">{{ $license->license_category }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Issuing Authority:</span>
                                <span class="font-medium">{{ $license->issuing_authority }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Info Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6">
                        <h3 class="font-semibold text-lg mb-4 text-gray-900">Contract Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Contract Type:</span>
                                <span class="font-medium">{{ $license->contract_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Issue Date:</span>
                                <span class="font-medium">{{ $license->issue_date ? $license->issue_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expiry Date:</span>
                                <span class="font-medium">{{ $license->expiry_date ? $license->expiry_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Contract Start:</span>
                                <span class="font-medium">{{ $license->contract_start_date ? $license->contract_start_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Contract End:</span>
                                <span class="font-medium">{{ $license->contract_end_date ? $license->contract_end_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiry Status Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Expiry Status</h3>
                    @if($license->expiry_date)
                        @php
                            $daysUntilExpiry = now()->diffInDays($license->expiry_date, false);
                        @endphp
                        <div class="flex items-center space-x-4">
                            @if($daysUntilExpiry > 30)
                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                <span class="text-green-600 font-semibold text-lg">{{ $daysUntilExpiry }} days remaining</span>
                            @elseif($daysUntilExpiry > 0)
                                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                <span class="text-yellow-600 font-semibold text-lg">{{ $daysUntilExpiry }} days remaining</span>
                            @elseif($daysUntilExpiry == 0)
                                <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                                <span class="text-orange-600 font-semibold text-lg">Expires today</span>
                            @else
                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                <span class="text-red-600 font-semibold text-lg">Expired {{ abs($daysUntilExpiry) }} days ago</span>
                            @endif
                        </div>
                    @else
                        <span class="text-gray-500">No expiry date set</span>
                    @endif
                </div>
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-2">Validation & Status</h3>
                    @if(!empty($validationErrors))
                        <div class="mb-2 p-2 bg-red-100 text-red-700 rounded">
                            <strong>Validation Issues:</strong>
                            <ul class="list-disc ml-5">
                                @foreach($validationErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="mb-2 p-2 bg-green-100 text-green-700 rounded">
                            All license requirements are satisfied.
                        </div>
                    @endif
                </div>
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-2">Notes</h3>
                    <p>{{ $license->notes ?? 'No notes.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 