<template>
  <div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800">{{ $t('auto.key21') }}</h2>
    </div>
    <div class="p-6">
      <p class="text-gray-600">{{ $t('auto.key22') }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Reactive data
const data = ref({})

// Methods
const loadData = async () => {
  // Simulation de données
  data.value = {}
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>

<style scoped>
/* Styles spécifiques au composant MatchesList */
</style>
