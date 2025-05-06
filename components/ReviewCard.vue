<template>
  <div class="p-6 md:p-8 bg-[#191818c4] mt-6 rounded-lg text-center md:text-left h-full">
    <NuxtImg :src="review.acf.image" alt="review star" class="max-w-[160px] mx-auto md:ml-0 md:mr-auto" />
    <p class="mt-8 text-lg text-white font-bold line-clamp-1">{{ review.acf.title }}</p>
    <p class="mt-3 text-lg text-white line-clamp-5">{{ review.acf.description }}</p>
    <p class="mt-8 text-lg text-[#00FFF2] underline cursor-pointer">
      <NuxtLink :to="review.acf?.action || '/#'" target="_blank" external>See more</NuxtLink>
    </p>
  </div>
</template>

<script setup>
  defineProps({
    review: Object
  });
</script>