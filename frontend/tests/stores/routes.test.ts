import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useRoutesStore } from '~/stores/routes'
import { mockApiFetch } from '../setup'

describe('Routes Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  const mockRoute = {
    id: 123,
    distanceKm: 5.432,
    path: ['MX', 'GLP', 'CGE'],
    fromStationId: 'MX',
    toStationId: 'CGE'
  }

  describe('Initial State', () => {
    it('should have correct initial state', () => {
      const routes = useRoutesStore()
      
      expect(routes.currentRoute).toBeNull()
      expect(routes.routeHistory).toEqual([])
      expect(routes.loading).toBe(false)
      expect(routes.error).toBeNull()
    })
  })

  describe('calculateRoute', () => {
    it('should calculate route successfully', async () => {
      const routes = useRoutesStore()
      mockApiFetch.mockResolvedValue(mockRoute)

      await routes.calculateRoute('MX', 'CGE', 'WEB')

      expect(mockApiFetch).toHaveBeenCalledWith('/routes', {
        method: 'POST',
        body: { fromStationId: 'MX', toStationId: 'CGE', analyticCode: 'WEB' }
      })
      expect(routes.currentRoute).toEqual(mockRoute)
      expect(routes.routeHistory).toHaveLength(1)
      expect(routes.routeHistory[0]).toEqual(mockRoute)
      expect(routes.loading).toBe(false)
      expect(routes.error).toBeNull()
    })

    it('should handle API error with message', async () => {
      const routes = useRoutesStore()
      const errorMessage = 'Route not found'
      
      mockApiFetch.mockRejectedValue({ data: { message: errorMessage } })

      await expect(routes.calculateRoute('MX', 'INVALID', 'WEB')).rejects.toThrow()

      expect(routes.currentRoute).toBeNull()
      expect(routes.error).toBe(errorMessage)
      expect(routes.loading).toBe(false)
    })

    it('should handle 401 error', async () => {
      const routes = useRoutesStore()
      
      mockApiFetch.mockRejectedValue({ status: 401 })

      await expect(routes.calculateRoute('MX', 'CGE', 'WEB')).rejects.toThrow()

      expect(routes.error).toBe('Unauthorized. Please login.')
    })

    it('should handle unknown error', async () => {
      const routes = useRoutesStore()
      
      mockApiFetch.mockRejectedValue(new Error('Network error'))

      await expect(routes.calculateRoute('MX', 'CGE', 'WEB')).rejects.toThrow()

      expect(routes.error).toBe('Network error')
    })

    it('should maintain history with max 10 items', async () => {
      const routes = useRoutesStore()
      
      // Add 11 routes
      for (let i = 0; i < 11; i++) {
        const route = { ...mockRoute, id: i + 1 }
        mockApiFetch.mockResolvedValue(route)
        await routes.calculateRoute('MX', 'CGE', 'WEB')
      }

      expect(routes.routeHistory).toHaveLength(10)
      expect(routes.routeHistory[0].id).toBe(11) // Most recent first
      expect(routes.routeHistory[9].id).toBe(2) // Oldest kept
    })
  })

  describe('clearCurrentRoute', () => {
    it('should clear current route and error', () => {
      const routes = useRoutesStore()
      
      routes.currentRoute = mockRoute
      routes.error = 'Some error'

      routes.clearCurrentRoute()

      expect(routes.currentRoute).toBeNull()
      expect(routes.error).toBeNull()
    })
  })

  describe('clearHistory', () => {
    it('should clear route history', async () => {
      const routes = useRoutesStore()
      
      mockApiFetch.mockResolvedValue(mockRoute)
      await routes.calculateRoute('MX', 'CGE', 'WEB')
      
      expect(routes.routeHistory).toHaveLength(1)

      routes.clearHistory()

      expect(routes.routeHistory).toEqual([])
    })
  })
})
