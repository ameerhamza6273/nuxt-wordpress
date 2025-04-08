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
  props.tripData.filter((trip) => trip.acf?.highlight === "true")
);


</script>
<template>
  <div class=" bg-[#0D0D0D]" id="exploreTrips" >
    <div class="grid grid-cols-1  md:grid-cols-11 mx-auto">
      <div v-if="movie" class="col-span-1 md:col-span-5 self-center px-6 md:px-16 py-16 text-center md:text-left ">
        <h3 class="text-white  text-xl lg:text-2xl mb-6 lg:mb-16 uppercase border-b inline-block ">
          <i>{{ movie.acf.subtitle }}</i>
        </h3>
        <h2 class="text-white font-bold max-w-[400px] text-3xl lg:text-5xl mt-3 uppercase heading-line-ht"
          style="line-height: 56px;">
          {{ movie.acf.title }}
        </h2>
        <p class="text-lg text-white  mt-5">{{ movie.acf.description }} </p>

      </div>
      
      <div v-if="tripData" class="col-span-2 hidden md:block" v-for="trip in highlightedTrips" :key="trip.id">
        <NuxtLink :to="`/trip/${trip.id}`">
          <NuxtImg :src="trip.acf.explore_trip_desktop" alt="Druk Path Logo" class="w-full hover:opacity-[0.7] cursor-pointer" />
        </NuxtLink>
      </div>


    </div>
    <div v-if="tripData" class="block md:hidden">
      <div class="flex" >
        <NuxtLink :to="`/trip/${trip.id}`" class="w-1/3" v-for="trip in highlightedTrips" :key="trip.id">
          <NuxtImg :src="trip.acf.explore_trip_mobile" alt="Druk Path Logo"
            class="w-full object-contain hover:opacity-[0.7] cursor-pointer" width="200" height="80" />
        </NuxtLink>

      </div>

    </div>

  </div>
</template>