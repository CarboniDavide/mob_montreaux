import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useStationsStore } from '~/stores/stations'
import { mockApiFetch } from '../setup'

describe('Stations Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  const mockStations = [
    { id: 1, short_name: 'MX', long_name: 'Montreux' },
    { id: 2, short_name: 'CGE', long_name: 'Caux-Glion-Est' },
    { id: 3, short_name: 'VVY', long_name: 'Vevey' }
  ]

  describe('Initial State', () => {
    it('should have correct initial state', () => {
      const stations = useStationsStore()
      
      expect(stations.stations).toEqual([])
      expect(stations.loading).toBe(false)
      expect(stations.error).toBeNull()
    })

    it('should compute isLoaded correctly', () => {
      const stations = useStationsStore()
      
      expect(stations.isLoaded).toBe(false)
      
      stations.stations = [{ id: 1, short: 'MX', long: 'Montreux', label: 'MX — Montreux' }]
      expect(stations.isLoaded).toBe(true)
    })
  })

  describe('fetchStations', () => {
    it('should fetch stations successfully', async () => {
      const stations = useStationsStore()
      mockApiFetch.mockResolvedValue(mockStations)

      await stations.fetchStations()

      expect(mockApiFetch).toHaveBeenCalledWith('/stations')
      expect(stations.stations).toHaveLength(3)
      expect(stations.stations[0]).toEqual({
        id: 1,
        short: 'MX',
        long: 'Montreux',
        label: 'MX — Montreux'
      })
      expect(stations.loading).toBe(false)
      expect(stations.error).toBeNull()
    })

    it('should not fetch if already loaded', async () => {
      const stations = useStationsStore()
      stations.stations = [{ id: 1, short: 'MX', long: 'Montreux', label: 'MX — Montreux' }]

      await stations.fetchStations()

      expect(mockApiFetch).not.toHaveBeenCalled()
    })

    it('should not fetch if already loading', async () => {
      const stations = useStationsStore()
      stations.loading = true

      await stations.fetchStations()

      expect(mockApiFetch).not.toHaveBeenCalled()
    })

    it('should handle fetch error', async () => {
      const stations = useStationsStore()
      const error = new Error('Failed to fetch stations')
      
      mockApiFetch.mockRejectedValue(error)

      await expect(stations.fetchStations()).rejects.toThrow()

      expect(stations.error).toBe('Failed to fetch stations')
      expect(stations.stations).toEqual([])
      expect(stations.loading).toBe(false)
    })

    it('should handle unknown error format', async () => {
      const stations = useStationsStore()
      
      mockApiFetch.mockRejectedValue({})

      await expect(stations.fetchStations()).rejects.toThrow()

      expect(stations.error).toBe('Failed to load stations')
      expect(stations.loading).toBe(false)
    })
  })

  describe('reset', () => {
    it('should reset store to initial state', () => {
      const stations = useStationsStore()
      
      // Set up some state
      stations.stations = [{ id: 1, short: 'MX', long: 'Montreux', label: 'MX — Montreux' }]
      stations.error = 'Some error'

      stations.reset()

      expect(stations.stations).toEqual([])
      expect(stations.error).toBeNull()
    })
  })
})
