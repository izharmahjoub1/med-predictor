<template>
  <nav class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo and Brand -->
        <div class="flex items-center">
          <router-link to="/dashboard" class="flex items-center">
            <div class="text-2xl font-bold">
              <span class="text-gray-800">{{ $t('navigation.brand.fit') }}</span>
              <span class="bg-blue-600 text-white px-1 relative">
                I
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 20 20" fill="none">
                  <path d="M2 10 L6 6 L10 10 L14 6 L18 10" stroke="white" stroke-width="2" fill="none"/>
                </svg>
              </span>
              <span class="text-gray-800">{{ $t('navigation.brand.intelligence') }}</span>
            </div>
            <div class="ml-3 text-sm text-blue-600 font-semibold uppercase tracking-wide hidden sm:block">
              <div>{{ $t('navigation.brand.football') }}</div>
              <div>{{ $t('navigation.brand.health') }}</div>
              <div>{{ $t('navigation.brand.fifaConnect') }}</div>
            </div>
          </router-link>
          
          <!-- FIFA Connect Badge -->
          <div class="hidden sm:flex items-center ml-6">
            <div class="bg-green-100 border border-green-300 rounded-lg px-3 py-1">
              <div class="flex items-center">
                <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-medium text-green-800">{{ $t('navigation.brand.fifaConnect') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex items-center">
          <!-- Dashboard -->
          <router-link 
            to="/dashboard" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            :class="{ 'text-blue-600 bg-blue-50': $route.name === 'dashboard' }"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
            </svg>
            {{ $t('navigation.main.dashboard') }}
          </router-link>

          <!-- Players Dropdown -->
          <div class="relative" v-if="hasPermission('player_access')">
            <button 
              @click="toggleDropdown('players')"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
              {{ $t('navigation.main.players') }}
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <div v-if="activeDropdown === 'players'" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link 
                to="/player-dashboard" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ $t('navigation.dropdowns.players.playerDashboard') }}
              </router-link>
              <router-link 
                to="/player-registration" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                v-if="hasPermission('player_registration')"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                {{ $t('navigation.dropdowns.players.playerRegistration') }}
              </router-link>
            </div>
          </div>

          <!-- Competitions Dropdown -->
          <div class="relative" v-if="hasPermission('competition_access')">
            <button 
              @click="toggleDropdown('competitions')"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              {{ $t('navigation.main.competitions') }}
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <div v-if="activeDropdown === 'competitions'" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link 
                to="/league-championship" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                {{ $t('navigation.dropdowns.competitions.leagueChampionship') }}
              </router-link>
              <router-link 
                to="/competitions" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ $t('navigation.dropdowns.competitions.competitionManagement') }}
              </router-link>
              <router-link 
                to="/rankings" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                {{ $t('navigation.dropdowns.competitions.rankings') }}
              </router-link>
            </div>
          </div>

          <!-- Performance -->
          <router-link 
            to="/performances" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            :class="{ 'text-blue-600 bg-blue-50': $route.path.startsWith('/performances') }"
            v-if="hasPermission('performance_access')"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            {{ $t('navigation.main.performance') }}
          </router-link>

          <!-- Medical -->
          <router-link 
            to="/medical" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            :class="{ 'text-blue-600 bg-blue-50': $route.path.startsWith('/medical') }"
            v-if="hasPermission('medical_access')"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            {{ $t('navigation.main.medical') }}
          </router-link>

          <!-- Transfers -->
          <router-link 
            to="/transfers" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            :class="{ 'text-blue-600 bg-blue-50': $route.path.startsWith('/transfers') }"
            v-if="hasPermission('transfer_access')"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            {{ $t('navigation.main.transfers') }}
          </router-link>

          <!-- FIFA -->
          <div class="relative" v-if="hasPermission('fifa_access')">
            <button 
              @click="toggleDropdown('fifa')"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
              </svg>
              {{ $t('navigation.main.fifa') }}
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <div v-if="activeDropdown === 'fifa'" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link 
                to="/fifa/sync-dashboard" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ $t('navigation.dropdowns.fifa.fifaSyncDashboard') }}
              </router-link>
              <router-link 
                to="/fifa-dashboard" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ $t('navigation.dropdowns.fifa.fifaDashboard') }}
              </router-link>
            </div>
          </div>

          <!-- Admin -->
          <div class="relative" v-if="hasPermission('admin_access')">
            <button 
              @click="toggleDropdown('admin')"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              {{ $t('navigation.main.admin') }}
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <div v-if="activeDropdown === 'admin'" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link 
                to="/back-office/dashboard" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                {{ $t('navigation.dropdowns.admin.backOffice') }}
              </router-link>
              <router-link 
                to="/user-management/dashboard" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                {{ $t('navigation.dropdowns.admin.userManagement') }}
              </router-link>
              <router-link 
                to="/role-management" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                {{ $t('navigation.dropdowns.admin.roleManagement') }}
              </router-link>
            </div>
          </div>
        </div>

        <!-- User Menu -->
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <div class="ml-3 relative">
            <button 
              @click="toggleDropdown('user')"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
              <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center">
                  <span class="text-white font-semibold text-sm">{{ userInitials }}</span>
                </div>
                <div class="text-left">
                  <div class="text-sm font-medium text-gray-900">{{ userName }}</div>
                  <div class="text-xs text-gray-500 capitalize">{{ userRole }}</div>
                </div>
              </div>
              <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <!-- Language Selector -->
            <div class="ml-4 inline-block align-middle">
              <button v-if="$i18n.locale !== 'fr'" @click="switchLocale('fr')" class="px-2 py-1 text-xs rounded hover:bg-blue-50">Français</button>
              <button v-if="$i18n.locale !== 'en'" @click="switchLocale('en')" class="px-2 py-1 text-xs rounded hover:bg-blue-50">English</button>
            </div>
            
            <div v-if="activeDropdown === 'user'" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
              <router-link 
                to="/profile" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ $t('navigation.dropdowns.user.profile') }}
              </router-link>
              <router-link 
                to="/settings" 
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $t('navigation.dropdowns.user.settings') }}
              </router-link>
              <div class="border-t border-gray-100"></div>
              <button 
                @click="logout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                {{ $t('navigation.dropdowns.user.logout') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="mobileMenuOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <router-link 
          to="/dashboard" 
          class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out"
          :class="{ 'border-blue-500 text-blue-700 bg-blue-50': $route.name === 'dashboard' }"
        >
          {{ $t('navigation.main.dashboard') }}
        </router-link>
        <!-- Add more mobile menu items as needed -->
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  data() {
    return {
      activeDropdown: null,
      mobileMenuOpen: false,
      userPermissions: {},
      userName: 'User',
      userRole: 'user',
      userInitials: 'U'
    }
  },
  mounted() {
    this.loadUserData();
    this.setupClickOutside();
  },
  methods: {
    toggleDropdown(dropdown) {
      this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;
    },
    
    loadUserData() {
      // Load user data from meta tags or API
      const userMeta = document.querySelector('meta[name="user-data"]');
      if (userMeta) {
        try {
          const userData = JSON.parse(userMeta.content);
          this.userName = userData.name || 'User';
          this.userRole = userData.role || 'user';
          this.userInitials = userData.initials || 'U';
          this.userPermissions = userData.permissions || {};
        } catch (e) {
          console.warn('Failed to parse user data');
        }
      }
    },
    
    hasPermission(permission) {
      return this.userPermissions[permission] || false;
    },
    
    setupClickOutside() {
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.relative')) {
          this.activeDropdown = null;
        }
      });
    },
    
    logout() {
      // Handle logout
      this.$router.push('/logout');
    },
    switchLocale(locale) {
      this.$i18n.locale = locale;
      fetch(`/lang/${locale}`).then(() => {
        // Optionnel : recharger la page si besoin
        // window.location.reload();
      });
    }
  }
}
</script>

<style scoped>
.router-link-active {
  @apply text-blue-600 bg-blue-50;
}

.router-link-exact-active {
  @apply text-blue-600 bg-blue-50;
}
</style> 