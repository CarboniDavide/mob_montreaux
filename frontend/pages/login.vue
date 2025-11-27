<template>
  <v-container class="py-8">
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card class="pa-6" elevation="4">
          <v-card-title class="text-h6">Sign in</v-card-title>

          <v-card-text>
            <v-form ref="formRef" v-model="valid">
              <v-text-field v-model="email" label="Email" type="email" required />
              <v-text-field v-model="password" label="Password" type="password" required />

              <v-row class="mt-4">
                <v-col cols="6">
                  <v-btn color="primary" @click="doLogin" :disabled="!valid">Login</v-btn>
                </v-col>
                <v-col cols="6" class="text-right">
                  <NuxtLink to="/register">Create account</NuxtLink>
                </v-col>
              </v-row>

              <v-alert v-if="error" type="error" class="mt-4">{{ error }}</v-alert>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { useRouter } from '#imports'
import { useAuthStore } from '~/stores/auth'

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const auth = useAuthStore()
    auth.init()

    const email = ref('')
    const password = ref('')
    const valid = ref(false)
    const error = ref<string | null>(null)
    const router = useRouter()

    async function doLogin() {
      error.value = null
      try {
        await auth.login(email.value, password.value)
        // redirect to home or returnPath
        await router.push('/')
      } catch (err: any) {
        error.value = err?.message || 'Login failed'
      }
    }

    return { email, password, doLogin, valid, error }
  }
})
</script>

<style scoped></style>
