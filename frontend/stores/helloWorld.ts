
import { defineStore } from 'pinia'

export interface HelloWorldState {
  message: string
}

export const helloWorldStore = defineStore('helloWorld', {
  state: (): HelloWorldState => ({ 
    message: 'Welcome to Nuxt 3! This come from Pinia store.',
  }),
  getters: {
    sayHello: (state): string => "Hello World!",
    getMessage: (state): string => state.message,
  },
  actions: {
    replaceWithDavide(): void {
      this.message = 'Welcome Davide!'
    },
  },
})