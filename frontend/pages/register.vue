<template>
  <v-container class="py-8">
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card class="pa-6" elevation="4">
          <v-card-title class="text-h6">Create account</v-card-title>

          <v-card-text>
            <v-form v-model="valid">
              <v-text-field v-model="name" label="Name" required />
              <v-text-field v-model="email" label="Email" type="email" required />
              <v-text-field v-model="password" label="Password" type="password" required />
              <v-text-field v-model="password_confirmation" label="Confirm password" type="password" required />

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
      try {
        await auth.register(name.value, email.value, password.value, password_confirmation.value)
        await router.push('/')
      } catch (err: any) {
        error.value = err?.message || 'Registration failed'
      }
    }

    return { name, email, password, password_confirmation, doRegister, valid, error }
  }
})
</script>

<style scoped></style>
