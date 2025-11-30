import { defineStore } from 'pinia'

export interface User { id?: number; name?: string; email?: string }

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null as string | null,
    user: null as User | null,
    initialized: false,
  }),
  getters: {
    isLogged: (state) => !!state.token,
  },
  actions: {
    async init() {
      if (this.initialized) return
      
      if (process.client) {
        const token = localStorage.getItem('api_token')
        if (token) {
          this.token = token
          try {
            await this.me()
          } catch (error) {
            // Token invalid, clear it
            this.token = null
            this.user = null
            localStorage.removeItem('api_token')
          }
        }
        this.initialized = true
      }
    },

    async login(email: string, password: string) {
      const config = useRuntimeConfig()
      const base = config.public.apiBase

      const body = { email, password }
      const res = await $fetch(`${base}/auth/login`, { method: 'POST', body })

      if (res && res.access_token) {
        this.token = res.access_token
        if (process.client) localStorage.setItem('api_token', this.token)
        await this.me()
      }

      return res
    },

    async register(name: string, email: string, password: string, password_confirmation: string) {
      const config = useRuntimeConfig()
      const base = config.public.apiBase

      const body = { name, email, password, password_confirmation }
      const res = await $fetch(`${base}/auth/register`, { method: 'POST', body })

      if (res && res.access_token) {
        this.token = res.access_token
        if (process.client) localStorage.setItem('api_token', this.token)
        await this.me()
      }

      return res
    },

    async me() {
      if (!this.token) return null
      const config = useRuntimeConfig()
      const base = config.public.apiBase

      const res = await $fetch(`${base}/auth/me`, {
        headers: { Authorization: `Bearer ${this.token}` },
      })

      this.user = res
      return res
    },

    async logout() {
      if (!this.token) return
      const config = useRuntimeConfig()
      const base = config.public.apiBase

      await $fetch(`${base}/auth/logout`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${this.token}` },
      })

      this.token = null
      this.user = null
      if (process.client) localStorage.removeItem('api_token')
    }
  }
})
