<template>
  <v-btn
    :color="color"
    :variant="variant"
    :size="size"
    :disabled="disabled"
    :loading="loading"
    :icon="icon"
    :block="block"
    :rounded="rounded"
    @click="handleClick"
  >
    <slot>{{ label }}</slot>
  </v-btn>
</template>

<script setup lang="ts">
/**
 * VButton - A wrapper component for Vuetify's v-btn
 * 
 * This component provides a typed interface for the Vuetify button
 * and enables Storybook autodocs generation.
 */

interface Props {
  /** The color theme of the button */
  color?: 'primary' | 'secondary' | 'success' | 'error' | 'warning' | 'info'
  /** The visual style variant */
  variant?: 'elevated' | 'flat' | 'tonal' | 'outlined' | 'text' | 'plain'
  /** The size of the button */
  size?: 'x-small' | 'small' | 'default' | 'large' | 'x-large'
  /** Disable button interaction */
  disabled?: boolean
  /** Show loading spinner */
  loading?: boolean
  /** Display as icon button (no text) */
  icon?: boolean
  /** Make button full width */
  block?: boolean
  /** Border radius style */
  rounded?: boolean | 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'pill' | 'circle'
  /** Button text label (alternative to default slot) */
  label?: string
}

const props = withDefaults(defineProps<Props>(), {
  color: 'primary',
  variant: 'elevated',
  size: 'default',
  disabled: false,
  loading: false,
  icon: false,
  block: false,
  rounded: false,
  label: '',
})

const emit = defineEmits<{
  /** Emitted when button is clicked */
  click: [event: MouseEvent]
}>()

const handleClick = (event: MouseEvent) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}
</script>
