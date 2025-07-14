@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Teams') }} - {{ $club->name }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Manage club teams and squad composition
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('club-management.teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Team
                        </a>
                        <a href="{{ route('club-management.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                @if($teams->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($teams as $team)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $team->name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $team->type ?? 'Senior Team' }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('club-management.teams.show', $team) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View
                                            </a>
                                            <a href="{{ route('club-management.teams.edit', $team) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                                Edit
                                            </a>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Players:</span>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $teamStats[$team->id]['squad_size'] ?? 0 }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Average Rating:</span>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ number_format($teamStats[$team->id]['average_rating'] ?? 0, 1) }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Team Strength:</span>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ number_format($teamStats[$team->id]['strengths']['overall'] ?? 0, 1) }}
                                            </span>
                                        </div>

                                        @if(isset($teamStats[$team->id]['analysis']))
                                            <div class="border-t pt-3">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Tactical Analysis</h4>
                                                <div class="grid grid-cols-2 gap-2 text-xs">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Attack:</span>
                                                        <span class="font-medium">{{ number_format($teamStats[$team->id]['analysis']['attack'] ?? 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Midfield:</span>
                                                        <span class="font-medium">{{ number_format($teamStats[$team->id]['analysis']['midfield'] ?? 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Defense:</span>
                                                        <span class="font-medium">{{ number_format($teamStats[$team->id]['analysis']['defense'] ?? 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Goalkeeping:</span>
                                                        <span class="font-medium">{{ number_format($teamStats[$team->id]['analysis']['goalkeeping'] ?? 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center pt-3 border-t">
                                            <span class="text-sm text-gray-600">Status:</span>
                                            @if($team->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @elseif($team->status === 'inactive')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($team->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-t">
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('club-management.teams.manage-players', $team) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                                Manage Players
                                            </a>
                                            <form method="POST" action="{{ route('club-management.teams.destroy', $team) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this team?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <div class="text-sm text-gray-700">
                            Showing {{ $teams->count() }} team(s)
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No teams found</h3>
                        <p class="text-gray-500 mb-6">
                            Get started by creating your first team for {{ $club->name }}.
                        </p>
                        <a href="{{ route('club-management.teams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create First Team
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 