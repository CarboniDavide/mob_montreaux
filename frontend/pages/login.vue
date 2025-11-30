<template>
  <v-container class="py-8">
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card class="pa-6" elevation="4">
          <div class="text-center mb-4">
            <v-icon size="48" color="primary" class="mb-3">mdi-login</v-icon>
            <v-card-title class="text-h5 font-weight-bold">Sign in</v-card-title>
          </div>

          <v-card-text>
            <v-form ref="formRef" v-model="valid">
              <v-text-field 
                v-model="email" 
                label="Email" 
                type="email" 
                prepend-inner-icon="mdi-email"
                required 
              />
              <v-text-field 
                v-model="password" 
                label="Password" 
                type="password" 
                prepend-inner-icon="mdi-lock"
                required 
              />

              <v-row class="mt-4">
                <v-col cols="6">
                  <v-btn color="primary" @click="doLogin" :disabled="!valid" block prepend-icon="mdi-login-variant">Login</v-btn>
                </v-col>
                <v-col cols="6">
                  <v-btn color="primary" variant="outlined" to="/register" block prepend-icon="mdi-account-plus">Create account</v-btn>
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
      
      // Validate fields before sending
      if (!email.value || !password.value) {
        error.value = 'Please enter your email and password'
        return
      }
      
      try {
        await auth.login(email.value, password.value)
        // redirect to home or returnPath
        await router.push('/')
      } catch (err: any) {
        // Parse different error types
        if (err?.status === 422) {
          error.value = 'Invalid email or password format'
        } else if (err?.status === 401) {
          error.value = 'Incorrect email or password'
        } else if (err?.data?.message) {
          error.value = err.data.message
        } else if (err?.message) {
          error.value = err.message
        } else {
          error.value = 'Login failed. Please try again'
        }
      }
    }

    return { email, password, doLogin, valid, error }
  }
})
</script>

<style scoped></style>
