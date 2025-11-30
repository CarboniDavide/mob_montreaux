import { useAuthStore } from '~/stores/auth'

export default defineNuxtPlugin(() => {
  const auth = useAuthStore()

  return {
    provide: {
      apiFetch: async (path: string, opts: any = {}) => {
        const config = useRuntimeConfig()
        const base = config.public.apiBase.replace(/\/$/, '')

        const headers = { ...(opts.headers || {}) }
        if (auth.token) headers['Authorization'] = `Bearer ${auth.token}`

        const url = path.startsWith('/') ? `${base}${path}` : `${base}/${path}`
        return await $fetch(url, { ...opts, headers })
      }
    }
  }
})
