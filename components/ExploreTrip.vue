<script setup>
import { computed } from 'vue'

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
  tripData: {
    type: Array,
    required: true,
  },
});

const movieId = 27275;

const movie = computed(() => props.movies.find((m) => m.id === movieId));
const highlightedTrips = computed(() =>
  props.tripData
    .filter((trip) => trip.acf?.highlight === "true")
    .slice(0, 3) // 👈 sirf pehle 3 show karega
);


</script>
<template>
  <div class=" bg-[#0D0D0D]">
    <div class="grid grid-cols-1  lg:grid-cols-11 mx-auto">
      <div v-if="movie" class="col-span-1 lg:col-span-5 self-center px-6 md:px-16 py-16 text-center md:text-left ">
        <h3 class="text-white  text-xl lg:text-2xl mb-6 lg:mb-16 uppercase border-b inline-block ">
          <i>{{ movie.acf.subtitle }}</i>
        </h3>
        <h2 class="text-white font-bold w-full md:max-w-[400px] text-3xl lg:text-5xl mt-3 uppercase text-center md:text-left"
            style="line-height: 56px;">
          {{ movie.acf.title }}
        </h2>
        <p class="text-lg text-white mt-5">{{ movie.acf.description }} </p>

      </div>

      <div v-if="tripData" class="col-span-2 hidden lg:block relative" v-for="trip in highlightedTrips" :key="trip.id">
        <NuxtLink :to="`/trip/${trip.id}`">
          <NuxtImg :src="trip.acf.explore_trip_desktop" alt="Druk Path Logo"
            class="w-full hover:opacity-[0.7] cursor-pointer" />
          <div class="absolute right-12 bottom-0 transform translate-y-0 rotate-90 origin-right">
            <h2 class="text-white uppercase text-4xl xl:text-5xl font-bold tracking-wider whitespace-nowrap">
              {{ trip.acf.trip_title.length > 20 ? trip.acf.trip_title.substring(0, 20) + '...' : trip.acf.trip_title }}
            </h2>

          </div>
        </NuxtLink>
      </div>
    </div>
    <div v-if="tripData" class="block lg:hidden">
      <div class="flex">
        <NuxtLink :to="`/trip/${trip.id}`" class="w-1/3" v-for="trip in highlightedTrips" :key="trip.id">
          <div class="relative">
            <NuxtImg :src="trip.acf.explore_trip_mobile" alt="Druk Path Logo"
              class="w-full object-contain hover:opacity-[0.7] cursor-pointer" width="200" height="80" />
            <!-- The text will appear at the bottom of the image on mobile -->
            <div class="absolute bottom-0 left-0 w-full p-2 py-6">
              <p class="text-white uppercase text-lg sm:text-3xl font-bold tracking-wider" style="line-height: 20px;">
                {{ trip.acf.trip_title }}
              </p>
            </div>
          </div>
        </NuxtLink>

      </div>

    </div>

  </div>
</template>

<style scoped>
@media (min-width:650px) {
  .text-lg.sm\:text-3xl.font-bold {
    line-height: 46px !important;
  }
}
</style>