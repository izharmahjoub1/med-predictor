<template>
    <div>
        <!-- Hero Section -->
        <section class="text-center py-16 bg-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $t('landing.hero.title') }}</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-8">{{ $t('landing.hero.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4">
                <router-link to="/login" class="inline-flex items-center px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white text-lg font-semibold rounded-full shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m13-4a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $t('landing.login') }}
                </router-link>
                <!-- Sélecteur de langue -->
                <div class="flex gap-2 mt-2">
                    <button
                        :class="[$i18n.locale === 'fr' ? 'bg-blue-600 text-white' : 'bg-white text-blue-700 border', 'px-4 py-1 rounded-full font-semibold shadow transition']"
                        @click="$i18n.locale = 'fr'"
                    >{{ $t('auto.key150') }}</button>
                    <button
                        :class="[$i18n.locale === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-blue-700 border', 'px-4 py-1 rounded-full font-semibold shadow transition']"
                        @click="$i18n.locale = 'en'"
                    >{{ $t('auto.key151') }}</button>
                </div>
            </div>
        </section>
        <!-- Profiles Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ $t('landing.profiles.title') }}
                </h2>
                <p class="text-lg text-gray-600">
                    {{ $t('landing.profiles.subtitle') }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Player -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key152') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.player.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.player.desc') }}</p>
                </div>
                <!-- Club Staff -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key153') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.clubStaff.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.clubStaff.desc') }}</p>
                </div>
                <!-- Association Staff -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key154') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.associationStaff.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.associationStaff.desc') }}</p>
                </div>
                <!-- Medical Staff -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key155') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.medicalStaff.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.medicalStaff.desc') }}</p>
                </div>
                <!-- Referee -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key156') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.referee.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.referee.desc') }}</p>
                </div>
                <!-- System Admin -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-5xl mb-2">{{ $t('auto.key157') }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ $t('landing.profiles.admin.title') }}</h3>
                    <p class="text-gray-600 text-center">{{ $t('landing.profiles.admin.desc') }}</p>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>
                        {{ $t('landing.footer.copyright') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: "LandingPage"
};
</script>
