<script setup>
  import { computed } from "vue";
  
  const props = defineProps({
    dedicatedTeam: Array, // ✅ Ensure this prop is received correctly
  });
  
  // ✅ ID-based filtering
  const mainReview = computed(() => props.dedicatedTeam.find(member => member.id === 27287) || null);
  const firstReview = computed(() => props.dedicatedTeam.find(member => member.id === 27293) || null);
  const secondReview = computed(() => props.dedicatedTeam.find(member => member.id === 27294) || null);
  const thirdReview = computed(() => props.dedicatedTeam.find(member => member.id === 27295) || null);
  </script>
<template>
    <div id="reviews">
      <div class="bg-[url('/Review-mob.png')] md:bg-[url('/reviews-bg.jpg')] bg-cover bg-top bg-no-repeat">
        <div class="max-w-[1230px] mx-auto py-16 px-4">
          <h2 class="text-white text-xl md:text-2xl mb-5 md:mb-10 border-b uppercase inline-block">
            <i>{{ mainReview?.acf?.subtitle }}</i>
          </h2>
          <div class="max-w-[1230px] md:mx-auto">
            <h2 class="text-white uppercase text-3xl max-w-[780px] md:text-5xl mt-5 font-bold heading-line-ht" style="line-height: 50px;">
              {{ mainReview?.acf?.title }}
            </h2>
  
            <div class="p-6 md:p-8 bg-[#2927279a] md:bg-[#191818c4] mt-10 md:mt-16 rounded-lg">
              <div class="flex flex-col md:flex-row md:items-center gap-4">
                <img src="/5-Star-Ratings.png" alt="5 Star Ratings" class="max-w-[200px]">
                <p class="text-lg text-white">{{ mainReview?.acf?.description }}</p>
              </div>
              <p class="mt-12 text-lg text-white font-bold">Keson Lim</p>
              <p v-if="mainReview?.acf?.description_1" class="mt-3 text-lg text-white">{{ mainReview?.acf?.description_1 }}</p>
              <p v-if="mainReview?.acf?.description_2" class="mt-6 text-lg text-white">{{ mainReview?.acf?.description_2 }}</p>
              <p v-if="mainReview?.acf?.description_3" class="mt-6 text-lg text-white">{{ mainReview?.acf?.description_3 }}</p>
            </div>
  
            <div class="flex flex-col md:flex-row items-center justify-between gap-1 md:gap-10 lg:gap-28">
              <ReviewCard v-if="firstReview" :review="firstReview"  />
              <ReviewCard v-if="secondReview" :review="secondReview"  />
              <ReviewCard v-if="thirdReview" :review="thirdReview"  />
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  
  