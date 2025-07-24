@php
    $user = auth()->user();
@endphp

<!-- Main Navigation Menu - Fixed at top -->
<nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 px-4 py-2 shadow-sm z-50">
    <div class="flex flex-col sm:flex-row items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-3 mb-2 sm:mb-0">
            <img src="{{ asset('images/logos/fit.png') }}" alt="FIT Logo" style="height:60px;width:auto;margin-right:0.75rem;" class="inline-block align-middle">
        </div>
        <!-- Menus principaux -->
        <div class="flex flex-wrap gap-2 sm:gap-6 items-center justify-center">
            <!-- Admin -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.admin') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('user-management.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.user_management') }}</a>
                    @if($user && in_array($user->role, ['system_admin', 'association_admin', 'association_registrar']))
                        <a href="{{ route('admin.account-requests.index') }}" class="block px-4 py-2 hover:bg-blue-50 font-semibold text-blue-700">{{ __('navigation.account_requests') }}</a>
                    @endif
                    <a href="{{ route('role-management.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.role_management') }}</a>
                    <a href="{{ route('audit-trail.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.audit_trail') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('logs.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.logs') }}</a>
                    <a href="{{ route('system-status.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.system_status') }}</a>
                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.settings') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('license-types.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.license_types') }}</a>
                    <a href="{{ route('content.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.content_management') }}</a>
                    <a href="{{ route('stakeholder-gallery.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.gallery') }}</a>
                </div>
            </div>
            <!-- Club Management -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.club_management') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('players.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.players') }}</a>
                    <a href="{{ route('player-registration.create') }}" class="block px-4 py-2 hover:bg-blue-50 font-semibold text-blue-700">{{ __('navigation.register_player') }}</a>
                    <a href="{{ route('club.player-licenses.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.player_license_status') }}</a>
                    <a href="{{ route('player-passports.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.player_passports') }}</a>
                    <a href="{{ route('health-records.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.player_health') }}</a>
                    <a href="{{ route('performances.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.performances') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('teams.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.teams') }}</a>
                    <a href="{{ route('club-player-assignments.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.player_assignments') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('match-sheet.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.match_sheets') }}</a>
                    <a href="{{ route('transfers.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.transfers') }}</a>
                    <a href="{{ route('performance-recommendations.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.recommendations') }}</a>
                </div>
            </div>
            <!-- Association Management -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.association_management') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('competitions.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.competitions') }}</a>
                    <a href="{{ route('fixtures.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fixtures') }}</a>
                    <a href="{{ route('rankings.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.rankings') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('seasons.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.seasons') }}</a>
                    <a href="{{ route('federations.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.federations') }}</a>
                    <a href="{{ route('registration-requests.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.registration_requests') }}</a>
                    <a href="{{ route('licenses.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.licenses') }}</a>
                    <a href="{{ route('player-licenses.index') }}" class="block px-4 py-2 hover:bg-blue-50 font-semibold text-blue-700">{{ __('navigation.player_license_requests') }}</a>
                    <a href="{{ route('contracts.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.contracts') }}</a>
                </div>
            </div>
            <!-- FIFA -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.fifa') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('fifa.dashboard') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fifa_dashboard') }}</a>
                    <a href="{{ route('fifa.connectivity') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fifa_connect') }}</a>
                    <a href="{{ route('fifa.sync-dashboard') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.sync_dashboard') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('fifa.contracts') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fifa_contracts') }}</a>
                    <a href="{{ route('fifa.analytics') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fifa_analytics') }}</a>
                    <a href="{{ route('fifa.statistics') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.fifa_statistics') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('daily-passport.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.daily_passports') }}</a>
                    <a href="{{ route('data-sync.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.data_sync') }}</a>
                    <a href="{{ route('fifa.players.search') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.player_search') }}</a>
                </div>
            </div>
            <!-- Device Connections -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.device_connections') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('device-connections.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.device_dashboard') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('apple-health-kit.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.apple_health_kit') }}</a>
                    <a href="{{ route('catapult-connect.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.catapult_connect') }}</a>
                    <a href="{{ route('garmin-connect.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.garmin_connect') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('device-connections.oauth2.tokens') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.oauth2_tokens') }}</a>
                </div>
            </div>
            <!-- Healthcare -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.healthcare') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('healthcare.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.healthcare_dashboard') }}</a>
                    <a href="{{ route('healthcare.predictions') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.medical_predictions') }}</a>
                    <a href="{{ route('healthcare.export') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.export_data') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('health-records.index') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.health_records') }}</a>
                    <a href="{{ route('medical-predictions.dashboard') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.prediction_models') }}</a>
                </div>
            </div>
            <!-- Referee Portal -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.referee_portal') }}</button>
                <div x-show="open" @click.away="open = false" class="absolute z-20 bg-white border rounded shadow-lg mt-2 min-w-[200px]">
                    <a href="{{ route('referee.dashboard') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.dashboard') }}</a>
                    <a href="{{ route('referee.match-assignments') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.match_assignments') }}</a>
                    <a href="{{ route('referee.competition-schedule') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.competition_schedule') }}</a>
                    <div class="border-t my-1"></div>
                    <a href="{{ route('referee.create-match-report') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.create_match_report') }}</a>
                    <a href="{{ route('referee.performance-stats') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.performance_stats') }}</a>
                    <a href="{{ route('referee.settings') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.settings') }}</a>
                </div>
            </div>
            <li>
                <a href="{{ url('/performances') }}" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.performance') }}</a>
            </li>
            <!-- DTN Manager -->
            <li>
                <a href="{{ url('/dtn') }}" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.dtn_manager') }}</a>
            </li>
            <!-- RPM -->
            <li>
                <a href="{{ url('/rpm') }}" class="px-3 py-2 rounded hover:bg-blue-100 font-semibold text-gray-700 hover:text-blue-700 transition-colors">{{ __('navigation.rpm') }}</a>
            </li>
        </div>
        <!-- Notifications -->
        <div class="mt-2 sm:mt-0 mr-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center px-3 py-2 rounded hover:bg-blue-100 relative">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @php $unread = auth()->user() ? auth()->user()->unreadNotifications()->count() : 0; @endphp
                    @if($unread > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $unread }}</span>
                    @endif
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 z-20 bg-white border rounded shadow-lg mt-2 min-w-[300px] max-h-96 overflow-y-auto">
                    <div class="p-2 font-semibold border-b">{{ __('navigation.notifications') }}</div>
                    @forelse(auth()->user() ? auth()->user()->unreadNotifications : collect() as $notification)
                        <div class="px-4 py-2 border-b hover:bg-blue-50">
                            <div class="text-sm">{!! $notification->data['new_status'] ?? '' !!} - <a href="{{ route('license-requests.show', $notification->data['license_request_id'] ?? 0) }}" class="text-blue-600 hover:underline">Voir la demande</a></div>
                            @if(!empty($notification->data['comment']))
                                <div class="text-xs text-gray-600 mt-1">{{ $notification->data['comment'] }}</div>
                            @endif
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="mt-1">
                                @csrf
                                <button type="submit" class="text-xs text-green-600 hover:underline">{{ __('navigation.mark_as_read') }}</button>
                            </form>
                        </div>
                    @empty
                        <div class="px-4 py-2 text-gray-500">{{ __('navigation.no_unread_notifications') }}</div>
                    @endforelse
                    <div class="p-2 text-right">
                        <a href="{{ route('profile.show') }}#notifications" class="text-blue-600 hover:underline text-xs">{{ __('navigation.view_all_notifications') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profil utilisateur / Logout -->
        <div class="mt-2 sm:mt-0">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center px-3 py-2 rounded hover:bg-blue-100">
                    <span class="mr-2 font-semibold">{{ $user ? $user->name : __('navigation.user') }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 z-20 bg-white border rounded shadow-lg mt-2 min-w-[150px]">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-blue-50">{{ __('navigation.profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-blue-50">{{ __('navigation.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav> 