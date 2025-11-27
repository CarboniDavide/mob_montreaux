<template>
  <v-app>
    <v-main>
      <v-container class="py-10">
        <v-card>
          <v-card-title class="text-h5">Nuxt + Vuetify + Docker</v-card-title>
          <v-card-text>{{ getMessage }}</v-card-text>
          <v-card-text>{{ sayHelloWorld }}</v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script lang="ts">
  import { helloWorldStore } from '../stores/helloWorld';

  export default {
    name: 'HomePage',
    data() {
      return {
        helloWorldStore: helloWorldStore()
      }
    },
    computed: {
      sayHelloWorld(): string {
        return this.helloWorldStore.sayHello;
      },
      getMessage(): string {
        return this.helloWorldStore.getMessage;
      }
    }
  }
</script>
