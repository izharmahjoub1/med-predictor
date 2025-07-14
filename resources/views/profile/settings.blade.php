@extends('layouts.app')

@section('title', 'Paramètres - Med Predictor')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Settings Information -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-settings-form')
            </div>
        </div>

        <!-- Notification Preferences -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.notification-preferences-form')
            </div>
        </div>

        <!-- Security Settings -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.security-settings-form')
            </div>
        </div>
    </div>
</div>
@endsection 