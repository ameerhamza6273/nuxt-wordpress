<script setup>
import { computed } from 'vue'

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27334;

const movie = computed(() => props.movies.find((m) => m.id === movieId));
</script>
<template>
    <div id="aboutUs" v-if="movie">
      
     <p class="text-center uppercase text-white text-2xl font-semibold p-3 py-7 bg-[#0E0E0E]">
       {{ movie.acf.title }}
    </p>
      <div class="   bg-cover bg-center bg-no-repeat px-4"
      :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }">
  
        <div class="max-w-[990px] md:mx-auto py-12 ">
          <div class="flex flex-col sm:flex-row flex-wrap items-center justify-around gap-10">
            <img :src="movie.acf.logo_one" alt=""
              class="w-100 max-w-[150px] my-3 basis-1/2 md:basis-1/3 lg:basis-1/4">
            <img :src="movie.acf.logo_two" alt=""
              class="w-100 max-w-[210px] my-3 basis-1/2 md:basis-1/3 lg:basis-1/4">
            <img :src="movie.acf.logo_three" alt=""
              class="w-100 max-w-[180px] my-3 basis-1/2 md:basis-1/3 lg:basis-1/4">
            <img :src="movie.acf.logo_four" alt="" class="w-100 max-w-[160px] my-3 basis-1/2 md:basis-1/3 lg:basis-1/4">
            <img :src="movie.acf.logo_five" alt="" class="w-100 max-w-[140px] my-3">
            <img :src="movie.acf.logo_six" alt="" class="w-100 max-w-[105px] my-3">
            <img :src="movie.acf.logo_seven" alt="" class="w-100 max-w-[120px] my-3">
            <img :src="movie.acf.logo_eight" alt="" class="w-100 max-w-[210px] my-3">
          </div>
  
        </div>
      </div>
    </div>
  </template>
 