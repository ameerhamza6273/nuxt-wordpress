<template>
  <footer class="bg-[#1a1a1a] text-[#ccc] pt-10 pb-6 border-t-4 border-[#f2a900]">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mt-3">
        
        <div class="flex flex-col">
          <NuxtImg 
            src="logic-auto-parts-website-white-logo.png" 
            alt="Logic Auto Parts" 
            class="h-12 w-auto object-contain self-start"
          />
          <p class="opacity-75 mt-6 leading-relaxed text-sm">
            Premium European car parts specialist. We provide OE and OEM solutions for
            your vehicle's longevity.
          </p>

          <div class="flex gap-4 mt-8">
            <a v-for="(icon, index) in socials" :key="index" 
               href="#" 
               class="w-11 h-11 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white transition-all duration-400 hover:bg-[#f2a900] hover:border-[#f2a900] hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(242,169,0,0.3)]">
              <i :class="icon"></i>
            </a>
          </div>
        </div>

        <div class="lg:pl-12">
          <h5 class="text-white font-bold uppercase tracking-widest mb-8 pl-4 border-left-gold">
            Quick Links
          </h5>
          <ul class="space-y-3">
            <li v-for="link in quickLinks" :key="link.name">
              <NuxtLink :to="link.url" class="text-white/70 hover:text-[#f2a900] hover:pl-2 transition-all duration-300 flex items-center">
                {{ link.name }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <div>
          <h5 class="text-white font-bold uppercase tracking-widest mb-8 pl-4 border-left-gold">
            Contact Info
          </h5>
          <div class="space-y-4">
            <div class="flex items-center gap-3 text-white/80 group">
              <i class="fa fa-phone text-[#f2a900] w-5"></i>
              <span class="text-sm group-hover:text-white transition-colors">+000000000000</span>
            </div>
            <div class="flex items-center gap-3 text-white/80 group">
              <i class="fa fa-envelope text-[#f2a900] w-5"></i>
              <span class="text-sm group-hover:text-white transition-colors">booking@logicautoparts.com</span>
            </div>
            <div class="flex items-center gap-3 text-white/80 group">
              <i class="fa fa-map-marker-alt text-[#f2a900] w-5"></i>
              <span class="text-sm group-hover:text-white transition-colors">logicautoparts</span>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-6 border-white/10">
      <div class="text-center opacity-75 text-sm tracking-wider">
        © {{ new Date().getFullYear() }} Logic Auto Parts. All Rights Reserved.
      </div>
    </div>
  </footer>
</template>

<script setup>
const socials = [
  'fab fa-facebook-f',
  'fab fa-instagram',
  'fab fa-whatsapp',
  'fab fa-linkedin-in'
];

const quickLinks = [
  { name: 'About Us', url: '/about' },
  { name: 'DIY Blog', url: '/blog' },
  { name: 'Contact Us', url: '/contact' },
  { name: 'Terms & Conditions', url: '/terms' }
];
</script>

<style scoped>
/* Gold Border-Left Heading Effect */
.border-left-gold {
  border-left: 3px solid #f2a900;
}

/* Custom smooth easing for transitions */
a {
  will-change: transform, padding;
}
</style>