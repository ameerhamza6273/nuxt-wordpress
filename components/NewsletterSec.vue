<script setup>
import { computed } from 'vue'

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27248;

const movie = computed(() => props.movies.find((m) => m.id === movieId));
</script>

<template>
  <div v-if="movie">
    <div class="h-screen flex flex-col justify-center items-center bg-cover bg-center bg-no-repeat px-5 sm:px-10"
     :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }"
     >
      <div class="max-w-[1230px] mx-auto py-16">

        <div class="max-w-[1230px] md:mx-auto ">
          <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
            style="line-height: 50px;">
            {{ movie.acf.title}}
          </h2>
          <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
            style="line-height: 50px;">
            {{ movie.acf.title_2}}
          </h2>

          <p class="mt-5 sm:mt-10 max-w-2xl text-lg text-white text-center mx-auto">{{ movie.acf.description}}
          </p>
          <div class="flex flex-col sm:flex-row mt-10 sm:mt-14 items-center sm:justify-center ">
            <input type="text" :placeholder="movie.acf.placeholder_text"
              class="text-white bg-[#575757] rounded-xl p-2 px-4 min-w-[330px] border-2 border-[#575757]">
            <NuxtLink
            :to="movie.acf.action.url" target="_blank"
              class="bg-[#ffffff1f]  sm:ml-4 mt-4 md:mt-0 delay-300 text-white rounded-xl shadow-md py-2 px-4 font-medium border-2 border-white"
              >{{ movie.acf.action.title }}</NuxtLink>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>
  