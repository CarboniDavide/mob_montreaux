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
})
