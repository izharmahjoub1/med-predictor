<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            ⚽ RPM - Régulation & Préparation Matchs
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Planification des entraînements et gestion de la
                            charge de travail
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="createSession"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                            <span>{{ $t('auto.key1') }}</span>
                        </button>
                        <button
                            @click="exportToPerformance"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                            <span>{{ $t('auto.key2') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistics Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Sessions Cette Semaine
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.weeklySessions }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Charge Moyenne
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.averageLoad }} RPE
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Temps Total
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.totalTime }}h
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Joueurs à Risque
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.riskPlayers }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Training Calendar -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-gray-800">
                                    Calendrier d'Entraînement
                                </h2>
                                <div class="flex space-x-2">
                                    <button
                                        @click="previousWeek"
                                        class="p-2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 19l-7-7 7-7"
                                            ></path>
                                        </svg>
                                    </button>
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >{{ currentWeekLabel }}</span
                                    >
                                    <button
                                        @click="nextWeek"
                                        class="p-2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Weekly Calendar -->
                            <div class="grid grid-cols-7 gap-4">
                                <div
                                    v-for="day in weekDays"
                                    :key="day.date"
                                    class="min-h-32 border border-gray-200 rounded-lg p-3"
                                >
                                    <div class="text-center mb-2">
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ day.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatDate(day.date) }}
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <div
                                            v-for="session in day.sessions"
                                            :key="session.id"
                                            @click="viewSession(session)"
                                            class="p-2 bg-blue-50 border border-blue-200 rounded text-xs cursor-pointer hover:bg-blue-100"
                                        >
                                            <p
                                                class="font-medium text-blue-900"
                                            >
                                                {{ session.title }}
                                            </p>
                                            <p class="text-blue-700">
                                                {{ session.time }}
                                            </p>
                                            <p class="text-blue-600">
                                                {{ session.type }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Monitoring -->
                <div class="space-y-6">
                    <!-- Player Load Monitor -->
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Charge des Joueurs
                            </h3>
                        </div>
                        <div class="p-6">
                            <div
                                v-if="playerLoads.length === 0"
                                class="text-center text-gray-500 py-4"
                            >
                                Aucune donnée de charge
                            </div>
                            <div v-else class="space-y-3">
                                <div
                                    v-for="player in playerLoads"
                                    :key="player.id"
                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                                >
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ player.name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ player.position }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-lg font-semibold"
                                            :class="getLoadColor(player.load)"
                                        >
                                            {{ player.load }} RPE
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ player.sessions }} sessions
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sessions -->
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Sessions Récentes
                            </h3>
                        </div>
                        <div class="p-6">
                            <div
                                v-if="recentSessions.length === 0"
                                class="text-center text-gray-500 py-4"
                            >
                                Aucune session récente
                            </div>
                            <div v-else class="space-y-3">
                                <div
                                    v-for="session in recentSessions"
                                    :key="session.id"
                                    @click="viewSession(session)"
                                    class="p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
                                >
                                    <p class="font-medium text-gray-900">
                                        {{ session.title }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ formatDate(session.date) }} -
                                        {{ session.time }}
                                    </p>
                                    <div class="flex items-center mt-2">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                            :class="
                                                getSessionTypeClass(
                                                    session.type
                                                )
                                            "
                                        >
                                            {{ session.type }}
                                        </span>
                                        <span class="ml-2 text-xs text-gray-500"
                                            >{{
                                                session.playerCount
                                            }}
                                            joueurs</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Actions Rapides
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <button
                                @click="createSession"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                Nouvelle Session
                            </button>
                            <button
                                @click="createMatch"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                Préparer Match
                            </button>
                            <button
                                @click="viewAttendance"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                Présences
                            </button>
                            <button
                                @click="exportData"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                Exporter Données
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

// Router
const router = useRouter();

// Reactive data
const stats = ref({
    weeklySessions: 0,
    averageLoad: 0,
    totalTime: 0,
    riskPlayers: 0,
});

const currentWeek = ref(new Date());
const weekDays = ref([]);
const playerLoads = ref([]);
const recentSessions = ref([]);

// Computed
const currentWeekLabel = computed(() => {
    const start = new Date(currentWeek.value);
    start.setDate(start.getDate() - start.getDay());
    const end = new Date(start);
    end.setDate(end.getDate() + 6);

    return `${formatDate(start)} - ${formatDate(end)}`;
});

// Methods
const loadDashboardData = async () => {
    try {
        // Simuler le chargement des données
        stats.value = {
            weeklySessions: 12,
            averageLoad: 7.2,
            totalTime: 18.5,
            riskPlayers: 3,
        };

        generateWeekDays();
        loadPlayerLoads();
        loadRecentSessions();
    } catch (error) {
        console.error("Erreur lors du chargement des données:", error);
    }
};

const generateWeekDays = () => {
    const start = new Date(currentWeek.value);
    start.setDate(start.getDate() - start.getDay());

    const days = [];
    for (let i = 0; i < 7; i++) {
        const date = new Date(start);
        date.setDate(date.getDate() + i);

        days.push({
            date: date,
            name: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"][
                date.getDay()
            ],
            sessions: generateSessionsForDay(date),
        });
    }

    weekDays.value = days;
};

const generateSessionsForDay = (date) => {
    const sessions = [];
    const dayOfWeek = date.getDay();

    // Lundi, Mercredi, Vendredi : entraînements
    if ([1, 3, 5].includes(dayOfWeek)) {
        sessions.push({
            id: `session-${date.getTime()}-1`,
            title: "Entraînement Technique",
            time: "09:00 - 11:00",
            type: "Technique",
        });
    }

    // Mardi, Jeudi : préparation physique
    if ([2, 4].includes(dayOfWeek)) {
        sessions.push({
            id: `session-${date.getTime()}-2`,
            title: "Préparation Physique",
            time: "14:00 - 16:00",
            type: "Physique",
        });
    }

    // Samedi : match ou récupération
    if (dayOfWeek === 6) {
        sessions.push({
            id: `session-${date.getTime()}-3`,
            title: "Match Amical",
            time: "16:00 - 18:00",
            type: "Match",
        });
    }

    return sessions;
};

const loadPlayerLoads = () => {
    playerLoads.value = [
        {
            id: 1,
            name: "Ahmed Ben Ali",
            position: "Attaquant",
            load: 8.5,
            sessions: 5,
        },
        {
            id: 2,
            name: "Karim Benzema",
            position: "Attaquant",
            load: 7.2,
            sessions: 4,
        },
        {
            id: 3,
            name: "Hakim Ziyech",
            position: "Milieu",
            load: 6.8,
            sessions: 5,
        },
        {
            id: 4,
            name: "Achraf Hakimi",
            position: "Défenseur",
            load: 9.1,
            sessions: 6,
        },
    ];
};

const loadRecentSessions = () => {
    recentSessions.value = [
        {
            id: 1,
            title: "Entraînement Technique",
            date: new Date(Date.now() - 24 * 60 * 60 * 1000),
            time: "09:00 - 11:00",
            type: "Technique",
            playerCount: 18,
        },
        {
            id: 2,
            title: "Préparation Physique",
            date: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000),
            time: "14:00 - 16:00",
            type: "Physique",
            playerCount: 20,
        },
        {
            id: 3,
            title: "Match Amical",
            date: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000),
            time: "16:00 - 18:00",
            type: "Match",
            playerCount: 22,
        },
    ];
};

const createSession = () => {
    router.push("/rpm/sessions/create");
};

const createMatch = () => {
    router.push("/rpm/matches/create");
};

const viewSession = (session) => {
    router.push(`/rpm/sessions/${session.id}`);
};

const viewAttendance = () => {
    router.push("/rpm/attendance");
};

const exportData = async () => {
    try {
        console.log("Export des données RPM...");
        // Ici on appellerait le service d'export
    } catch (error) {
        console.error("Erreur lors de l'export:", error);
    }
};

const exportToPerformance = async () => {
    try {
        console.log("Export vers le module Performance...");
        // Ici on appellerait le service de synchronisation avec le module Performance
    } catch (error) {
        console.error("Erreur lors de l'export vers Performance:", error);
    }
};

const previousWeek = () => {
    currentWeek.value.setDate(currentWeek.value.getDate() - 7);
    generateWeekDays();
};

const nextWeek = () => {
    currentWeek.value.setDate(currentWeek.value.getDate() + 7);
    generateWeekDays();
};

const formatDate = (date) => {
    if (!date) return "N/A";
    return new Intl.DateTimeFormat("fr-FR", {
        day: "2-digit",
        month: "2-digit",
    }).format(date);
};

const getLoadColor = (load) => {
    if (load >= 8) return "text-red-600";
    if (load >= 6) return "text-yellow-600";
    return "text-green-600";
};

const getSessionTypeClass = (type) => {
    const classes = {
        Technique: "bg-blue-100 text-blue-800",
        Physique: "bg-green-100 text-green-800",
        Match: "bg-red-100 text-red-800",
        Récupération: "bg-gray-100 text-gray-800",
    };
    return classes[type] || "bg-gray-100 text-gray-800";
};

// Lifecycle
onMounted(() => {
    loadDashboardData();
});
</script>

<style scoped>
/* Styles spécifiques au module RPM */
</style>
