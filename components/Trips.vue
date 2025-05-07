<script setup>
import { computed } from "vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

const props = defineProps({
  dedicatedTeam: {
    type: Array,
    default: () => [],
  },
  teamMembers: {
    type: Array,
    default: () => [],
  },
});

const mainContent = computed(() => props.dedicatedTeam.find(member => member.id === 27783) || null);
const assistantMembers = computed(() => props.teamMembers);

</script>

<template>
  <div v-if="dedicatedTeam.length" class="px-3 md:px-8 py-16 bg-[url('/trip-bg.png')] bg-cover bg-center bg-no-repeat"
    id="trips">
    <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 max-w-[1230px] mx-auto">
      <div class="col-span-1 sm:col-span-2 lg:col-span-1 self-start mx-3">
        <h2 class="text-white font-bold text-3xl md:text-5xl mt-3 heading-line-ht" style="line-height: 56px;">
          {{ mainContent.acf.title }}
        </h2>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description }}</p>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description_2 }}</p>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description_3 }}</p>
      </div>

      <div v-if="assistantMembers" class="mt-6 md-mt-3 col-span-2">
        <Swiper :modules="[Navigation]" :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }"
            :spaceBetween="20" :breakpoints="{
            320: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 2 } // Set desktop to 2
          }" class="relative w-full pb-20" style="padding-bottom: 60px;">
    
    
          <SwiperSlide v-for="member in assistantMembers" :key="member.id">
            <div class="rounded-lg bg-[#000000b9] mx-1 min-h-[500px]"> 
              <NuxtImg :src="member.acf.main_image" alt="" class="rounded-t-lg w-full h-[307px] object-cover"  />
              <div class="p-4 sm:px-6">
                <h3 class="font-bold text-xl text-white">{{ member.acf.title }}</h3>
                <p class="text-lg text-white mt-3">{{ member.acf.description }}</p>
                <div class="flex items-center gap-4 mt-5 mb-2">
                  <NuxtLink :to="member.acf.action">
                    <NuxtImg src="/trip-insta.svg" alt="" class="max-w-[35px]" external target="_blank" />
                  </NuxtLink>
                  <NuxtLink :to="member.acf.action_2">
                    <NuxtImg src="/trip-facebook.svg" alt="" class="max-w-[35px]" external target="_blank" />
                  </NuxtLink>
                </div>
              </div>
            </div>
          </SwiperSlide>

          <!-- Navigation Arrows -->
          <section class="parallax-slider-navigation cursor-pointer">
            <article class="nav-indicator prevArrow">
              <NuxtImg src="/left-slide-icon.svg" alt="Left Arrow"
                class="w-10 absolute z-40 bottom-0 left-[30%] md:left-[20px]" />
            </article>
            <article class="nav-indicator nextArrow">
              <NuxtImg src="/right-slide-icon.svg" alt="Right Arrow"
                class="w-10 absolute z-40 bottom-0 left-[60%] md:left-[100px]" />
            </article>
          </section>
        </Swiper>

      </div>

    </div>
  </div>
</template>

<style>
:deep(.swiper-pagination-bullet) {
  background-color: #fff !important;
}

:deep(.swiper-pagination-bullet-active) {
  background-color: transparent !important;
  border: 2px solid #fff;
}
</style>
