<script setup>
const features = [
  { 
    icon: 'fa-solid fa-truck-fast', 
    text: 'Free Shipping Over $49', 
    link: '/free-shipping' // Shipping policy page
  },
  { 
    icon: 'fa-solid fa-rotate-left', 
    text: 'Hassle-Free Returns', 
    link: '/selection' // Returns & Exchanges page
  },
  { 
    icon: 'fa-solid fa-shield-halved', 
    text: 'Lifetime Replacement', 
    link: '/lifetime-guarantee' // Lifetime Warranty page
  }
];
</script>

<template>
  <section class="bg-[#fafafa] border-b border-gray-100 py-3">
    <div class="max-w-[1300px] mx-auto px-2">
      <div class="grid grid-cols-3 gap-4 md:divide-x md:divide-gray-200">
        <NuxtLink 
          v-for="(feature, index) in features" 
          :key="index" 
          :to="feature.link"
          class="flex flex-col md:flex-row items-center justify-center text-center group cursor-pointer"
        >
          <div class="w-9 md:w-14 flex items-center justify-center">
            <i :class="[feature.icon, 'text-[#e31e24] text-xl group-hover:scale-110 transition-transform']"></i>
          </div>
          <span class="text-[10px] sm:text-[12px] group-hover:text-[#e31e24] transition-colors md:text-[13px] font-black text-gray-700 tracking-tighter md:tracking-widest uppercase leading-tight md:leading-normal">
            {{ feature.text }}
          </span>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>