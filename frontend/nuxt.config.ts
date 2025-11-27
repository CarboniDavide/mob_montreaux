import { defineNuxtConfig } from "nuxt/config";

export default defineNuxtConfig({
  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css'
  ],
  modules: [
    '@pinia/nuxt',
  ],
  build: { 
    transpile: ['vuetify'] 
  },
  pinia: {
    storesDirs: []
  },
  typescript: {
    strict: true
  }
  ,runtimeConfig: {
    public: {
      // backend API base used by the SPA — change when running in docker or production
      apiBase: process.env.API_BASE || 'http://localhost/api/v1'
    }
  }
})
