<template>
  <v-container class="py-10">
    <v-row justify="center">
      <v-col cols="12" md="8">
        <v-card elevation="4" class="pa-6">
          <v-row align="center" class="mb-4">
            <v-col cols="auto">
              <v-avatar size="56" color="deep-purple accent-4" class="white--text">
                😊
              </v-avatar>
            </v-col>
            <v-col>
              <div class="text-h5">About — MOB Montreux</div>
              <div class="text-subtitle-2 text--secondary">A tiny demo app with a big sense of humour</div>
            </v-col>
          </v-row>

          <v-card-text class="mb-4">
            <p>
              This little app is a silly playground built with Nuxt, Vuetify and a splash of TypeScript.
              It loves mountains, good coffee and deliberately small feature sets.
            </p>

            <v-sheet class="pa-4 my-4 rounded" elevation="1" color="grey lighten-4">
              <strong>Why "MOB Montreux"?</strong>
              <div class="mt-2">Because it's easier to remember than a 12-step deployment script.</div>
            </v-sheet>

            <v-divider class="my-4" />

            <div>
              <strong>Fun facts</strong>
              <v-list dense>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Built with Nuxt 3 & Vuetify</v-list-item-title>
                    <v-list-item-subtitle>Fast, modern, and surprisingly polite.</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Local development</v-list-item-title>
                    <v-list-item-subtitle>Dockerfile available — bring your own coffee.</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </div>
          </v-card-text>

          <v-card-actions class="justify-space-between">
            <div>
              <v-btn color="primary" @click="nextJoke" variant="tonal" size="small">
                Tell me a joke
              </v-btn>
              <v-btn text size="small" @click="showJoke = !showJoke">
                {{ showJoke ? 'Hide' : 'Show' }} joke
              </v-btn>
            </div>

            <div v-if="showJoke" class="text-right">
              <strong class="mr-2">Joke:</strong>
              <span class="font-mono">{{ currentJoke }}</span>
            </div>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
export default {
  name: 'AboutPage',
  data() {
    return {
      showJoke: true,
      jokes: [
        "I would tell you a UDP joke, but you might not get it.",
        "Why do programmers prefer dark mode? Because light attracts bugs.",
        "I asked the computer for a joke. It returned '404: Humor not found.'",
        "There are 10 types of people in the world: those who get binary and those who don't.",
        "Debugging: Being the detective in a crime movie where you are also the murderer."
      ] as string[],
      idx: 0,
    }
  },
  computed: {
    currentJoke(): string {
      return this.jokes[this.idx] ?? ''
    }
  },
  methods: {
    nextJoke(): void {
      this.idx = (this.idx + 1) % this.jokes.length
    }
  }
}
</script>

<style scoped>
  .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", monospace; }
</style>