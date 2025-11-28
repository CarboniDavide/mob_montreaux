<template>
  <v-container class="py-10">
    <v-row justify="center">
      <v-col cols="12" md="10">
        <v-card elevation="6" class="pa-8">
          <v-row align="center">
            <v-col cols="12" md="6" order="2" order-md="1">
              <v-img
                :src="imageSrc"
                alt="Montreux station"
                aspect-ratio="16/9"
                class="rounded-lg elevation-4"
                cover
              />
              <div class="text-caption mt-3 text-grey-darken-1">{{ caption }}</div>
            </v-col>

            <v-col cols="12" md="6" order="1" order-md="2">
              <div class="text-center text-md-left mb-6">
                <v-icon size="56" color="primary" class="mb-3">mdi-train</v-icon>
                <div class="text-h3 font-weight-bold mb-2">Montreux Station</div>
                <div class="text-h6 text-grey-darken-1 mb-4">A little stop, a long story</div>
              </div>

              <p class="text-body-1 mb-4">
                Montreux railway station sits on the shore of Lake Geneva and is a gateway to the stunning Riviera towns and alpine passes.
                Passenger services began in the 19th century and the station became an important link for tourism and regional transport.
              </p>

              <v-divider class="my-4" />

              <p class="text-body-1 mb-6">
                The town is famous for the Montreux Jazz Festival (since 1967) and its relaxed lakeside promenade — perfect for a slow cup of coffee and an even slower scenic train ride.
              </p>

              <v-btn 
                color="primary" 
                href="/find-distance" 
                size="large"
                prepend-icon="mdi-map-marker-distance"
                variant="flat"
                class="mb-4"
              >
                Find Routes
              </v-btn>
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
  .rounded-lg { 
    border-radius: 12px; 
    overflow: hidden; 
  }
</style>
