import { defineStore } from 'pinia'

export interface Station {
  id: number
  short: string
  long: string
  label: string
}

export const useStationsStore = defineStore('stations', {
  state: () => ({
    stations: [] as Station[],
    loading: false,
    error: null as string | null,
  }),
  
  getters: {
    isLoaded: (state) => state.stations.length > 0,
  },
  
  actions: {
    async fetchStations() {
      // Early return if already loaded or loading
      if (this.isLoaded || this.loading) {
        return this.stations
      }

      this.loading = true
      this.error = null

      try {
        const { $apiFetch } = useNuxtApp()
        const items = await $apiFetch('/stations')
        
        this.stations = (items || []).map((s: any) => ({
          id: s.id,
          short: s.short_name || s.short,
          long: s.long_name || s.long,
          label: `${s.short_name} — ${s.long_name}`
        }))
        
        return this.stations
      } catch (err: any) {
        this.error = err?.message || 'Failed to load stations'
        throw err
      } finally {
        this.loading = false
      }
    },

    reset() {
      this.stations = []
      this.error = null
    }
  }
})
