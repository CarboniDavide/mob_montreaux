import type { Meta, StoryObj } from '@storybook/vue3'

const meta: Meta = {
  title: 'Vuetify/Button',
  // Remove tags: ['autodocs'] to avoid the error with string components
  argTypes: {
    color: {
      control: 'select',
      options: ['primary', 'secondary', 'success', 'error', 'warning', 'info'],
      description: 'The color theme of the button',
    },
    variant: {
      control: 'select',
      options: ['elevated', 'flat', 'tonal', 'outlined', 'text', 'plain'],
      description: 'The visual style variant',
    },
    size: {
      control: 'select',
      options: ['x-small', 'small', 'default', 'large', 'x-large'],
      description: 'The size of the button',
    },
    disabled: {
      control: 'boolean',
      description: 'Disable button interaction',
    },
    loading: {
      control: 'boolean',
      description: 'Show loading spinner',
    },
  },
  parameters: {
    docs: {
      description: {
        component: 'Vuetify button component with various styles and states. Based on v-btn from Vuetify 3.',
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

export const Secondary: Story = {
  args: {
    color: 'secondary',
    children: 'Secondary Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const Outlined: Story = {
  args: {
    variant: 'outlined',
    color: 'primary',
    children: 'Outlined Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const Tonal: Story = {
  args: {
    variant: 'tonal',
    color: 'primary',
    children: 'Tonal Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const Text: Story = {
  args: {
    variant: 'text',
    color: 'primary',
    children: 'Text Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const Loading: Story = {
  args: {
    loading: true,
    color: 'primary',
    children: 'Loading...',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const Disabled: Story = {
  args: {
    disabled: true,
    color: 'primary',
    children: 'Disabled Button',
  },
  render: (args) => ({
    setup() {
      return { args }
    },
    template: '<v-btn v-bind="args">{{ args.children }}</v-btn>',
  }),
}

export const AllSizes: Story = {
  render: () => ({
    template: `
      <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <v-btn size="x-small" color="primary">X-Small</v-btn>
        <v-btn size="small" color="primary">Small</v-btn>
        <v-btn size="default" color="primary">Default</v-btn>
        <v-btn size="large" color="primary">Large</v-btn>
        <v-btn size="x-large" color="primary">X-Large</v-btn>
      </div>
    `,
  }),
  parameters: {
    docs: {
      description: {
        story: 'Showcase of all available button sizes',
      },
    },
  },
}

export const AllColors: Story = {
  render: () => ({
    template: `
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <v-btn color="primary">Primary</v-btn>
        <v-btn color="secondary">Secondary</v-btn>
        <v-btn color="success">Success</v-btn>
        <v-btn color="error">Error</v-btn>
        <v-btn color="warning">Warning</v-btn>
        <v-btn color="info">Info</v-btn>
      </div>
    `,
  }),
  parameters: {
    docs: {
      description: {
        story: 'Showcase of all available button colors',
      },
    },
  },
}
