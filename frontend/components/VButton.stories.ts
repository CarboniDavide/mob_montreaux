import type { Meta, StoryObj } from '@storybook/vue3'
import VButton from './VButton.vue'

const meta: Meta<typeof VButton> = {
  title: 'Components/VButton',
  component: VButton,
  tags: ['autodocs'],
  argTypes: {
    color: {
      control: 'select',
      options: ['primary', 'secondary', 'success', 'error', 'warning', 'info'],
    },
    variant: {
      control: 'select',
      options: ['elevated', 'flat', 'tonal', 'outlined', 'text', 'plain'],
    },
    size: {
      control: 'select',
      options: ['x-small', 'small', 'default', 'large', 'x-large'],
    },
    disabled: {
      control: 'boolean',
    },
    loading: {
      control: 'boolean',
    },
    icon: {
      control: 'boolean',
    },
    block: {
      control: 'boolean',
    },
    rounded: {
      control: 'select',
      options: [false, true, 'xs', 'sm', 'md', 'lg', 'xl', 'pill', 'circle'],
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

export const Secondary: Story = {
  args: {
    label: 'Secondary Button',
    color: 'secondary',
  },
}

export const Success: Story = {
  args: {
    label: 'Success Button',
    color: 'success',
  },
}

export const Error: Story = {
  args: {
    label: 'Error Button',
    color: 'error',
  },
}

export const Outlined: Story = {
  args: {
    label: 'Outlined Button',
    variant: 'outlined',
    color: 'primary',
  },
}

export const Tonal: Story = {
  args: {
    label: 'Tonal Button',
    variant: 'tonal',
    color: 'primary',
  },
}

export const Text: Story = {
  args: {
    label: 'Text Button',
    variant: 'text',
    color: 'primary',
  },
}

export const Loading: Story = {
  args: {
    label: 'Loading...',
    loading: true,
    color: 'primary',
  },
}

export const Disabled: Story = {
  args: {
    label: 'Disabled Button',
    disabled: true,
    color: 'primary',
  },
}

export const Small: Story = {
  args: {
    label: 'Small Button',
    size: 'small',
    color: 'primary',
  },
}

export const Large: Story = {
  args: {
    label: 'Large Button',
    size: 'large',
    color: 'primary',
  },
}

export const Block: Story = {
  args: {
    label: 'Block Button',
    block: true,
    color: 'primary',
  },
}

export const Rounded: Story = {
  args: {
    label: 'Rounded Button',
    rounded: 'pill',
    color: 'primary',
  },
}

export const WithSlot: Story = {
  render: (args) => ({
    components: { VButton },
    setup() {
      return { args }
    },
    template: `
      <VButton v-bind="args">
        <v-icon start>mdi-heart</v-icon>
        With Icon
      </VButton>
    `,
  }),
  args: {
    color: 'error',
  },
}

export const AllSizes: Story = {
  render: () => ({
    components: { VButton },
    template: `
      <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <VButton size="x-small" label="X-Small" color="primary" />
        <VButton size="small" label="Small" color="primary" />
        <VButton size="default" label="Default" color="primary" />
        <VButton size="large" label="Large" color="primary" />
        <VButton size="x-large" label="X-Large" color="primary" />
      </div>
    `,
  }),
}

export const AllColors: Story = {
  render: () => ({
    components: { VButton },
    template: `
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <VButton label="Primary" color="primary" />
        <VButton label="Secondary" color="secondary" />
        <VButton label="Success" color="success" />
        <VButton label="Error" color="error" />
        <VButton label="Warning" color="warning" />
        <VButton label="Info" color="info" />
      </div>
    `,
  }),
}

export const AllVariants: Story = {
  render: () => ({
    components: { VButton },
    template: `
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <VButton label="Elevated" variant="elevated" color="primary" />
        <VButton label="Flat" variant="flat" color="primary" />
        <VButton label="Tonal" variant="tonal" color="primary" />
        <VButton label="Outlined" variant="outlined" color="primary" />
        <VButton label="Text" variant="text" color="primary" />
        <VButton label="Plain" variant="plain" color="primary" />
      </div>
    `,
  }),
}
