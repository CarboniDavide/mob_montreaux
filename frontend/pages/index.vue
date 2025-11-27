<template>
  <v-container class="py-10">
    <v-row justify="center">
      <v-col cols="12" md="10">
        <v-card elevation="3" class="pa-6">
          <v-row class="g-6" align="center">
            <v-col cols="12" md="6">
              <v-img
                :src="imageSrc"
                alt="Montreux station"
                aspect-ratio="16/9"
                class="rounded-lg"
                cover
              />
              <div class="text-caption mt-2 grey--text text--darken-1">{{ caption }}</div>
            </v-col>

            <v-col cols="12" md="6">
              <div class="text-h4 mb-2">Montreux Station</div>
              <div class="text-subtitle-1 mb-4">A little stop, a long story</div>

              <p class="mb-4">
                Montreux railway station sits on the shore of Lake Geneva and is a gateway to the stunning Riviera towns and alpine passes.
                Passenger services began in the 19th century and the station became an important link for tourism and regional transport.
              </p>

              <p class="mb-4">
                The town is famous for the Montreux Jazz Festival (since 1967) and its relaxed lakeside promenade — perfect for a slow cup of coffee and an even slower scenic train ride.
              </p>

              <v-btn color="primary" href="/find-distance" class="mb-4" variant="tonal">Read more about this tiny app</v-btn>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
export default {
  name: 'HomePage',
  data() {
    return {
      imageSrc: '/images/montreaux-station.jpg',
      caption: 'Montreux station, view across Lake Geneva'
    }
  }
}
</script>

<style scoped>
  .rounded-lg { border-radius: 8px; overflow: hidden; }
</style>
