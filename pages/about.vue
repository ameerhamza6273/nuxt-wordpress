<template>
  <section>
    <article>
      <PageNavbar />

      <main class="overflow-hidden">
        <TopBar />
        <section class="relative py-32 text-center text-white overflow-hidden">
          <div class="absolute inset-0 z-0">
            <NuxtImg src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=1600"
              class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-tr from-black/95 via-black/80 to-black/40"></div>
          </div>

          <div class="container mx-auto px-4 relative z-10">
            <span class="text-[#e31e24] font-black text-sm tracking-[4px] uppercase block mb-6">
              World-Class Standards
            </span>
            <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tighter uppercase leading-tight">
              Excellence in Every Part
            </h1>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed opacity-90">
              Logic Auto Parts: Delivering Precision Engineering and Unmatched Reliability for European Vehicles.
            </p>
          </div>
        </section>

        <section class="py-24 bg-white">
          <div class="max-w-[1300px] mx-auto px-4 md:px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16 md:gap-24">

              <div class="lg:w-5/12">
                <span class="text-[#e31e24] font-black text-[11px] tracking-[3px] uppercase block mb-4">Since Day
                  One</span>
                <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-6 leading-tight uppercase tracking-tighter">
                  Quality Isn't Just a Goal—It's Our Logic.
                </h2>
                <p class="text-gray-500 text-lg leading-relaxed mb-10 font-medium">
                  Founded on the principles of technical mastery, we have become the go-to destination for enthusiasts
                  who refuse to settle for second best. From high-performance braking systems to intricate engine
                  components, we source only from verified OE and OEM manufacturers.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                  <div class="p-5 border-l-4 border-[#f2a900] bg-gray-50 rounded-r-xl">
                    <h5 class="text-gray-900 font-black uppercase text-sm mb-1">Genuine Parts</h5>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Direct from source</p>
                  </div>
                  <div class="p-5 border-l-4 border-[#e31e24] bg-gray-50 rounded-r-xl">
                    <h5 class="text-gray-900 font-black uppercase text-sm mb-1">Expert Staff</h5>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Technically trained</p>
                  </div>
                </div>
              </div>

              <div class="lg:w-7/12 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div v-for="stat in stats" :key="stat.title"
                    class="bg-white border border-gray-100 p-10 rounded-[20px] text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-[#f2a900] group">
                    <i
                      :class="[stat.icon, 'text-4xl text-[#e31e24] mb-6 block transition-transform group-hover:scale-110']"></i>
                    <h3 class="text-3xl font-black text-gray-900 mb-2">{{ stat.value }}</h3>
                    <p class="text-gray-400 text-[11px] font-black uppercase tracking-widest">{{ stat.title }}</p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>

        <section class="py-24 bg-gray-50">
          <div class="max-w-[1300px] mx-auto px-4 md:px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">Why Choose Logic Auto
              Parts?</h2>
            <div class="w-16 h-1 bg-[#f2a900] mx-auto mt-6 rounded-full mb-16"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
              <div v-for="advantage in advantages" :key="advantage.title" class="group">
                <div
                  class="w-20 h-20 bg-white border border-gray-100 shadow-lg rounded-full flex items-center justify-center mx-auto mb-8 transition-all duration-500 group-hover:bg-[#e31e24] group-hover:scale-110">
                  <i :class="[advantage.icon, 'text-2xl text-[#e31e24] group-hover:text-white']"></i>
                </div>
                <h5 class="text-xl font-black text-gray-900 mb-4 uppercase">{{ advantage.title }}</h5>
                <p class="text-gray-500 text-sm md:text-base px-6 leading-relaxed font-medium italic">
                  {{ advantage.desc }}
                </p>
              </div>
            </div>
          </div>
        </section>

        <section class="py-20 bg-white ">
          <div class="max-w-[1300px] mx-auto px-4 md:px-6">
            <div class="bg-[#212529] rounded-[20px] text-center py-16">
              <h2 class="text-3xl md:text-4xl text-white font-bold uppercase tracking-tighter">
                Ready to Experience the Difference? </h2>
              <p class="opacity-75 text-white  leading-relaxed my-8 ">Join over 50,000 satisfied European car
                owners globally.</p>
              <div class="flex justify-center w-full">
                <NuxtLink
                  to="/shop"
                  class="w-full max-w-[300px] bg-[#dc3545] hover:bg-[#bd171d] text-white font-black py-4 rounded-full flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-red-900/20"
                >
                  <span class="uppercase tracking-widest">BROWSE BMW PARTS</span>
                </NuxtLink>
              </div>
            </div>

          </div>
        </section>
      </main>

      <PageFooter />
    </article>
  </section>
</template>

<script setup>
const stats = [
  { value: '190,000+', title: 'Premium Catalog Items', icon: 'fa-solid fa-boxes-stacked' },
  { value: 'Global', title: 'Logistics Network', icon: 'fa-solid fa-globe-europe' },
  { value: 'VIN-Match', title: '100% Fitment Accuracy', icon: 'fa-solid fa-microchip' },
  { value: 'Lifetime', title: 'Replacement Promise', icon: 'fa-solid fa-handshake' }
];

const advantages = [
  {
    title: 'Industry Leading Warranty',
    icon: 'fa-solid fa-shield-halved',
    desc: 'We are the only ones offering a lifetime replacement guarantee on our entire inventory.'
  },
  {
    title: 'Rapid Fulfillment',
    icon: 'fa-solid fa-truck-fast',
    desc: 'Orders placed by 2 PM ship the same day from our regional distribution centers.'
  },
  {
    title: 'Engineering Support',
    icon: 'fa-solid fa-headset',
    desc: 'Talk to real experts who understand the complexity of your European vehicle.'
  }
];
</script>

<style scoped>
/* Optional: Soft Parallax effect */
.relative {
  background-attachment: fixed;
}
</style>