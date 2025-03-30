<template>
    <div
      class="h-screen w-screen overflow-hidden fixed top-0 left-0 bg-[#161616] z-[9999] flex justify-center items-center flex-col img"
    >
      <img
        format="webp"
        quality="100"
        src="/Logo.png"
        class="w-60 box"
        alt="user"
        v-motion
        :initial="{ scale: 1 }"
        :animate="{ scale: 1.1 }"
        :transition="{ duration: 1, repeat: Infinity, direction: 'alternate' }"
      />
    </div>
  </template>
  
  <script setup lang="ts">
  import { Motion } from "@motionone/vue";
  </script>
  