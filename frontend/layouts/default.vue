<template>
  <v-app>
    <!-- Header -->
    <v-app-bar color="primary" dark flat>
      <v-container class="d-flex align-center justify-space-between">
        <h1 class="text-h6">MOB Website</h1>
        <div>
          <NuxtLink to="/" class="mr-4 text-white">Home</NuxtLink>
          <NuxtLink to="/find-distance" class="mr-4 text-white">Find Distance</NuxtLink>
          <NuxtLink to="/about" class="mr-4 text-white">About</NuxtLink>
          <span class="auth-separator" aria-hidden="true"></span>

          <div class="d-inline-flex align-center ml-6">
            <div v-if="initialized">
              <div v-if="auth.isLogged" class="mr-4">
                <span class="text-white mr-3">{{ auth.user?.name || auth.user?.email }}</span>
                <v-btn text class="text-white" @click="logout">Logout</v-btn>
              </div>
              <div v-else>
                <NuxtLink to="/login" class="mr-3 text-white">Login</NuxtLink>
                <NuxtLink to="/register" class="text-white">Register</NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </v-container>
    </v-app-bar>

    <!-- Main content -->
    <v-main>
      <v-container class="py-6">
        <slot />
      </v-container>
    </v-main>

    <!-- Footer -->
    <v-footer color="primary" dark app>
      <v-container class="text-center">
        © Made with <v-icon class="mx-1 text-red" size="20">mdi-heart</v-icon> by Davide Carboni
      </v-container>
    </v-footer>
  </v-app>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from '#imports'

const auth = useAuthStore()
const router = useRouter()
const initialized = ref(false)

onMounted(async () => {
  await auth.init()
  initialized.value = true
})

function logout() {
  auth.logout()
  router.push('/')
}
</script>
