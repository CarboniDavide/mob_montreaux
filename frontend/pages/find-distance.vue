<template>
	<v-container class="py-10">
		<v-row justify="center">
			<v-col cols="12" md="8">
				<v-card elevation="3" class="pa-6">
					<v-card-title class="text-h6">Find distance — Montreux</v-card-title>

					<v-card-text>
						<p class="mb-6 text-subtitle-1">
							Calculate the straight-line distance (great-circle) between two coordinates.
							Enter latitude/longitude for the two points and click "Calculate".
						</p>

						<v-row>
							<v-col cols="12" md="6">
								<v-card outlined class="pa-4">
									<div class="text-subtitle-2 mb-3">Point A</div>
									<v-text-field v-model.number="aLat" label="Latitude (°)" type="number" :rules="latRules" />
									<v-text-field v-model.number="aLon" label="Longitude (°)" type="number" :rules="lonRules" />
								</v-card>
							</v-col>

							<v-col cols="12" md="6">
								<v-card outlined class="pa-4">
									<div class="text-subtitle-2 mb-3">Point B</div>
									<v-text-field v-model.number="bLat" label="Latitude (°)" type="number" :rules="latRules" />
									<v-text-field v-model.number="bLon" label="Longitude (°)" type="number" :rules="lonRules" />
								</v-card>
							</v-col>
						</v-row>

						<v-row class="mt-4" align="center">
							<v-col cols="12" md="6">
								<v-btn color="primary" @click="calculate" :disabled="!validInputs">Calculate</v-btn>
								<v-btn variant="text" @click="reset" class="ml-3">Reset</v-btn>
							</v-col>

							<v-col cols="12" md="6" class="text-right">
								<div v-if="distanceKm !== null" class="text-h6">Distance: {{ distanceKm.toFixed(3) }} km</div>
								<div v-else class="text-caption grey--text">Result will appear here</div>
							</v-col>
						</v-row>

						<v-divider class="my-6" />

						<div class="text-body-1">
							<strong>Notes</strong>
							<ul>
								<li>This is the great-circle (Haversine) distance — shortest route over the earth’s surface.</li>
								<li>Coordinates should be in decimal degrees (for example Montreux Station: 46.4311, 6.9094).</li>
							</ul>
						</div>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
	name: 'FindDistancePage',
	data() {
		return {
			// defaults are helpful — Montreux station coordinates used as one example
			aLat: 46.4311 as number | null,
			aLon: 6.9094 as number | null,
			bLat: 46.5200 as number | null,
			bLon: 6.6300 as number | null,
			distanceKm: null as number | null,
		}
	},
	computed: {
		latRules(): ((v: number|null) => boolean|string)[] {
			return [
				(v: number|null) => v !== null && !Number.isNaN(v) || 'Required',
				(v: number|null) => (v === null || (v >= -90 && v <= 90)) || 'Latitude must be between -90 and 90',
			]
		},
		lonRules(): ((v: number|null) => boolean|string)[] {
			return [
				(v: number|null) => v !== null && !Number.isNaN(v) || 'Required',
				(v: number|null) => (v === null || (v >= -180 && v <= 180)) || 'Longitude must be between -180 and 180',
			]
		},
		validInputs(): boolean {
			return [this.aLat, this.aLon, this.bLat, this.bLon].every(v => v !== null && !Number.isNaN(v))
		}
	},
	methods: {
		calculate(): void {
			if (!this.validInputs) {
				this.distanceKm = null
				return
			}
			const toRad = (d: number) => (d * Math.PI) / 180

			const R = 6371 // Earth radius in km
			const lat1 = toRad(this.aLat as number)
			const lon1 = toRad(this.aLon as number)
			const lat2 = toRad(this.bLat as number)
			const lon2 = toRad(this.bLon as number)

			const dLat = lat2 - lat1
			const dLon = lon2 - lon1

			const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
								Math.cos(lat1) * Math.cos(lat2) *
								Math.sin(dLon / 2) * Math.sin(dLon / 2)

			const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
			this.distanceKm = R * c
		},
		reset(): void {
			this.aLat = 46.4311
			this.aLon = 6.9094
			this.bLat = 46.5200
			this.bLon = 6.6300
			this.distanceKm = null
		}
	}
})
</script>

<style scoped>
.text-right { text-align: right; }
</style>