<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation } from "swiper/modules";
import { ref, onMounted, onUnmounted, computed } from "vue";

// Props
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

// Find a movie by ID
const movieId = 27221;
const movie = computed(() => props.movies.find((m) => m.id === movieId));
const highlightedTrips = computed(() =>
  props.tripData.filter((trip) => trip.acf?.highlight === "true")
);
// Reactive ref to track screen width
const isMobile = ref(false);

const checkViewport = () => {
  isMobile.value = window.innerWidth < 600; // you can change 600 to any breakpoint
};

onMounted(() => {
  checkViewport();
  window.addEventListener("resize", checkViewport);
});

onUnmounted(() => {
  window.removeEventListener("resize", checkViewport);
});
</script>


<template>
  <div v-if="movie" class=" sm:pl-8 md:pl-16 md:pt-24 bg-cover bg-center bg-no-repeat"
    :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }" id="home">
    <div class="grid md:grid-cols-2 grid-cols-1 py-24 sm:py-32">
      <div class="self-end mt-10 md:mt-0 pl-6 md:pl-0">
        <h1 class="text-white font-bold text-center md:text-left md:max-w-[200px] text-3xl md:text-5xl heading-line-ht"
          style="line-height: 56px">
          {{ movie.acf.title }}
        </h1>
        <p class="text-lg text-white text-center md:text-left md:max-w-[430px] mt-3 pr-4">
          {{ movie.acf.description }}
        </p>
        <div class="flex mt-8">
          <NuxtLink :to="movie.acf.action.url" 
            class="bg-[#ffffff1f] mx-auto md:ml-0 md:mr-auto mb-14 md:mb-0 delay-300 text-white rounded-xl shadow-md py-2 px-4 font-medium border-2 border-white">
            {{ movie.acf.action.title }}
          </NuxtLink>
        </div>
      </div>
      <div class="bg-transparent mt-10 md:mt-0">
        <!-- Swiper Slider -->
        <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :pagination="{ clickable: true }"
          :spaceBetween="20"
          :centeredSlides="isMobile"
           :slidesPerView="2.5" :modules="[Navigation]" :loop="true" class="w-full relative"
          style="padding-bottom: 70px !important;" :breakpoints="{
            340: { slidesPerView: 1.5 },
            600: { slidesPerView: 2 },
            1124: { slidesPerView: 2.5 }
          }">
          <!-- Navigation Arrows -->
          <section class="parallax-slider-navigation cursor-pointer">
            <article class="nav-indicator prevArrow">
              <NuxtImg src="/left-slide-icon.svg" 
              alt="Left Arrow" 
              class="w-10 absolute z-40 bottom-24 md:bottom-0 left-[30%] md:left-[20px]" />
            </article>
            <article class="nav-indicator nextArrow">
              <NuxtImg src="/right-slide-icon.svg" 
              alt="Right Arrow"
              class="w-10 absolute z-40 bottom-24 md:bottom-0 left-[60%] md:left-[100px]" />
            </article>
          </section>

          <!-- Swiper Slides -->
          <swiper-slide v-for="(data, index) in highlightedTrips" :key="index">
            <div class="rounded-xl mx-3 min-h-[300px] overflow-hidden relative"
              :style="{ backgroundImage: `url(${data.acf.bg_image})`, backgroundSize: 'cover', backgroundPosition: 'center' }">

              <!-- Gradient Overlay -->
              <div
                class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent pointer-events-none">
              </div>

              <div class="p-6 px-7 z-10 flex flex-col md:justify-end min-h-[350px] md:min-h-[300px] relative">
                <!-- Dynamic Data -->
                <p class="text-lg text-white text-center z-20">
                  <b>{{ data.acf.title }} {{ data.acf.title_2 }}</b><br />
                  {{ data.acf.subtitle }}
                </p>
                <NuxtLink 
                  :to="`/trip/${data.id}`"
                  class="bg-[#ffffff1f] mt-3 delay-300 text-white rounded-xl shadow-md py-2 px-4 block font-medium text-center border-2 border-white cursor-pointer z-20">
                  Book Now
                </NuxtLink>
              </div>
            </div>

          </swiper-slide>
        </swiper>
      </div>
    </div>
  </div>
</template>

<style scoped>
.mySwiper {
    margin-top: 20px;
}

</style>