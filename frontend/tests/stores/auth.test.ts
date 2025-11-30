import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '~/stores/auth'

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
    localStorage.clear()
  })

  describe('Initial State', () => {
    it('should have correct initial state', () => {
      const auth = useAuthStore()
      
      expect(auth.token).toBeNull()
      expect(auth.user).toBeNull()
      expect(auth.initialized).toBe(false)
    })

    it('should compute isLogged correctly', () => {
      const auth = useAuthStore()
      
      expect(auth.isLogged).toBe(false)
      
      auth.token = 'test-token'
      expect(auth.isLogged).toBe(true)
    })
  })

  describe('Login', () => {
    it('should login successfully', async () => {
      const auth = useAuthStore()
      const mockResponse = {
        access_token: 'test-token-123',
      }
      const mockUser = { id: 1, name: 'Test User', email: 'test@example.com' }
      
      // Mock login and me endpoints
      vi.mocked($fetch)
        .mockResolvedValueOnce(mockResponse)
        .mockResolvedValueOnce(mockUser)

      await auth.login('test@example.com', 'password123')

      expect($fetch).toHaveBeenNthCalledWith(1, 'http://localhost:8000/api/v1/auth/login', {
        method: 'POST',
        body: { email: 'test@example.com', password: 'password123' }
      })
      expect(auth.token).toBe('test-token-123')
      expect(auth.user).toEqual(mockUser)
      expect(localStorage.getItem('api_token')).toBe('test-token-123')
    })

    it('should handle login error', async () => {
      const auth = useAuthStore()
      
      vi.mocked($fetch).mockRejectedValue(new Error('Invalid credentials'))

      await expect(auth.login('wrong@example.com', 'wrongpassword')).rejects.toThrow()

      expect(auth.token).toBeNull()
      expect(auth.user).toBeNull()
      expect(localStorage.getItem('api_token')).toBeNull()
    })
  })

  describe('Register', () => {
    it('should register successfully', async () => {
      const auth = useAuthStore()
      const mockResponse = {
        access_token: 'new-token-456',
      }
      const mockUser = { id: 2, name: 'New User', email: 'new@example.com' }
      
      vi.mocked($fetch)
        .mockResolvedValueOnce(mockResponse)
        .mockResolvedValueOnce(mockUser)

      await auth.register('New User', 'new@example.com', 'password123', 'password123')

      expect($fetch).toHaveBeenNthCalledWith(1, 'http://localhost:8000/api/v1/auth/register', {
        method: 'POST',
        body: { name: 'New User', email: 'new@example.com', password: 'password123', password_confirmation: 'password123' }
      })
      expect(auth.token).toBe('new-token-456')
      expect(auth.user).toEqual(mockUser)
      expect(localStorage.getItem('api_token')).toBe('new-token-456')
    })

    it('should handle registration error', async () => {
      const auth = useAuthStore()
      
      vi.mocked($fetch).mockRejectedValue(new Error('Email already exists'))

      await expect(auth.register('Test', 'exists@example.com', 'password', 'password')).rejects.toThrow()

      expect(auth.token).toBeNull()
    })
  })

  describe('Logout', () => {
    it('should logout and clear state', async () => {
      const auth = useAuthStore()
      
      // Set up logged in state
      auth.token = 'test-token'
      auth.user = { id: 1, name: 'Test', email: 'test@example.com' }
      localStorage.setItem('api_token', 'test-token')

      vi.mocked($fetch).mockResolvedValue({})

      await auth.logout()

      expect($fetch).toHaveBeenCalledWith('http://localhost:8000/api/v1/auth/logout', {
        method: 'POST',
        headers: { Authorization: 'Bearer test-token' }
      })
      expect(auth.token).toBeNull()
      expect(auth.user).toBeNull()
      expect(localStorage.getItem('api_token')).toBeNull()
    })
  })

  describe('Init', () => {
    it('should initialize with stored token', async () => {
      const auth = useAuthStore()
      const storedToken = 'stored-token-789'
      const mockUser = { id: 3, name: 'Stored User', email: 'stored@example.com' }
      
      localStorage.setItem('api_token', storedToken)
      vi.mocked($fetch).mockResolvedValue(mockUser)

      await auth.init()

      expect(auth.token).toBe(storedToken)
      expect($fetch).toHaveBeenCalledWith('http://localhost:8000/api/v1/auth/me', {
        headers: { Authorization: `Bearer ${storedToken}` }
      })
      expect(auth.user).toEqual(mockUser)
      expect(auth.initialized).toBe(true)
    })

    it('should handle init with no stored token', async () => {
      const auth = useAuthStore()

      await auth.init()

      expect(auth.token).toBeNull()
      expect(auth.user).toBeNull()
      expect(auth.initialized).toBe(true)
      expect($fetch).not.toHaveBeenCalled()
    })

    it('should handle init with invalid token', async () => {
      const auth = useAuthStore()
      localStorage.setItem('api_token', 'invalid-token')
      
      vi.mocked($fetch).mockRejectedValue(new Error('Unauthorized'))

      await auth.init()

      expect(auth.token).toBeNull()
      expect(auth.user).toBeNull()
      expect(localStorage.getItem('api_token')).toBeNull()
      expect(auth.initialized).toBe(true)
    })
  })
})
