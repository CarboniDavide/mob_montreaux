import { defineStore } from 'pinia'

const MAX_HISTORY_SIZE = 10
const DEFAULT_ANALYTIC_CODE = 'WEB'

export interface RouteResult {
  id?: number
  distanceKm: number
  path: string[]
  fromStationId: string
  toStationId: string
  analyticCode?: string
  createdAt?: string
}

const parseError = (err: any): string => {
  if (err?.status === 401) return 'Unauthorized. Please login.'
  if (err?.data?.message) return err.data.message
  return err?.message || 'Failed to calculate route.'
}

export const useRoutesStore = defineStore('routes', {
  state: () => ({
    currentRoute: null as RouteResult | null,
    routeHistory: [] as RouteResult[],
    loading: false,
    error: null as string | null,
  }),
  
  getters: {
    hasRoute: (state) => state.currentRoute !== null,
  },
  
  actions: {
    async calculateRoute(
      fromStationId: string, 
      toStationId: string, 
      analyticCode: string = DEFAULT_ANALYTIC_CODE
    ) {
      this.loading = true
      this.error = null
      this.currentRoute = null

      try {
        const { $apiFetch } = useNuxtApp()
        const result = await $apiFetch('/routes', { 
          method: 'POST', 
          body: { fromStationId, toStationId, analyticCode }
        })
        
        this.currentRoute = result as RouteResult
        this.addToHistory(result as RouteResult)
        
        return this.currentRoute
      } catch (err: any) {
        this.error = parseError(err)
        throw err
      } finally {
        this.loading = false
      }
    },

    addToHistory(route: RouteResult) {
      this.routeHistory.unshift(route)
      if (this.routeHistory.length > MAX_HISTORY_SIZE) {
        this.routeHistory = this.routeHistory.slice(0, MAX_HISTORY_SIZE)
      }
    },

    clearCurrentRoute() {
      this.currentRoute = null
      this.error = null
    },

    clearHistory() {
      this.routeHistory = []
    }
  }
})
