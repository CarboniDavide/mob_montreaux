<template>
  <v-container class="py-16">
    <v-row justify="center">
      <v-col cols="12" md="10" lg="8">
        <!-- Hero Section -->
        <div class="text-center mb-12 fade-in">
          <v-avatar size="120" class="mb-6 profile-avatar">
            <v-img src="https://ui-avatars.com/api/?name=Davide+Carboni&size=240&background=1976d2&color=fff&bold=true" />
          </v-avatar>
          
          <h1 class="text-h3 font-weight-bold mb-3 slide-up">
            Hi, I'm Davide Carboni 👋
          </h1>
          
          <p class="text-h6 text-grey-darken-1 mb-6 slide-up-delay">
            Full-Stack Developer & Problem Solver
          </p>

          <div class="d-flex justify-center gap-3 mb-8 slide-up-delay-2">
            <v-chip color="primary" variant="flat" class="pulse">
              <v-icon start>mdi-code-tags</v-icon>
              Laravel
            </v-chip>
            <v-chip color="success" variant="flat" class="pulse-delay-1">
              <v-icon start>mdi-vuejs</v-icon>
              Vue.js
            </v-chip>
            <v-chip color="info" variant="flat" class="pulse-delay-2">
              <v-icon start>mdi-language-typescript</v-icon>
              TypeScript
            </v-chip>
          </div>
        </div>

        <!-- About Cards -->
        <v-row class="mb-8">
          <v-col cols="12" md="6" class="slide-left">
            <v-card elevation="3" class="pa-6 h-100 hover-lift">
              <div class="d-flex align-center mb-4">
                <v-icon size="40" color="primary" class="mr-3">mdi-rocket-launch</v-icon>
                <h2 class="text-h5">What I Do</h2>
              </div>
              <p class="text-body-1">
                I build full-stack web applications with modern frameworks. From REST APIs to interactive UIs, 
                I love creating elegant solutions to complex problems.
              </p>
              <v-divider class="my-4" />
              <div class="text-caption text-grey">
                Currently working on MOB Montreux — a route calculation demo
              </div>
            </v-card>
          </v-col>

          <v-col cols="12" md="6" class="slide-right">
            <v-card elevation="3" class="pa-6 h-100 hover-lift">
              <div class="d-flex align-center mb-4">
                <v-icon size="40" color="success" class="mr-3">mdi-code-braces</v-icon>
                <h2 class="text-h5">Tech Stack</h2>
              </div>
              <v-chip-group column>
                <v-chip size="small" variant="tonal">PHP</v-chip>
                <v-chip size="small" variant="tonal">Laravel</v-chip>
                <v-chip size="small" variant="tonal">Vue.js</v-chip>
                <v-chip size="small" variant="tonal">Nuxt</v-chip>
                <v-chip size="small" variant="tonal">TypeScript</v-chip>
                <v-chip size="small" variant="tonal">Docker</v-chip>
                <v-chip size="small" variant="tonal">MySQL</v-chip>
                <v-chip size="small" variant="tonal">Git</v-chip>
              </v-chip-group>
            </v-card>
          </v-col>
        </v-row>

        <!-- Fun Section -->
        <v-card elevation="4" class="pa-8 text-center fun-card scale-in">
          <v-icon size="60" color="warning" class="mb-4 rotate-icon">mdi-emoticon-happy</v-icon>
          <h3 class="text-h4 mb-4">Want to hear a developer joke?</h3>
          
          <v-expand-transition>
            <div v-show="showJoke" class="mb-6">
              <v-sheet class="pa-6 rounded-lg bg-grey-lighten-4 joke-box">
                <p class="text-h6 text-primary font-italic">{{ currentJoke }}</p>
              </v-sheet>
            </div>
          </v-expand-transition>

          <div class="d-flex justify-center gap-3">
            <v-btn 
              color="primary" 
              size="large" 
              variant="flat"
              @click="nextJoke"
              prepend-icon="mdi-refresh"
            >
              New Joke
            </v-btn>
            <v-btn 
              variant="outlined"
              size="large"
              @click="showJoke = !showJoke"
            >
              {{ showJoke ? 'Hide' : 'Show' }}
            </v-btn>
          </div>
        </v-card>

        <!-- Contact Section -->
        <v-card elevation="3" class="pa-8 mt-8 text-center slide-up-delay-3">
          <h3 class="text-h5 mb-6">Let's Connect</h3>
          <div class="d-flex justify-center gap-4">
            <v-btn 
              icon 
              size="large" 
              variant="outlined"
              href="https://github.com/CarboniDavide" 
              target="_blank"
              class="hover-scale"
            >
              <v-icon>mdi-github</v-icon>
            </v-btn>
            <v-btn 
              icon 
              size="large" 
              variant="outlined"
              href="https://www.linkedin.com/in/davide-carboni-dc" 
              target="_blank"
              class="hover-scale"
            >
              <v-icon>mdi-linkedin</v-icon>
            </v-btn>
            <v-btn 
              icon 
              size="large" 
              variant="outlined"
              href="mailto:carbo.davide@gmail.com"
              class="hover-scale"
            >
              <v-icon>mdi-email</v-icon>
            </v-btn>
          </div>
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
        "A SQL query walks into a bar, walks up to two tables and asks... 'Can I join you?'",
        "There are 10 types of people in the world: those who get binary and those who don't.",
        "Debugging: Being the detective in a crime movie where you are also the murderer.",
        "Why do Java developers wear glasses? Because they can't C#.",
        "How many programmers does it take to change a light bulb? None, that's a hardware problem."
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
      this.showJoke = true
    }
  }
}
</script>

<style scoped>
/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Apply animations */
.fade-in {
  animation: fadeIn 0.8s ease-out;
}

.slide-up {
  animation: slideUp 0.6s ease-out 0.2s backwards;
}

.slide-up-delay {
  animation: slideUp 0.6s ease-out 0.4s backwards;
}

.slide-up-delay-2 {
  animation: slideUp 0.6s ease-out 0.6s backwards;
}

.slide-up-delay-3 {
  animation: slideUp 0.6s ease-out 0.8s backwards;
}

.slide-left {
  animation: slideLeft 0.6s ease-out 0.3s backwards;
}

.slide-right {
  animation: slideRight 0.6s ease-out 0.3s backwards;
}

.scale-in {
  animation: scaleIn 0.6s ease-out 0.5s backwards;
}

.pulse {
  animation: pulse 2s ease-in-out infinite;
}

.pulse-delay-1 {
  animation: pulse 2s ease-in-out 0.3s infinite;
}

.pulse-delay-2 {
  animation: pulse 2s ease-in-out 0.6s infinite;
}

/* Hover effects */
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.hover-scale {
  transition: transform 0.2s ease;
}

.hover-scale:hover {
  transform: scale(1.1);
}

.profile-avatar {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  animation: fadeIn 1s ease-out;
}

.joke-box {
  animation: scaleIn 0.4s ease-out;
}

.rotate-icon:hover {
  animation: rotate 0.6s ease-in-out;
}

.gap-3 {
  gap: 12px;
}

.gap-4 {
  gap: 16px;
}

.h-100 {
  height: 100%;
}
</style>