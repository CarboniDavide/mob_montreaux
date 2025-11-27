import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  const auth = useAuthStore()
  auth.init()

  // If page requires auth (set with definePageMeta({ requiresAuth: true })) and user not logged in → redirect to /login
  if (to.meta?.requiresAuth && !auth.isLogged) {
    return navigateTo('/login')
  }
})
