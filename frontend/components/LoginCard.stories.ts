import type { Meta, StoryObj } from '@storybook/vue3'
import { fn } from '@storybook/test'
import LoginCard from './LoginCard.vue'

const meta: Meta<typeof LoginCard> = {
  title: 'Components/LoginCard',
  component: LoginCard,
  tags: ['autodocs'],
  argTypes: {
    width: {
      control: 'number',
    },
    elevation: {
      control: { type: 'range', min: 0, max: 24, step: 1 },
    },
    onSubmit: { action: 'submitted' },
  },
  args: {
    onSubmit: fn(),
  },
}

export default meta
type Story = StoryObj<typeof LoginCard>

export const Default: Story = {
  args: {
    title: 'Login',
    emailLabel: 'Email',
    passwordLabel: 'Password',
    buttonText: 'Sign In',
  },
}

export const CustomLabels: Story = {
  args: {
    title: 'Welcome Back',
    emailLabel: 'Email Address',
    passwordLabel: 'Your Password',
    buttonText: 'Continue',
  },
}

export const Loading: Story = {
  args: {
    title: 'Login',
    loading: true,
    buttonText: 'Signing In...',
  },
}

export const WithErrors: Story = {
  args: {
    title: 'Login',
    emailError: 'Invalid email format',
    passwordError: 'Password is required',
  },
}

export const WideCard: Story = {
  args: {
    title: 'Login',
    width: 600,
  },
}

export const NoElevation: Story = {
  args: {
    title: 'Login',
    elevation: 0,
  },
}

export const HighElevation: Story = {
  args: {
    title: 'Login',
    elevation: 12,
  },
}

export const InContainer: Story = {
  render: (args) => ({
    components: { LoginCard },
    setup() {
      return { args }
    },
    template: `
      <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <LoginCard v-bind="args" />
      </div>
    `,
  }),
  args: {
    title: 'Welcome Back',
  },
}
