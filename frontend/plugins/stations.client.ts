import { useStationsStore } from '~/stores/stations'

export default defineNuxtPlugin(async () => {
  const stationsStore = useStationsStore()
  
  // Pre-fetch stations when the app loads (client-side only)
  if (!stationsStore.isLoaded) {
    try {
      await stationsStore.fetchStations()
    } catch (error) {
      // Silent fail - component can handle errors when needed
      console.warn('Failed to pre-fetch stations:', error)
    }
  }
})
  