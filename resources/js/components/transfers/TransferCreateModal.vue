<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-medium text-gray-900">{{ $t('auto.key346') }}</h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Informations du joueur -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key347') }}</label>
              <select
                v-model="form.player_id"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">{{ $t('auto.key348') }}</option>
                <option v-for="player in players" :key="player.id" :value="player.id">
                  {{ player.name }} ({{ player.position }}) - {{ player.current_club?.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key349') }}</label>
              <select
                v-model="form.transfer_type"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">{{ $t('auto.key350') }}</option>
                <option value="permanent">{{ $t('auto.key351') }}</option>
                <option value="loan">{{ $t('auto.key352') }}</option>
                <option value="free_agent">{{ $t('auto.key353') }}</option>
              </select>
            </div>
          </div>

          <!-- Clubs -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key354') }}</label>
              <select
                v-model="form.club_origin_id"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">{{ $t('auto.key355') }}</option>
                <option v-for="club in clubs" :key="club.id" :value="club.id">
                  {{ club.name }} ({{ club.country }})
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key356') }}</label>
              <select
                v-model="form.club_destination_id"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">{{ $t('auto.key357') }}</option>
                <option v-for="club in clubs" :key="club.id" :value="club.id">
                  {{ club.name }} ({{ club.country }})
                </option>
              </select>
            </div>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key358') }}</label>
              <input
                v-model="form.transfer_date"
                type="date"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key359') }}</label>
              <input
                v-model="form.contract_start_date"
                type="date"
                required
                :min="form.transfer_date"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key360') }}</label>
              <input
                v-model="form.contract_end_date"
                type="date"
                :min="form.contract_start_date"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key361') }}</label>
              <select
                v-model="form.currency"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="EUR">{{ $t('auto.key362') }}</option>
                <option value="USD">{{ $t('auto.key363') }}</option>
                <option value="GBP">{{ $t('auto.key364') }}</option>
              </select>
            </div>
          </div>

          <!-- Informations financières -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key365') }}</label>
              <input
                v-model="form.transfer_fee"
                type="number"
                min="0"
                step="0.01"
                placeholder="0.00"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div class="flex items-center">
              <input
                v-model="form.is_minor_transfer"
                type="checkbox"
                id="is_minor_transfer"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="is_minor_transfer" class="ml-2 block text-sm text-gray-900">
                Transfert de mineur
              </label>
            </div>
          </div>

          <!-- Conditions spéciales -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key366') }}</label>
            <textarea
              v-model="form.special_conditions"
              rows="3"
              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              placeholder="Conditions spéciales du transfert..."
            ></textarea>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('auto.key367') }}</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              placeholder="Notes internes..."
            ></textarea>
          </div>

          <!-- Informations de validation -->
          <div v-if="isInternational" class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">{{ $t('auto.key368') }}</h3>
                <div class="mt-2 text-sm text-blue-700">
                  <p>{{ $t('auto.key369') }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              <span v-if="loading">{{ $t('auto.key370') }}</span>
              <span v-else>{{ $t('auto.key371') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue'

export default {
  name: 'TransferCreateModal',
  emits: ['close', 'created'],
  setup(props, { emit }) {
    const loading = ref(false)
    const players = ref([])
    const clubs = ref([])

    const form = reactive({
      player_id: '',
      club_origin_id: '',
      club_destination_id: '',
      transfer_type: '',
      transfer_date: '',
      contract_start_date: '',
      contract_end_date: '',
      transfer_fee: '',
      currency: 'EUR',
      is_minor_transfer: false,
      special_conditions: '',
      notes: ''
    })

    const isInternational = computed(() => {
      if (!form.club_origin_id || !form.club_destination_id) return false
      
      const originClub = clubs.value.find(c => c.id == form.club_origin_id)
      const destinationClub = clubs.value.find(c => c.id == form.club_destination_id)
      
      return originClub && destinationClub && originClub.country !== destinationClub.country
    })

    const loadPlayers = async () => {
      try {
        const response = await fetch('/api/players?transfer_eligible=1')
        const data = await response.json()
        if (data.success) {
          players.value = data.data
        }
      } catch (error) {
        console.error('Error loading players:', error)
      }
    }

    const loadClubs = async () => {
      try {
        const response = await fetch('/api/clubs')
        const data = await response.json()
        if (data.success) {
          clubs.value = data.data
        }
      } catch (error) {
        console.error('Error loading clubs:', error)
      }
    }

    const submitForm = async () => {
      loading.value = true
      
      try {
        const response = await fetch('/api/transfers', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            ...form,
            transfer_fee: form.transfer_fee ? parseFloat(form.transfer_fee) : null
          })
        })

        const data = await response.json()
        
        if (data.success) {
          emit('created')
          // Reset form
          Object.keys(form).forEach(key => {
            if (key === 'currency') {
              form[key] = 'EUR'
            } else if (key === 'is_minor_transfer') {
              form[key] = false
            } else {
              form[key] = ''
            }
          })
        } else {
          alert('Erreur: ' + data.message)
        }
      } catch (error) {
        console.error('Error creating transfer:', error)
        alert('Erreur lors de la création du transfert')
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      loadPlayers()
      loadClubs()
    })

    return {
      loading,
      players,
      clubs,
      form,
      isInternational,
      submitForm
    }
  }
}
</script> 