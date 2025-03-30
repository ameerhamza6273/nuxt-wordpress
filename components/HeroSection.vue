<script setup>
const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27221;
const movie = computed(() => props.movies.find((m) => m.id === movieId));
</script>

<template>
  <div
    v-if="movie"
    class="pl-6 sm:pl-8 md:pl-16 md:pt-24 bg-cover bg-center bg-no-repeat"
    :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }"
    id="home"
  >
    <div class="grid md:grid-cols-2 grid-cols-1 py-24 sm:py-32">
      <div class="self-end mt-10 md:mt-0">
        <h1
          class="text-white font-bold max-w-[200px] text-3xl md:text-5xl font-bevietnam heading-line-ht"
          style="line-height: 56px"
        >
          {{ movie.acf.title }}
        </h1>
        <p class="text-lg text-white max-w-[430px] mt-3 pr-4">
          {{ movie.acf.description }}
        </p>
        <div class="flex mt-8">
          <NuxtLink
            :to="movie.acf.action.url"
            target="_blank"
            class="bg-[#ffffff1f] delay-300 text-white rounded-xl shadow-md py-2 px-4 font-bevietnam font-medium border-2 border-white"
          >
            {{ movie.acf.action.title }}
          </NuxtLink>
        </div>
      </div>
      <HeroSecSlider />
    </div>
  </div>
</template>