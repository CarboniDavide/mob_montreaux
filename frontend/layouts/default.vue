<template>
  <v-app>
    <!-- Header -->
    <v-app-bar color="primary" dark flat>
      <v-container class="d-flex align-center justify-space-between">
        <h1 class="text-h6">MOB Website</h1>
        <div>
          <NuxtLink to="/" class="mr-4 text-white">Home</NuxtLink>
          <NuxtLink to="/find-distance" class="mr-4 text-white">Find Distance</NuxtLink>
          <NuxtLink to="/about" class="text-white">About</NuxtLink>
        </div>
      </v-container>
    </v-app-bar>

    <!-- Main content -->
    <v-main>
      <v-container class="py-6">
        <slot />
      </v-container>
    </v-main>

    <!-- Footer -->
    <v-footer color="primary" dark app>
      <v-container class="text-center">
        © Made with <v-icon class="mx-1 text-red" size="20">mdi-heart</v-icon> by Davide Carboni
      </v-container>
    </v-footer>
  </v-app>
</template>

<script setup>
</script>
