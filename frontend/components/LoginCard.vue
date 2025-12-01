<template>
  <v-card :width="width" :elevation="elevation">
    <v-card-title>{{ title }}</v-card-title>
    <v-card-text>
      <v-text-field
        v-model="emailModel"
        :label="emailLabel"
        :error-messages="emailError"
        type="email"
        variant="outlined"
        class="mb-3"
      />
      <v-text-field
        v-model="passwordModel"
        :label="passwordLabel"
        :error-messages="passwordError"
        type="password"
        variant="outlined"
      />
    </v-card-text>
    <v-card-actions class="px-4 pb-4">
      <v-spacer />
      <VButton
        :label="buttonText"
        :loading="loading"
        :disabled="!isValid"
        color="primary"
        @click="handleSubmit"
      />
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import VButton from './VButton.vue'

/**
 * LoginCard - A reusable login form component
 * 
 * Features:
 * - Email and password inputs with validation
 * - Customizable labels and button text
 * - Loading state support
 * - Error message display
 */

interface Props {
  /** Card title text */
  title?: string
  /** Email input label */
  emailLabel?: string
  /** Password input label */
  passwordLabel?: string
  /** Submit button text */
  buttonText?: string
  /** Show loading state */
  loading?: boolean
  /** Card width */
  width?: string | number
  /** Card elevation (shadow depth) */
  elevation?: number
  /** Email validation error message */
  emailError?: string
  /** Password validation error message */
  passwordError?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Login',
  emailLabel: 'Email',
  passwordLabel: 'Password',
  buttonText: 'Sign In',
  loading: false,
  width: 400,
  elevation: 2,
  emailError: '',
  passwordError: '',
})

interface Emits {
  /** Emitted when form is submitted with valid data */
  (e: 'submit', payload: { email: string; password: string }): void
}

const emit = defineEmits<Emits>()

const emailModel = ref('')
const passwordModel = ref('')

const isValid = computed(() => {
  return emailModel.value.length > 0 && passwordModel.value.length > 0
})

const handleSubmit = () => {
  if (isValid.value && !props.loading) {
    emit('submit', {
      email: emailModel.value,
      password: passwordModel.value,
    })
  }
}
</script>
