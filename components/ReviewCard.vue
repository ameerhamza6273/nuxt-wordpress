<template>
    <div class="p-6 md:p-8 bg-[#191818c4] mt-6 rounded-lg">
      <img :src="review.acf.main_image" alt="" class="max-w-[160px]">
      <p class="mt-8 text-lg text-white font-bold">{{ review.acf.title }}</p>
      <p class="mt-3 text-lg text-white">{{ review.acf.description }}</p>
    </div>
  </template>
  
  <script setup>
  defineProps({
    review: Object
  });
  </script>
  