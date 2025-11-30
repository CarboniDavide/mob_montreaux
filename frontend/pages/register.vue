<template>
  <v-container class="py-8">
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card class="pa-6" elevation="4">
          <div class="text-center mb-4">
            <v-icon size="48" color="primary" class="mb-3">mdi-account-plus</v-icon>
            <v-card-title class="text-h5 font-weight-bold">Create account</v-card-title>
          </div>

          <v-card-text>
            <v-form v-model="valid">
              <v-text-field 
                v-model="name" 
                label="Name" 
                prepend-inner-icon="mdi-account"
                required 
              />
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
              <v-text-field 
                v-model="password_confirmation" 
                label="Confirm password" 
                type="password" 
                prepend-inner-icon="mdi-lock-check"
                required 
              />

              <v-row class="mt-4">
                <v-col cols="12" class="text-right">
                  <v-btn color="primary" @click="doRegister" :disabled="!valid">Register</v-btn>
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
  name: 'RegisterPage',
  setup() {
    const auth = useAuthStore()
    auth.init()

    const name = ref('')
    const email = ref('')
    const password = ref('')
    const password_confirmation = ref('')
    const valid = ref(false)
    const error = ref<string | null>(null)
    const router = useRouter()

    async function doRegister() {
      error.value = null
      
      // Validate fields before sending
      if (!name.value || !email.value || !password.value || !password_confirmation.value) {
        error.value = 'Please fill in all fields'
        return
      }
      
      if (password.value !== password_confirmation.value) {
        error.value = 'Passwords do not match'
        return
      }
      
      try {
        await auth.register(name.value, email.value, password.value, password_confirmation.value)
        await router.push('/')
      } catch (err: any) {
        // Parse different error types
        if (err?.status === 422) {
          // Validation error from server
          if (err?.data?.errors) {
            // Laravel validation errors
            const errors = err.data.errors
            const firstError = Object.values(errors)[0]
            error.value = Array.isArray(firstError) ? firstError[0] : 'Invalid input'
          } else if (err?.data?.message) {
            error.value = err.data.message
          } else {
            error.value = 'Please check your information and try again'
          }
        } else if (err?.data?.message) {
          error.value = err.data.message
        } else if (err?.message) {
          error.value = err.message
        } else {
          error.value = 'Registration failed. Please try again'
        }
      }
    }

    return { name, email, password, password_confirmation, doRegister, valid, error }
  }
})
</script>

<style scoped></style>
