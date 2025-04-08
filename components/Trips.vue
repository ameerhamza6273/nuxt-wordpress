<script setup>
import { computed } from "vue";

const props = defineProps({
  dedicatedTeam: {
    type: Array,
    default: () => [],
  },
});

const mainContent = computed(() => props.dedicatedTeam.find(member => member.id === 27266) || null);
const memberOne = computed(() => props.dedicatedTeam.find(member => member.id === 27267) || null);
const memberTwo = computed(() => props.dedicatedTeam.find(member => member.id === 27270) || null);
</script>

<template>
  <div v-if="dedicatedTeam.length" class="px-3 md:px-8 py-16 bg-[url('/trip-bg.png')] bg-cover bg-center bg-no-repeat" id="trips">
    <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 max-w-[1230px] mx-auto">
      <div class="col-span-1 sm:col-span-2 lg:col-span-1 self-start mx-3">
        <h2 class="text-white font-bold text-3xl md:text-5xl mt-3 heading-line-ht" style="line-height: 56px;">
          {{ mainContent.acf.dedicated_title }}
        </h2>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description_1 }}</p>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description_2 }}</p>
        <p v-if="mainContent" class="text-lg text-white lg:max-w-[330px] mt-5">{{ mainContent.acf.description_3 }}</p>
      </div>

      <div v-if="memberOne" class="rounded-lg bg-[#000000b9] mx-3 mt-6 md-mt-3 col-span-1">
        <NuxtImg :src="memberOne.acf.main_image" alt="" class="rounded-t-lg w-full" />
        <div class="p-4 sm:px-6">
          <h3 class="font-bold text-xl text-white">{{ memberOne.acf.title }}</h3>
          <p class="text-lg text-white mt-3">{{ memberOne.acf.description }}</p>
          <div class="flex items-center gap-4 mt-5 mb-2">
            <NuxtImg src="/trip-insta.svg" alt="" class="max-w-[35px]" />
            <NuxtImg src="/trip-facebook.svg" alt="" class="max-w-[35px]" />
          </div>
        </div>
      </div>

      <div v-if="memberTwo" class="rounded-lg bg-[#000000b9] mx-3 mt-6 md-mt-3 col-span-1">
        <NuxtImg  :src="memberTwo.acf.main_image" alt="" class="rounded-t-lg w-full" />
        <div class="p-4 sm:px-6">
          <h3 class="font-bold text-xl text-white">{{ memberTwo.acf.title }}</h3>
          <p class="text-lg text-white mt-3">{{ memberTwo.acf.description }}</p>
          <div class="flex items-center gap-4 mt-5 mb-2">
            <NuxtImg src="/trip-insta.svg" alt="" class="max-w-[35px]" />
            <NuxtImg src="/trip-facebook.svg" alt="" class="max-w-[35px]" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
