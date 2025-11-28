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
									:disabled="!isReady"
									clearable
									variant="outlined"
									prepend-inner-icon="mdi-map-marker"
								/>

								<v-autocomplete
									v-model="toShort"
									:items="stations"
									item-title="label"
									item-value="short"
									label="To station"
									:loading="loadingStations"
									:disabled="!isReady"
									clearable
									variant="outlined"
									prepend-inner-icon="mdi-flag-checkered"
									class="mt-4"
								/>

								<v-text-field 
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

						<div v-if="!isLogged" class="mb-4">
							<v-alert type="info" dense>
								Authentication required to query routes. Please <NuxtLink to="/login">login</NuxtLink> or <NuxtLink to="/register">create an account</NuxtLink>.
							</v-alert>
						</div>

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
import { ref, computed, onMounted, watch } from 'vue'
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const authInitialized = ref(false)

definePageMeta({
  requiresAuth: true
})

onMounted(async () => {
	await auth.init()
	authInitialized.value = true
	loadStations()
})

const isLogged = computed(() => auth.isLogged)

const stations = ref<Array<{ id: number; short: string; long: string; label: string }>>([])
const loadingStations = ref(false)

const fromShort = ref<string | null>('MX')
const toShort = ref<string | null>('CGE')
const analyticCode = ref<string>('WEB')

const finding = ref(false)
const routeResult = ref<any | null>(null)
const error = ref<string | null>(null)

const isReady = computed(() => authInitialized.value)
const canQuery = computed(() => isReady.value && fromShort.value && toShort.value && fromShort.value !== toShort.value)

const { $apiFetch } = useNuxtApp()

async function loadStations() {
	loadingStations.value = true
	try {
		const items = await $apiFetch('/stations')
		// map items to { id, short, long, label }
		stations.value = (items || []).map((s: any) => ({ id: s.id, short: s.short_name || s.short, long: s.long_name || s.long, label: `${s.short_name} — ${s.long_name}` }))
	} catch (err: any) {
		// show a friendly message
		error.value = err?.message || 'Failed to load stations. Make sure you are logged in and your backend is running.'
	} finally {
		loadingStations.value = false
	}
}

async function findRoute() {
	error.value = null
	routeResult.value = null
	if (!canQuery.value) return
	finding.value = true
	try {
		const body = { fromStationId: fromShort.value, toStationId: toShort.value, analyticCode: analyticCode.value || 'WEB' }
		const res = await $apiFetch('/routes', { method: 'POST', body })
		routeResult.value = res
	} catch (err: any) {
		// display clear messages for auth or validation errors
		if (err?.status === 401) {
			error.value = 'Unauthorized. Please login.'
		} else if (err?.data?.message) {
			error.value = err.data.message
		} else {
			error.value = err?.message || 'Failed to calculate route.'
		}
	} finally {
		finding.value = false
	}
}

function reset() {
	fromShort.value = null
	toShort.value = null
	analyticCode.value = 'WEB'
	routeResult.value = null
	error.value = null
}

// if the user logs in after the page mounted, reload stations
watch(isLogged, (v) => { if (v) loadStations() })
</script>

<style scoped>
.text-right { text-align: right; }
</style>