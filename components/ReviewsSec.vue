<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation, Pagination } from "swiper/modules";

import { computed, ref } from "vue";

const showMore = ref(false)
const props = defineProps({
  dedicatedTeam: Array, // ✅ Ensure this prop is received correctly
});

// ✅ ID-based filtering
const mainReview = computed(() => props.dedicatedTeam.find(member => member.id === 27770) || null);
const firstReview = computed(() => props.dedicatedTeam.find(member => member.id === 27774) || null);
const secondReview = computed(() => props.dedicatedTeam.find(member => member.id === 27779) || null);
const thirdReview = computed(() => props.dedicatedTeam.find(member => member.id === 27780) || null);
</script>
<template>
  <div id="reviews">
    <div class="bg-[url('/Review-mob.png')] md:bg-[url('/reviews-bg.jpg')] bg-cover bg-top bg-no-repeat">
      <div class="max-w-[1230px] mx-auto py-16 px-4">
        <h2 class="text-white text-xl md:text-2xl mb-5 md:mb-10 border-b uppercase inline-block">
          <i>{{ mainReview?.acf?.subtitle }}</i>
        </h2>
        <div class="max-w-[1230px] md:mx-auto">
          <h2 class="text-white uppercase text-3xl max-w-[780px] md:text-5xl mt-5 font-bold heading-line-ht"
            style="line-height: 50px;">
            {{ mainReview?.acf?.title }}
          </h2>

          <div class="p-6 md:p-8 bg-[#2927279a] md:bg-[#191818c4] mt-10 md:mt-16 rounded-lg">
            <div class="flex flex-col md:flex-row md:items-center gap-4 text-center">
              <p class="mt-3 text-lg text-white font-bold md:hidden block">
                {{ mainReview?.acf?.dedicated_title }}
              </p>
              <NuxtImg src="/5-Star-Ratings.png" alt="5 Star Ratings" class="max-w-[200px] mx-auto  md:mx-0" />
              <p class="text-lg text-white">5.0 RATING</p>
            </div>
            <div class="md:block hidden line-clamp-8">
              <h1 class="mt-12 text-lg text-white font-bold hidden md:block">
                {{ mainReview?.acf?.dedicated_title }}
              </h1>
              <p v-if="mainReview?.acf?.description_1" class="mt-6 md:mt-3 text-lg text-white">
                {{ mainReview?.acf?.description_1 }}
              </p>
              <p v-if="mainReview?.acf?.description_2" class="mt-6 text-lg text-white">
                {{ mainReview?.acf?.description_2}}
              </p>
              <p v-if="mainReview?.acf?.description_3" class="mt-6 text-lg text-white">
                {{ mainReview?.acf?.description_3 }}                
              </p>
            </div>
            <div class="block md:hidden">
              <div v-if="!showMore" class="block md:block">
                <p v-if="mainReview?.acf?.description_1" class="mt-3 text-lg text-white">
                  {{ mainReview.acf.description_1.slice(0, 150) }}...
                </p>
                <button @click="showMore = true" class="text-white underline mt-3">Read More</button>
              </div>

              <!-- Full content if showMore is true -->
              <div v-else>
                <p v-if="mainReview?.acf?.description_1" class="mt-3 text-lg text-white">
                  {{ mainReview.acf.description_1 }}
                </p>
                <p v-if="mainReview?.acf?.description_2" class="mt-6 text-lg text-white">
                  {{ mainReview.acf.description_2 }}
                </p>
                <p v-if="mainReview?.acf?.description_3" class="mt-6 text-lg text-white">
                  {{ mainReview.acf.description_3 }}
                </p>
                <button @click="showMore = false" class="text-white underline mt-3">Read Less</button>
              </div>
            </div>
          </div>

          <!-- Mobile Slider (Only visible on small screens) -->
          <div class="block md:hidden relative w-full">
            <swiper :navigation="{ nextEl: '.reviewNextArrow', prevEl: '.reviewPrevArrow' }"
              :pagination="{ clickable: true }" :spaceBetween="10" :slidesPerView="1" :loop="true"
              :modules="[Navigation, Pagination]" :dir="'ltr'" class="w-full" style="padding-bottom: 50px !important;">

              <!-- Navigation Arrows -->
              <section class="parallax-slider-navigation cursor-pointer">
                <article class="nav-indicator prevArrow">
                  <NuxtImg src="/left-slide-icon.svg" alt="Left Arrow"
                    class="w-10 absolute z-40 bottom-0 left-[10%] md:left-[30%] lg:left-[37%]" />
                </article>
                <article class="nav-indicator nextArrow">
                  <NuxtImg src="/right-slide-icon.svg" alt="Right Arrow"
                    class="w-10 absolute z-40 bottom-0 right-[10%] md:right-[30%] lg:right-[37%]" />
                </article>
              </section>
              <swiper-slide v-if="firstReview">
                <ReviewCard :review="firstReview" />
              </swiper-slide>
              <swiper-slide v-if="secondReview">
                <ReviewCard :review="secondReview" />
              </swiper-slide>
              <swiper-slide v-if="thirdReview">
                <ReviewCard :review="thirdReview" />
              </swiper-slide>
            </swiper>

            <!-- Navigation Arrows for Mobile -->
            <section class="parallax-slider-navigation cursor-pointer">
              <article class="nav-indicator reviewPrevArrow">
                <NuxtImg src="/left-slide-icon.svg" alt="Left Arrow" class="w-10 absolute z-40 bottom-0 left-[10%]" />
              </article>
              <article class="nav-indicator reviewNextArrow">
                <NuxtImg src="/right-slide-icon.svg" alt="Right Arrow"
                  class="w-10 absolute z-40 bottom-0 right-[10%]" />
              </article>
            </section>
          </div>

          <!-- Desktop Static Layout (Hidden on mobile) -->
          <div class="hidden md:flex md:grid md:grid-cols-3 gap-1 md:gap-4 lg:gap-8">
            <ReviewCard v-if="firstReview" :review="firstReview" />
            <ReviewCard v-if="secondReview" :review="secondReview" />
            <ReviewCard v-if="thirdReview" :review="thirdReview" />
          </div>

        </div>
      </div>
    </div>
  </div>
</template>


<style scoped>
:deep(.swiper-pagination-bullet) {
  background-color: #fff !important;
  margin-right: 8px !important;
}

:deep(.swiper-pagination-bullet-active) {
  background-color: transparent !important;
  border: 2px solid #fff;
}
</style>