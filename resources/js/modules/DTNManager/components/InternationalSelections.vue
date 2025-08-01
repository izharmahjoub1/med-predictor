<template>
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{$t('dtn.international_selections.title')}}
                </h2>
                <div class="flex space-x-3">
                    <button
                        @click="createSelection"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>{{ $t('dtn.international_selections.create_button') }}</span>
                    </button>
                    <button
                        @click="exportToFIFA"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ $t('dtn.international_selections.export_button') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('dtn.international_selections.filters.team') }}</label>
                    <select v-model="filters.team" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ $t('dtn.international_selections.filters.team_placeholder') }}</option>
                        <option v-for="team in teams" :key="team.id" :value="team.id">
                            {{ team.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('dtn.international_selections.filters.competition') }}</label>
                    <select v-model="filters.competition" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ $t('dtn.international_selections.filters.competition_placeholder') }}</option>
                        <option value="fifa_world_cup">{{ $t('dtn.international_selections.competitions.fifa_world_cup') }}</option>
                        <option value="africa_cup">{{ $t('dtn.international_selections.competitions.africa_cup') }}</option>
                        <option value="friendly">{{ $t('dtn.international_selections.competitions.friendly') }}</option>
                        <option value="qualification">{{ $t('dtn.international_selections.competitions.qualification') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('dtn.international_selections.filters.status') }}</label>
                    <select v-model="filters.status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ $t('dtn.international_selections.filters.status_placeholder') }}</option>
                        <option value="draft">{{ $t('dtn.international_selections.status.draft') }}</option>
                        <option value="published">{{ $t('dtn.international_selections.status.published') }}</option>
                        <option value="confirmed">{{ $t('dtn.international_selections.status.confirmed') }}</option>
                        <option value="completed">{{ $t('dtn.international_selections.status.completed') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('dtn.international_selections.filters.date') }}</label>
                    <input type="date" v-model="filters.date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div class="flex items-end">
                    <button @click="applyFilters" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors">
                        {{ $t('dtn.international_selections.filters.apply') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Selections List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.team') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.competition') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.players') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('dtn.international_selections.table.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="selection in filteredSelections" :key="selection.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ selection.team.charAt(0) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ selection.team }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ selection.opponent }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getCompetitionClass(selection.competition)">
                                {{ getCompetitionLabel(selection.competition) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ formatDate(selection.date) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ selection.playerCount }} / {{ selection.maxPlayers }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStatusClass(selection.status)">
                                {{ getStatusLabel(selection.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button @click="viewSelection(selection)" class="text-blue-600 hover:text-blue-900">
                                    {{ $t('dtn.international_selections.actions.view') }}
                                </button>
                                <button @click="editSelection(selection)" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $t('dtn.international_selections.actions.edit') }}
                                </button>
                                <button @click="managePlayers(selection)" class="text-green-600 hover:text-green-900">
                                    {{ $t('dtn.international_selections.actions.players') }}
                                </button>
                                <button @click="notifyClubs(selection)" class="text-yellow-600 hover:text-yellow-900">
                                    {{ $t('dtn.international_selections.actions.notify') }}
                                </button>
                                <button @click="exportSelection(selection)" class="text-purple-600 hover:text-purple-900">
                                    {{ $t('dtn.international_selections.actions.export') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    {{ $t('dtn.international_selections.pagination.displaying', { from: pagination.from, to: pagination.to, total: pagination.total }) }}
                </div>
                <div class="flex space-x-2">
                    <button @click="previousPage" :disabled="pagination.currentPage === 1" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ $t('dtn.international_selections.pagination.previous') }}
                    </button>
                    <button @click="nextPage" :disabled="pagination.currentPage === pagination.lastPage" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ $t('dtn.international_selections.pagination.next') }}
                    </button>
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
const selections = ref([]);
const teams = ref([]);
const filters = ref({
    team: "",
    competition: "",
    status: "",
    date: "",
});

const pagination = ref({
    currentPage: 1,
    lastPage: 1,
    total: 0,
    from: 0,
    to: 0,
    perPage: 10,
});

// Computed
const filteredSelections = computed(() => {
    let filtered = selections.value;

    if (filters.value.team) {
        filtered = filtered.filter(
            (selection) => selection.teamId === filters.value.team
        );
    }

    if (filters.value.competition) {
        filtered = filtered.filter(
            (selection) => selection.competition === filters.value.competition
        );
    }

    if (filters.value.status) {
        filtered = filtered.filter(
            (selection) => selection.status === filters.value.status
        );
    }

    if (filters.value.date) {
        filtered = filtered.filter((selection) => {
            const selectionDate = new Date(selection.date)
                .toISOString()
                .split("T")[0];
            return selectionDate === filters.value.date;
        });
    }

    return filtered;
});

// Methods
const loadSelections = async () => {
    try {
        // Simuler le chargement des sélections
        selections.value = [
            {
                id: 1,
                team: "Équipe A",
                teamId: 1,
                opponent: "Maroc",
                competition: "friendly",
                date: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000),
                playerCount: 23,
                maxPlayers: 23,
                status: "published",
            },
            {
                id: 2,
                team: "Équipe A",
                teamId: 1,
                opponent: "Sénégal",
                competition: "africa_cup",
                date: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000),
                playerCount: 20,
                maxPlayers: 23,
                status: "draft",
            },
            {
                id: 3,
                team: "U23",
                teamId: 3,
                opponent: "Égypte",
                competition: "qualification",
                date: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000),
                playerCount: 18,
                maxPlayers: 23,
                status: "confirmed",
            },
            {
                id: 4,
                team: "Équipe A Femmes",
                teamId: 2,
                opponent: "Nigeria",
                competition: "fifa_world_cup",
                date: new Date(Date.now() + 60 * 24 * 60 * 60 * 1000),
                playerCount: 22,
                maxPlayers: 23,
                status: "draft",
            },
        ];

        teams.value = [
            { id: 1, name: "Équipe A" },
            { id: 2, name: "Équipe A Femmes" },
            { id: 3, name: "U23" },
            { id: 4, name: "U20" },
        ];

        pagination.value = {
            currentPage: 1,
            lastPage: Math.ceil(
                selections.value.length / pagination.value.perPage
            ),
            total: selections.value.length,
            from: 1,
            to: Math.min(pagination.value.perPage, selections.value.length),
            perPage: 10,
        };
    } catch (error) {
        console.error("Erreur lors du chargement des sélections:", error);
    }
};

const applyFilters = () => {
    console.log("Filtres appliqués:", filters.value);
};

const createSelection = () => {
    router.push("/dtn/selections/create");
};

const viewSelection = (selection) => {
    router.push(`/dtn/selections/${selection.id}`);
};

const editSelection = (selection) => {
    router.push(`/dtn/selections/${selection.id}/edit`);
};

const managePlayers = (selection) => {
    router.push(`/dtn/selections/${selection.id}/players`);
};

const notifyClubs = async (selection) => {
    try {
        console.log(
            "Notification des clubs pour la sélection:",
            selection.team
        );
        // Ici on appellerait le service de notification
    } catch (error) {
        console.error("Erreur lors de la notification:", error);
    }
};

const exportSelection = async (selection) => {
    try {
        console.log("Export de la sélection:", selection.team);
        // Ici on appellerait le service d'export
    } catch (error) {
        console.error("Erreur lors de l'export:", error);
    }
};

const exportToFIFA = async () => {
    try {
        console.log("Export vers FIFA Connect...");
        // Ici on appellerait le service FIFA Connect
    } catch (error) {
        console.error("Erreur lors de l'export FIFA:", error);
    }
};

const getCompetitionClass = (competition) => {
    const classes = {
        fifa_world_cup: "bg-red-100 text-red-800",
        africa_cup: "bg-green-100 text-green-800",
        friendly: "bg-blue-100 text-blue-800",
        qualification: "bg-yellow-100 text-yellow-800",
    };
    return classes[competition] || "bg-gray-100 text-gray-800";
};

const getCompetitionLabel = (competition) => {
    const labels = {
        fifa_world_cup: "Coupe du Monde",
        africa_cup: "Coupe d'Afrique",
        friendly: "Match Amical",
        qualification: "Qualifications",
    };
    return labels[competition] || competition;
};

const getStatusClass = (status) => {
    const classes = {
        draft: "bg-gray-100 text-gray-800",
        published: "bg-blue-100 text-blue-800",
        confirmed: "bg-green-100 text-green-800",
        completed: "bg-purple-100 text-purple-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusLabel = (status) => {
    const labels = {
        draft: "Brouillon",
        published: "Publiée",
        confirmed: "Confirmée",
        completed: "Terminée",
    };
    return labels[status] || status;
};

const formatDate = (date) => {
    if (!date) return "N/A";
    return new Intl.DateTimeFormat("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    }).format(date);
};

const previousPage = () => {
    if (pagination.value.currentPage > 1) {
        pagination.value.currentPage--;
    }
};

const nextPage = () => {
    if (pagination.value.currentPage < pagination.value.lastPage) {
        pagination.value.currentPage++;
    }
};

// Lifecycle
onMounted(() => {
    loadSelections();
});
</script>

<style scoped>
/* Styles spécifiques au composant InternationalSelections */
</style>
