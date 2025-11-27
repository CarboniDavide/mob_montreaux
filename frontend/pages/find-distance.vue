<template>
	<v-container class="py-10">
		<v-row justify="center">
			<v-col cols="12" md="8">
				<v-card elevation="3" class="pa-6">
					<v-card-title class="text-h6">Find shortest distance between stations</v-card-title>

					<v-card-text>
						<div class="mb-4">
							<p class="text-subtitle-1">Pick two stations and compute the shortest path (Dijkstra) using the backend route calculator.</p>
						</div>

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
								/>
							</v-col>

							<v-col cols="12" md="6">
								<v-autocomplete
									v-model="toShort"
									:items="stations"
									item-title="label"
									item-value="short"
									label="To station"
									:loading="loadingStations"
									:disabled="!isReady"
									clearable
								/>
							</v-col>
						</v-row>

						<v-row class="mt-4" align="center">
							<v-col cols="12" md="6">
								<v-text-field v-model="analyticCode" label="Analytic code (optional)" placeholder="e.g. WEB-UI" />
							</v-col>

							<v-col cols="12" md="6" class="text-right">
								<v-btn color="primary" @click="findRoute" :loading="finding" :disabled="!canQuery">Find shortest route</v-btn>
								<v-btn variant="text" @click="reset" class="ml-3">Reset</v-btn>
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

						<div v-if="routeResult" class="mt-4">
							<v-card outlined class="pa-4">
								<div class="d-flex justify-space-between align-center mb-3">
									<div>
										<div class="text-subtitle-1">Distance</div>
										<div class="text-h5">{{ routeResult.distanceKm.toFixed(3) }} km</div>
									</div>
									<div class="text-right">
										<div class="text-subtitle-1">ID</div>
										<div class="text-caption">{{ routeResult.id }}</div>
									</div>
								</div>

								<v-divider class="my-3" />

								<div>
									<div class="text-subtitle-2 mb-2">Path (ordered stations)</div>
									<v-chip-group>
										<v-chip v-for="(s, i) in routeResult.path" :key="i" class="ma-1" color="primary" variant="tonal">{{ s }}</v-chip>
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
auth.init()

const isLogged = computed(() => auth.isLogged)

const stations = ref<Array<{ id: number; short: string; long: string; label: string }>>([])
const loadingStations = ref(false)

const fromShort = ref<string | null>('MX')
const toShort = ref<string | null>('CGE')
const analyticCode = ref<string>('WEB')

const finding = ref(false)
const routeResult = ref<any | null>(null)
const error = ref<string | null>(null)

const isReady = computed(() => isLogged.value)
const canQuery = computed(() => isReady.value && fromShort.value && toShort.value && fromShort.value !== toShort.value)

const { $apiFetch } = useNuxtApp()

async function loadStations() {
	if (!isLogged.value) return
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

onMounted(() => loadStations())
// if the user logs in after the page mounted, reload stations
watch(isLogged, (v) => { if (v) loadStations() })
</script>

<style scoped>
.text-right { text-align: right; }
</style>