@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        {{ __('Create Player License') }}
                    </h2>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Club: {{ $club->name }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        Available players: {{ $availablePlayers->count() }}
                    </p>
                </div>

                @if($availablePlayers->count() > 0)
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        <h4 class="font-bold">Available Players:</h4>
                        <ul class="list-disc list-inside">
                            @foreach($availablePlayers as $player)
                                <li>{{ $player->name }} ({{ $player->position }}) - {{ $player->age }} years old</li>
                            @endforeach
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('club-management.licenses.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="player_id" class="block text-sm font-medium text-gray-700">Player</label>
                            <select id="player_id" name="player_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a player</option>
                                @foreach($availablePlayers as $player)
                                    <option value="{{ $player->id }}" {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                        {{ $player->name }} ({{ $player->position }}) - {{ $player->age }} years old
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="license_type" class="block text-sm font-medium text-gray-700">License Type</label>
                            <select id="license_type" name="license_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select license type</option>
                                @foreach($licenseTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('license_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select category</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contract_start_date" class="block text-sm font-medium text-gray-700">Contract Start Date</label>
                                <input type="date" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <div>
                                <label for="contract_end_date" class="block text-sm font-medium text-gray-700">Contract End Date</label>
                                <input type="date" id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="salary_eur" class="block text-sm font-medium text-gray-700">Salary (EUR)</label>
                                <input type="number" step="0.01" id="salary_eur" name="salary_eur" value="{{ old('salary_eur') }}" placeholder="50000.00" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label for="bonus_eur" class="block text-sm font-medium text-gray-700">Bonus (EUR)</label>
                                <input type="number" step="0.01" id="bonus_eur" name="bonus_eur" value="{{ old('bonus_eur') }}" placeholder="10000.00" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label for="release_clause_eur" class="block text-sm font-medium text-gray-700">Release Clause (EUR)</label>
                                <input type="number" step="0.01" id="release_clause_eur" name="release_clause_eur" value="{{ old('release_clause_eur') }}" placeholder="5000000.00" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="fifa_connect_id" class="block text-sm font-medium text-gray-700">FIFA Connect ID</label>
                            <input type="text" id="fifa_connect_id" name="fifa_connect_id" value="{{ old('fifa_connect_id') }}" placeholder="FIFA_123456" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Additional notes about this license...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('club-management.licenses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create License
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <p>No players available for license creation.</p>
                        <p class="mt-2">All players in this club already have active licenses.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 