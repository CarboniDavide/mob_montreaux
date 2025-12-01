# Storybook Guide

This guide covers using Storybook in the Nuxt 3 + Vuetify frontend application.

## 📋 What is Storybook?

**Storybook** is a tool for developing UI components in isolation. It allows you to:
- Build components independently from your app
- Test different component states visually
- Document components with live examples
- Share components with your team
- Test UI components interactively

## 🚀 Quick Start

### Running Storybook

```bash
# Start Storybook dev server
cd frontend
npm run storybook
```

Storybook will be available at: **http://localhost:6006**

### Build Storybook for Production

```bash
# Build static Storybook
npm run build-storybook

# Output will be in `storybook-static/` directory
```

---

## 📁 Project Structure

```
frontend/
├── .storybook/
│   ├── main.ts              # Storybook configuration
│   └── preview.ts           # Vuetify setup and global decorators
├── components/
│   ├── VButton.vue          # Custom button wrapper component
│   ├── VButton.stories.ts   # VButton stories (autodocs enabled)
│   ├── LoginCard.vue        # Login form component
│   ├── LoginCard.stories.ts # LoginCard stories (autodocs enabled)
│   └── Button.stories.ts    # Direct Vuetify button examples
├── assets/
│   └── css/                 # Global styles (NOT in components/)
└── pages/
    └── *.stories.ts         # Page stories (optional)
```

**Important:** Assets belong in `/frontend/assets/`, not inside the components folder.

---

## ✍️ Writing Stories

### Basic Story Structure

```typescript
import type { Meta, StoryObj } from '@storybook/vue3'
import VButton from './VButton.vue'

const meta: Meta<typeof VButton> = {
  title: 'Components/VButton',
  component: VButton,
  tags: ['autodocs'], // ✅ Works with proper Vue components
  argTypes: {
    label: { control: 'text' },
    color: {
      control: 'select',
      options: ['primary', 'secondary', 'success', 'error'],
    },
  },
}

export default meta
type Story = StoryObj<typeof VButton>

export const Primary: Story = {
  args: {
    label: 'Click me',
    color: 'primary',
  },
}

export const Secondary: Story = {
  args: {
    label: 'Click me',
    color: 'secondary',
  },
}
```

### Vuetify Component Story (Direct Usage)

**Note:** Direct Vuetify component stories **cannot use** `tags: ['autodocs']` as it causes errors. Remove the tag or wrap in a proper Vue component.

```typescript
import type { Meta, StoryObj } from '@storybook/vue3'

const meta: Meta = {
  title: 'Vuetify/Button',
  // ❌ NO component: 'v-btn' - causes autodocs errors
  // ❌ NO tags: ['autodocs'] - doesn't work with string components
  argTypes: {
    color: {
      control: 'select',
      options: ['primary', 'secondary', 'success', 'error'],
      description: 'The color theme of the button',
    },
    variant: {
      control: 'select',
      options: ['elevated', 'flat', 'tonal', 'outlined', 'text'],
      description: 'The visual style variant',
    },
  },
  parameters: {
    docs: {
      description: {
        component: 'Vuetify button component examples.',
      },
    },
  },
}

export default meta
type Story = StoryObj

export const Primary: Story = {
  args: {
    color: 'primary',
    children: 'Primary Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}
```

**Better Approach:** Create a wrapper component (see below).

### Wrapper Component Approach (Recommended)

For better TypeScript support and autodocs, create a wrapper component:

```vue
<!-- components/VButton.vue -->
<template>
  <v-btn
    :color="color"
    :variant="variant"
    :size="size"
    :disabled="disabled"
    :loading="loading"
    @click="handleClick"
  >
    <slot>{{ label }}</slot>
  </v-btn>
</template>

<script setup lang="ts">
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
  /** Button text label */
  label?: string
}

const props = withDefaults(defineProps<Props>(), {
  color: 'primary',
  variant: 'elevated',
  size: 'default',
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()

const handleClick = (event: MouseEvent) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}
</script>
```

```typescript
// components/VButton.stories.ts
import type { Meta, StoryObj } from '@storybook/vue3'
import VButton from './VButton.vue'

const meta: Meta<typeof VButton> = {
  title: 'Components/VButton',
  component: VButton,
  tags: ['autodocs'], // ✅ Works perfectly with Vue components
  argTypes: {
    color: {
      control: 'select',
      options: ['primary', 'secondary', 'success', 'error', 'warning', 'info'],
    },
    onClick: { action: 'clicked' },
  },
}

export default meta
type Story = StoryObj<typeof VButton>

export const Primary: Story = {
  args: {
    label: 'Primary Button',
    color: 'primary',
  },
}

export const Loading: Story = {
  args: {
    label: 'Loading...',
    loading: true,
  },
}
```

**Benefits:**
- ✅ Autodocs works properly
- ✅ Full TypeScript support
- ✅ JSDoc comments appear in docs
- ✅ Better prop validation
- ✅ Reusable in your app

### Custom Vue Component Story

```typescript
// components/LoginCard.vue
<template>
  <v-card>
    <v-card-title>{{ title }}</v-card-title>
    <v-card-text>
      <v-text-field v-model="email" label="Email" />
      <v-text-field v-model="password" label="Password" type="password" />
    </v-card-text>
    <v-card-actions>
      <v-btn @click="onSubmit" :loading="loading">{{ buttonText }}</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
interface Props {
  title?: string
  buttonText?: string
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Login',
  buttonText: 'Sign In',
  loading: false,
})

const email = ref('')
const password = ref('')

const emit = defineEmits<{
  submit: [{ email: string; password: string }]
}>()

const onSubmit = () => {
  emit('submit', { email: email.value, password: password.value })
}
</script>
```

```typescript
// components/LoginForm.stories.ts
import type { Meta, StoryObj } from '@storybook/vue3'
import LoginForm from './LoginForm.vue'

const meta: Meta<typeof LoginForm> = {
  title: 'Components/LoginForm',
  component: LoginForm,
  tags: ['autodocs'],
  argTypes: {
    title: { control: 'text' },
    buttonText: { control: 'text' },
    loading: { control: 'boolean' },
  },
}

export default meta
type Story = StoryObj<typeof LoginForm>

export const Default: Story = {
  args: {
    title: 'Login',
    buttonText: 'Sign In',
    loading: false,
  },
}

export const Loading: Story = {
  args: {
    title: 'Login',
    buttonText: 'Signing In...',
    loading: true,
  },
}

export const CustomText: Story = {
  args: {
    title: 'Welcome Back',
    buttonText: 'Continue',
    loading: false,
  },
}
```

---

## 🎨 Story Types

### 1. Args Stories (Recommended)

```typescript
export const Primary: Story = {
  args: {
    label: 'Button',
    color: 'primary',
  },
}
```

**Benefits:**
- Interactive controls in Storybook UI
- Easy to modify props
- Shareable via URL

### 2. Render Function Stories

```typescript
export const WithIcon: Story = {
  render: (args) => ({
    components: { MyButton },
    setup() {
      return { args }
    },
    template: `
      <MyButton v-bind="args">
        <template #icon>
          <v-icon>mdi-check</v-icon>
        </template>
        {{ args.label }}
      </MyButton>
    `,
  }),
  args: {
    label: 'With Icon',
  },
}
```

### 3. Template Stories

```typescript
export const Multiple: Story = {
  render: () => ({
    template: `
      <div style="display: flex; gap: 1rem;">
        <MyButton color="primary">Primary</MyButton>
        <MyButton color="secondary">Secondary</MyButton>
        <MyButton color="success">Success</MyButton>
      </div>
    `,
  }),
}
```

---

## 🎭 Advanced Features

### Using Actions

Track user interactions:

```typescript
import { fn } from '@storybook/test'

export const WithActions: Story = {
  args: {
    onClick: fn(),
    onHover: fn(),
  },
}
```

View actions in the "Actions" panel in Storybook.

### Using Decorators

Add wrappers to stories:

```typescript
// Single story decorator
export const Centered: Story = {
  decorators: [
    () => ({
      template: '<div style="display: flex; justify-content: center;"><story /></div>',
    }),
  ],
}

// Global decorator in .storybook/preview.ts
export const decorators = [
  (story) => ({
    components: { story },
    template: '<v-app><story /></v-app>',
  }),
]
```

### Parameters

Configure story behavior:

```typescript
export const NoBackground: Story = {
  parameters: {
    backgrounds: {
      default: 'dark',
    },
    layout: 'fullscreen',
  },
}
```

### Play Functions

Simulate user interactions:

```typescript
import { userEvent, within } from '@storybook/test'

export const FilledForm: Story = {
  play: async ({ canvasElement }) => {
    const canvas = within(canvasElement)
    
    await userEvent.type(canvas.getByLabelText('Email'), 'test@example.com')
    await userEvent.type(canvas.getByLabelText('Password'), 'password123')
    await userEvent.click(canvas.getByRole('button'))
  },
}
```

---

## 🧪 Testing with Storybook

### Visual Testing

Storybook stories can be used for visual regression testing:

```bash
# Install Chromatic (optional)
npm install --save-dev chromatic

# Run visual tests
npx chromatic --project-token=<your-token>
```

### Interaction Testing

Test user interactions in stories:

```typescript
import { expect } from '@storybook/test'

export const SubmitForm: Story = {
  play: async ({ canvasElement }) => {
    const canvas = within(canvasElement)
    
    const emailInput = canvas.getByLabelText('Email')
    await userEvent.type(emailInput, 'test@example.com')
    
    expect(emailInput).toHaveValue('test@example.com')
    
    const submitButton = canvas.getByRole('button', { name: /submit/i })
    await userEvent.click(submitButton)
  },
}
```

---

## 📚 Organizing Stories

### Naming Convention

```typescript
// ✅ Good
title: 'Components/Button'
title: 'Forms/LoginForm'
title: 'Pages/Dashboard'

// ❌ Bad
title: 'button'
title: 'MyButton'
```

### Grouping with Subgroups

```typescript
// Creates: Vuetify → Components → Button
title: 'Vuetify/Components/Button'

// Creates: Custom → Forms → Input
title: 'Custom/Forms/Input'
```

### Story Naming

```typescript
// ✅ Good
export const Primary: Story = {}
export const Loading: Story = {}
export const Disabled: Story = {}

// ❌ Bad
export const story1: Story = {}
export const test: Story = {}
```

---

## 🎨 Customizing Storybook

### Global Styles (.storybook/preview.ts)

```typescript
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import '../assets/css/main.css' // Your custom styles
```

### Themes

Add theme switcher:

```typescript
// .storybook/preview.ts
export const parameters = {
  backgrounds: {
    default: 'light',
    values: [
      { name: 'light', value: '#ffffff' },
      { name: 'dark', value: '#1e1e1e' },
    ],
  },
}
```

### Viewports

Test responsive designs:

```typescript
export const parameters = {
  viewport: {
    viewports: {
      mobile: {
        name: 'Mobile',
        styles: { width: '375px', height: '667px' },
      },
      tablet: {
        name: 'Tablet',
        styles: { width: '768px', height: '1024px' },
      },
    },
  },
}
```

---

## 🔧 Configuration Files

### .storybook/main.ts

```typescript
import type { StorybookConfig } from '@storybook/vue3-vite'
import vue from '@vitejs/plugin-vue'

const config: StorybookConfig = {
  stories: [
    '../components/**/*.mdx',
    '../components/**/*.stories.@(js|jsx|mjs|ts|tsx)',
    '../pages/**/*.stories.@(js|jsx|mjs|ts|tsx)', // Optional: page stories
  ],
  addons: [
    '@storybook/addon-links',
    '@storybook/addon-essentials',
    '@storybook/addon-interactions',
  ],
  framework: {
    name: '@storybook/vue3-vite',
    options: {},
  },
  docs: {
    autodocs: 'tag', // Enable autodocs for components with tags: ['autodocs']
  },
  async viteFinal(config) {
    // Required: Add Vue plugin to handle .vue files
    config.plugins = config.plugins || []
    config.plugins.push(vue())
    return config
  },
}

export default config
```

**Key Points:**
- Uses standard `@storybook/vue3-vite` (NOT `@nuxtjs/storybook`)
- Vue plugin is required for .vue file support
- Stories pattern includes components and optionally pages

### .storybook/preview.ts

```typescript
import type { Preview } from '@storybook/vue3'
import { setup } from '@storybook/vue3'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

const vuetify = createVuetify({
  components,
  directives,
})

setup((app) => {
  app.use(vuetify)
})

const preview: Preview = {
  parameters: {
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/i,
      },
    },
  },
}

export default preview
```

---

## 💡 Best Practices

### 1. One Component Per Story File

```bash
# ✅ Good
components/Button.vue
components/Button.stories.ts

# ❌ Bad
components/AllComponents.stories.ts
```

### 2. Cover All States

```typescript
export const Default: Story = {}
export const Loading: Story = {}
export const Disabled: Story = {}
export const Error: Story = {}
export const Empty: Story = {}
```

### 3. Use Meaningful Names

```typescript
// ✅ Good
export const PrimaryButton: Story = {}
export const LoadingState: Story = {}

// ❌ Bad
export const Story1: Story = {}
export const Test: Story = {}
```

### 4. Document Props

```typescript
const meta: Meta<typeof MyComponent> = {
  argTypes: {
    color: {
      description: 'The button color theme',
      control: 'select',
      options: ['primary', 'secondary'],
      table: {
        type: { summary: 'string' },
        defaultValue: { summary: 'primary' },
      },
    },
  },
}
```

### 5. Keep Stories Simple

```typescript
// ✅ Good - Simple and focused
export const Primary: Story = {
  args: { color: 'primary' },
}

// ❌ Bad - Too complex
export const Everything: Story = {
  render: () => ({
    template: '/* 100 lines of template */',
  }),
}
```

---

## 🐛 Troubleshooting

### Storybook Won't Start

```bash
# Clear cache and reinstall
rm -rf node_modules .storybook/storybook-cache
npm install
npm run storybook
```

### Vue Files Not Loading

Ensure `@vitejs/plugin-vue` is installed and configured in `.storybook/main.ts`:

```typescript
async viteFinal(config) {
  config.plugins = config.plugins || []
  config.plugins.push(vue())
  return config
}
```

### Vuetify Components Not Styling

Make sure Vuetify is initialized in `.storybook/preview.ts`:

```typescript
import { setup } from '@storybook/vue3'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'

const vuetify = createVuetify({})
setup((app) => app.use(vuetify))
```

### Stories Not Showing Up

Check the `stories` glob pattern in `.storybook/main.ts`:

```typescript
stories: [
  '../components/**/*.stories.@(js|jsx|mjs|ts|tsx)',
]
```

### Error: "Cannot use 'in' operator to search for '__docgenInfo' in v-btn"

**Cause:** Using `tags: ['autodocs']` with string-based Vuetify components.

**Solution 1:** Remove `tags: ['autodocs']` from stories using string components:
```typescript
const meta: Meta = {
  title: 'Vuetify/Button',
  // Remove: tags: ['autodocs'],
  // Remove: component: 'v-btn' as any,
}
```

**Solution 2 (Recommended):** Create a proper Vue wrapper component:
```vue
<!-- VButton.vue -->
<template>
  <v-btn v-bind="$props" @click="$emit('click', $event)">
    <slot>{{ label }}</slot>
  </v-btn>
</template>
```

Then use it with `tags: ['autodocs']` safely.

### Nuxt Dev Won't Start: "Could not load @nuxtjs/storybook"

**Cause:** `@nuxtjs/storybook` in `nuxt.config.ts` modules array.

**Solution:** Remove it from modules (we use standalone Storybook):
```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  modules: ['@pinia/nuxt'], // Remove '@nuxtjs/storybook'
})
```

Storybook runs independently: `npm run storybook`

---

## 📚 Additional Resources

- [Storybook Documentation](https://storybook.js.org/docs/vue/get-started/introduction)
- [Storybook for Vue 3](https://storybook.js.org/docs/vue/get-started/install)
- [Vuetify Components](https://vuetifyjs.com/en/components/all/)
- [Storybook Addons](https://storybook.js.org/addons)

---

## 🎯 Current Components in Storybook

### Available Components

✅ **VButton** (`components/VButton.vue`)
- Vuetify button wrapper with full TypeScript support
- Autodocs enabled
- 15+ story variants (Primary, Secondary, Loading, Disabled, sizes, colors, variants)
- Action tracking for click events

✅ **LoginCard** (`components/LoginCard.vue`)
- Complete login form component
- Uses VButton internally
- 8 story variants (Default, Loading, WithErrors, etc.)
- Form validation and error handling

✅ **Button Examples** (`components/Button.stories.ts`)
- Direct Vuetify v-btn usage examples
- No autodocs (to avoid errors)
- Showcase stories for sizes, colors, variants

### Components to Add Stories For

**Existing Components (from your app):**
- `pages/login.vue` - Login page
- `pages/register.vue` - Registration page  
- `pages/find-distance.vue` - Route calculator page
- Any custom components you create

**Future Components:**
- Form inputs (email, password, search)
- Cards and dialogs
- Navigation components
- Dashboard layouts

---

**Happy Storyboarding! 📚✨**
