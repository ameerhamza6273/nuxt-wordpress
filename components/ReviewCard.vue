<template>
    <div class="p-6 md:p-8 bg-[#191818c4] mt-6 rounded-lg text-center md:text-left">
      <NuxtImg :src="review.acf.main_image" alt="review star" class="max-w-[160px] mx-auto md:ml-0 md:mr-auto" />
      <p class="mt-8 text-lg text-white font-bold">{{ review.acf.title }}</p>
      <p class="mt-3 text-lg text-white">{{ review.acf.description }}</p>
    </div>
  </template>
  
  <script setup>
  defineProps({
    review: Object
  });
  </script>
  