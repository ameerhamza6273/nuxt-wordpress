<template>
  <div>
    <page-navbar />
    <main>
        <TopBar />
    <section class="relative py-24 bg-[#212529] text-center text-white overflow-hidden">
      <div class="absolute inset-0 opacity-20">
        <NuxtImg src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1600" class="w-full h-full object-cover" />
      </div>
      <div class="container mx-auto px-4 relative z-10">
        <span class="text-[#e31e24] font-black text-sm tracking-[4px] uppercase block mb-4">Fast & Reliable</span>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tighter uppercase">Coast-to-Coast Shipping</h1>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto">
          Get your premium European parts in 5 days or less. Free shipping on all orders over $49.
        </p>
      </div>
    </section>

    <section class="py-20  relative z-20">
      <div class="container max-w-[1200px] mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="feature in shippingFeatures" :key="feature.title" 
               class="bg-white p-8 rounded-2xl shadow-xl border border-gray-50 flex flex-col items-center text-center group hover:-translate-y-2 transition-all duration-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-[#e31e24] transition-colors">
              <i :class="[feature.icon, 'text-2xl text-[#e31e24] group-hover:text-white']"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-3 uppercase text-sm tracking-wide">{{ feature.title }}</h3>
            <p class="text-gray-500 text-xs leading-relaxed">{{ feature.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-gray-50">
      <div class="container max-w-[1200px] mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
          <div class="lg:w-1/2">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-8 uppercase tracking-tighter">
              Strategic Distribution <br/> <span class="text-[#e31e24]">Hubs</span>
            </h2>
            <div class="space-y-8">
              <div class="flex gap-6">
                <div class="shrink-0 w-12 h-12 bg-white rounded-lg shadow flex items-center justify-center text-[#f2a900]">
                  <i class="fa-solid fa-warehouse"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 uppercase">Mesa, Arizona</h4>
                  <p class="text-sm text-gray-500 mt-1">Our 146,000-square-foot Western hub utilizes the latest packing technology to serve the Western US with 100% accuracy.</p>
                </div>
              </div>
              <div class="flex gap-6">
                <div class="shrink-0 w-12 h-12 bg-white rounded-lg shadow flex items-center justify-center text-[#e31e24]">
                  <i class="fa-solid fa-warehouse"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 uppercase">Milford, Connecticut</h4>
                  <p class="text-sm text-gray-500 mt-1">Our original Northeast headquarters. Local to the area? Stop by our Pick-Up Center to get your parts in person.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="lg:w-1/2 w-full">
            <div class="bg-white p-8 rounded-3xl shadow-2xl border border-gray-100">
              <h4 class="text-xl font-black mb-6 uppercase tracking-tight border-b pb-4">Shipping Rates</h4>
              <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                  <span class="font-bold text-gray-700">Orders Over $49</span>
                  <span class="text-[#e31e24] font-black uppercase">FREE</span>
                </div>
                <div class="flex justify-between items-center p-4 border border-gray-100 rounded-xl">
                  <span class="text-gray-600">Under $49 (Ground)</span>
                  <span class="font-bold text-gray-900">$6.99</span>
                </div>
                <div class="flex justify-between items-center p-4 border border-gray-100 rounded-xl">
                  <span class="text-gray-600">Standard Delivery</span>
                  <span class="font-bold text-gray-900">3-5 Days</span>
                </div>
              </div>
              <p class="text-[10px] text-gray-400 mt-6 uppercase tracking-widest text-center italic">
                *Applies to contiguous 48 U.S. states only
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-24 bg-white">
      <div class="container max-w-[1000px] mx-auto px-4 text-center">
        <div class="inline-block px-4 py-1 bg-red-50 text-[#e31e24] rounded-full text-[10px] font-black uppercase tracking-[3px] mb-6">
          Global Reach
        </div>
        <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-8 uppercase tracking-tighter">International Customers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
          <div class="p-8 border border-gray-100 rounded-2xl">
            <i class="fa-solid fa-plane-departure text-[#e31e24] text-3xl mb-6"></i>
            <h5 class="font-bold text-gray-900 mb-3 uppercase">Transit & Tracking</h5>
            <p class="text-sm text-gray-500 leading-relaxed">Most international orders arrive within 1-2 weeks. Once shipped, you'll receive a tracking number via the "My Account" page.</p>
          </div>
          <div class="p-8 border border-gray-100 rounded-2xl">
            <i class="fa-solid fa-circle-exclamation text-[#f2a900] text-3xl mb-6"></i>
            <h5 class="font-bold text-gray-900 mb-3 uppercase">Duties & Taxes</h5>
            <p class="text-sm text-gray-500 leading-relaxed">Your local customs agency may charge duties/taxes per local laws. Logic Auto Parts does not handle these external fees.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 bg-gray-50 flex items-center justify-center">
      <div class="container max-w-[1200px] mx-auto px-4">
        <div class="bg-[#212529] rounded-[24px] py-16 px-4 text-center shadow-2xl relative overflow-hidden">
          <div class="relative z-10 mx-auto max-w-4xl">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 tracking-tight uppercase">
              Ready to Experience the Difference?
            </h2>
            <p class="text-gray-400 text-lg mb-10 font-medium">
              Join over 50,000 satisfied European car owners globally.
            </p>
            <div class="flex justify-center w-full">
              <NuxtLink
                to="/shop"
                class="w-full max-w-[300px] bg-[#e31e24] hover:bg-[#bd171d] text-white font-black py-4 rounded-full flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-red-900/20"
              >
                <span class="uppercase tracking-widest">BROWSE BMW PARTS</span>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <page-footer />
  </div>
</template>

<script setup>
const shippingFeatures = [
  { title: '275,000+ Parts', icon: 'fa-solid fa-gears', desc: 'Perfected inventory and supply chain so your parts are available when you need them.' },
  { title: 'Same Day Shipping', icon: 'fa-solid fa-bolt', desc: 'Orders from stock are packed and ready to ship in less than 24 hours.' },
  { title: 'Seven Days A Week', icon: 'fa-solid fa-calendar-check', desc: 'Multiple daily pickups ensure your parts leave our hands as quickly as possible.' },
  { title: '5 Days or Less', icon: 'fa-solid fa-truck-fast', desc: 'Coast-to-coast hubs mean standard delivery reaches you in 3-5 business days.' }
];
</script>