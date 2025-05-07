<script setup>
import { computed } from 'vue'

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27222;

const movie = computed(() => props.movies.find((m) => m.id === movieId));
</script>

<template>
  <div id="aboutUs">
    <div
      v-if="movie"
      class="h-screen flex flex-col justify-center  bg-cover bg-center bg-no-repeat px-5 sm:px-10"
      :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }"

    >
      <div class="max-w-[1230px] mx-auto py-16">
        <div class="text-center ">
          <h2 class="text-white text-xl md:text-2xl mb-10 border-b uppercase inline-block text-center">
            <i>{{ movie.acf.subtitle }}</i>
          </h2>
        </div>
        <div class="max-w-[1230px] md:mx-auto">
          <h2
            class="text-white uppercase text-3xl md:text-5xl mt-5 sm:mt-8 font-bold text-center max-w-2xl mx-auto heading-line-ht"
            style="line-height: 50px;"
          >
            {{ movie.acf.title }}
          </h2>

          <p class="mt-5 sm:mt-8 max-w-4xl text-lg text-white text-center mx-auto">
            {{ movie.acf.description }}
            <NuxtLink :to="movie.acf.action.url" class="text-[#00FFF2] underline" external target="_blank">
              {{ movie.acf.action.title }}
            </NuxtLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
