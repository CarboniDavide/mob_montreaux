import { vi } from 'vitest'

// Mock localStorage
const localStorageMock = (() => {
  let store: Record<string, string> = {}

  return {
    getItem: (key: string) => store[key] || null,
    setItem: (key: string, value: string) => {
      store[key] = value
    },
    removeItem: (key: string) => {
      delete store[key]
    },
    clear: () => {
      store = {}
    }
  }
})()

global.localStorage = localStorageMock as Storage

// Mock $apiFetch
const mockApiFetch = vi.fn()

// Mock useNuxtApp
global.useNuxtApp = vi.fn(() => ({
  $apiFetch: mockApiFetch
}))

// Mock useRuntimeConfig
global.useRuntimeConfig = vi.fn(() => ({
  public: {
    apiBase: 'http://localhost:8000/api/v1'
  }
}))

// Mock $fetch
global.$fetch = vi.fn()

// Mock process.client
global.process = {
  ...global.process,
  client: true
}

// Export mockApiFetch for use in tests
export { mockApiFetch }

