<script setup>
import { computed } from 'vue'

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27275;

const movie = computed(() => props.movies.find((m) => m.id === movieId));
</script>
<template>
  <div class=" bg-[#0D0D0D]" id="exploreTrips" v-if="movie">
    <div class="grid grid-cols-1  md:grid-cols-11 mx-auto">
      <div class="col-span-1 md:col-span-5 self-center px-6 md:px-16 py-16 text-center md:text-left ">
        <h3 class="text-white  text-xl lg:text-2xl mb-6 lg:mb-16 uppercase border-b inline-block ">
          <i>{{ movie.acf.subtitle }}</i>
        </h3>
        <h2 class="text-white font-bold max-w-[400px] text-3xl lg:text-5xl mt-3 uppercase font-bevietnam heading-line-ht"
          style="line-height: 56px;">
          {{ movie.acf.title }}
        </h2>
        <p class="text-lg text-white  mt-5">{{ movie.acf.description }}</p>

      </div>
      <div class="col-span-2 hidden md:block">
        <NuxtLink to="/druk-path"><img :src="movie.acf.logo_one" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
      <div class="col-span-2 hidden md:block" >
        <NuxtLink to="/dolomites"><img :src="movie.acf.logo_two" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
      <div class="col-span-2 hidden md:block">
        <NuxtLink to="/mont-blanc"><img :src="movie.acf.logo_three" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>

    </div>
    <div class="block md:hidden">
      <div class="flex ">
        <NuxtLink to="/druk-path" class="w-1/3"><img :src="movie.acf.logo_four" alt="" class=" w-full object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
        <NuxtLink to="/dolomites" class="w-1/3"><img :src="movie.acf.logo_five" alt="" class=" w-full object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
        <NuxtLink to="/mont-blanc" class="w-1/3"><img :src="movie.acf.logo_six" alt="" class=" w-full  object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
    </div>

  </div>
</template>