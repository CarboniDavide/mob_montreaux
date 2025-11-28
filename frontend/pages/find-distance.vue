<template>
	<v-container class="py-10">
		<v-row justify="center">
			<v-col cols="12" md="10" lg="8">
				<v-card elevation="6" class="pa-8">
					<div class="text-center mb-6">
						<v-icon size="56" color="primary" class="mb-3">mdi-map-marker-distance</v-icon>
						<v-card-title class="text-h4 font-weight-bold mb-2">Route Calculator</v-card-title>
						<p class="text-subtitle-1 text-grey-darken-1">Find the shortest path between stations using Dijkstra's algorithm</p>
					</div>

					<v-card-text>
						<v-row>
							<v-col cols="12" md="6">
						<v-autocomplete
							v-model="fromShort"
							:items="stations"
							item-title="label"
							item-value="short"
							label="From station"
							:loading="loadingStations"
							clearable
							variant="outlined"
							prepend-inner-icon="mdi-map-marker"
						/>						<v-autocomplete
							v-model="toShort"
							:items="stations"
							item-title="label"
							item-value="short"
							label="To station"
							:loading="loadingStations"
							clearable
							variant="outlined"
							prepend-inner-icon="mdi-flag-checkered"
							class="mt-4"
						/>								<v-text-field 
									v-model="analyticCode" 
									label="Analytic code (optional)" 
									placeholder="e.g. WEB-UI" 
									prepend-inner-icon="mdi-tag"
									variant="outlined"
									class="mt-4"
								/>
							</v-col>

							<v-divider vertical class="mx-6"></v-divider>

							<v-col cols="12" md="5" class="d-flex flex-column justify-center align-center">
								<v-btn 
									color="primary" 
									@click="findRoute" 
									:loading="finding" 
									:disabled="!canQuery"
									size="x-large"
									prepend-icon="mdi-routes"
									variant="flat"
									block
									class="mb-4"
								>
									Calculate
								</v-btn>
								<v-btn 
									variant="outlined" 
									@click="reset"
									size="x-large"
									prepend-icon="mdi-refresh"
									block
								>
									Reset
								</v-btn>
							</v-col>
						</v-row>

						<v-divider class="my-6" />

						<div v-if="error" class="mb-4">
							<v-alert type="error" dense>{{ error }}</v-alert>
						</div>

						<div v-if="routeResult" class="mt-6">
							<v-card outlined class="pa-6" elevation="3">
								<div class="d-flex justify-space-between align-center mb-4">
									<div>
										<div class="text-subtitle-1 text-grey-darken-1">Total Distance</div>
										<div class="text-h4 text-primary font-weight-bold">{{ routeResult.distanceKm.toFixed(3) }} km</div>
									</div>
									<div class="text-right">
										<v-chip color="success" variant="flat">
											<v-icon start size="small">mdi-check-circle</v-icon>
											Route Found
										</v-chip>
										<div class="text-caption text-grey-darken-1 mt-2">ID: {{ routeResult.id }}</div>
									</div>
								</div>

								<v-divider class="my-4" />

								<div>
									<div class="text-h6 mb-3 d-flex align-center">
										<v-icon class="mr-2" color="primary">mdi-routes</v-icon>
										Station Path
									</div>
									<v-chip-group>
										<v-chip 
											v-for="(s, i) in routeResult.path" 
											:key="i" 
											class="ma-1" 
											:color="i === 0 ? 'success' : i === routeResult.path.length - 1 ? 'error' : 'primary'" 
											variant="flat"
										>
											<v-icon v-if="i === 0" start size="small">mdi-map-marker</v-icon>
											<v-icon v-else-if="i === routeResult.path.length - 1" start size="small">mdi-flag-checkered</v-icon>
											{{ s }}
										</v-chip>
									</v-chip-group>
								</div>
							</v-card>
						</div>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useStationsStore } from '~/stores/stations'
import { useRoutesStore } from '~/stores/routes'

// Default values
const DEFAULT_FROM = 'MX'
const DEFAULT_TO = 'CGE'
const DEFAULT_ANALYTIC_CODE = 'WEB'

const auth = useAuthStore()
const stationsStore = useStationsStore()
const routesStore = useRoutesStore()

definePageMeta({
  requiresAuth: true
})

onMounted(async () => {
	await auth.init()
	await stationsStore.fetchStations()
})

// Stations
const stations = computed(() => stationsStore.stations)
const loadingStations = computed(() => stationsStore.loading)

// Form fields
const fromShort = ref<string | null>(DEFAULT_FROM)
const toShort = ref<string | null>(DEFAULT_TO)
const analyticCode = ref<string>(DEFAULT_ANALYTIC_CODE)

// Route calculation
const finding = computed(() => routesStore.loading)
const routeResult = computed(() => routesStore.currentRoute)
const error = computed(() => routesStore.error)

// Validation
const canQuery = computed(() => 
	fromShort.value && 
	toShort.value && 
	fromShort.value !== toShort.value
)

async function findRoute() {
	if (!canQuery.value) return
	
	await routesStore.calculateRoute(
		fromShort.value!,
		toShort.value!,
		analyticCode.value || 'WEB'
	)
}

function reset() {
	fromShort.value = null
	toShort.value = null
	analyticCode.value = DEFAULT_ANALYTIC_CODE
	routesStore.clearCurrentRoute()
}
</script>

<style scoped>
.text-right { text-align: right; }
</style>