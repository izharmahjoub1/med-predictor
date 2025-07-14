@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New User</h1>
                    <p class="mt-2 text-gray-600">Create a new user with FIFA Connect compliance</p>
                </div>
                <a href="{{ route('user-management.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('user-management.users.store') }}" class="p-6">
                @csrf

                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror"
                                   placeholder="Enter full name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" name="password" id="password" required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror"
                                   placeholder="Enter password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Confirm password">
                        </div>
                    </div>
                </div>

                <!-- Role and Entity Selection -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Role & Entity Assignment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select name="role" id="role" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('role') border-red-300 @enderror">
                                <option value="">Select a role</option>
                                @foreach($roles as $role => $display)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ $display }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" id="status" required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('status') border-red-300 @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="association-container" class="hidden">
                            <label for="association_id" class="block text-sm font-medium text-gray-700 mb-1">Football Association</label>
                            <select name="association_id" id="association_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('association_id') border-red-300 @enderror">
                                <option value="">Select Football Association</option>
                                @foreach($associations as $association)
                                <option value="{{ $association->id }}" {{ old('association_id') == $association->id ? 'selected' : '' }}>
                                    {{ $association->name }} ({{ $association->country }})
                                </option>
                                @endforeach
                            </select>
                            @error('association_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="club-container" class="hidden">
                            <label for="club_id" class="block text-sm font-medium text-gray-700 mb-1">Club</label>
                            <select name="club_id" id="club_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('club_id') border-red-300 @enderror">
                                <option value="">Select Club</option>
                                @foreach($clubs as $club)
                                <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>
                                    {{ $club->name }} ({{ $club->country }})
                                </option>
                                @endforeach
                            </select>
                            @error('club_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- FIFA Connect Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">FIFA Connect Integration</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">FIFA Connect ID</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>A unique FIFA Connect ID will be automatically generated for this user based on their role and entity assignment. This ID ensures compliance with FIFA data standards and enables seamless integration with FIFA Connect services.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800">Default Permissions</h3>
                                <div class="mt-2 text-sm text-gray-700">
                                    <p>Permissions will be automatically assigned based on the selected role. These permissions ensure users have appropriate access to system modules while maintaining security and compliance standards.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('user-management.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const associationContainer = document.getElementById('association-container');
    const associationSelect = document.getElementById('association_id');
    const clubContainer = document.getElementById('club-container');
    const clubSelect = document.getElementById('club_id');

    // Role change handler
    roleSelect.addEventListener('change', function() {
        const role = this.value;
        
        // Show/hide containers based on role
        if (['association_admin', 'association_registrar', 'association_medical'].includes(role)) {
            // Association roles - show association, optionally show club
            associationContainer.classList.remove('hidden');
            associationSelect.required = true;
            clubContainer.classList.remove('hidden');
            clubSelect.required = false;
        } else if (['club_admin', 'club_manager', 'club_medical'].includes(role)) {
            // Club roles - show club, optionally show association
            clubContainer.classList.remove('hidden');
            clubSelect.required = true;
            associationContainer.classList.remove('hidden');
            associationSelect.required = false;
        } else {
            // System admin or other roles - hide both
            associationContainer.classList.add('hidden');
            associationSelect.required = false;
            clubContainer.classList.add('hidden');
            clubSelect.required = false;
        }
    });

    // Trigger initial state
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection 