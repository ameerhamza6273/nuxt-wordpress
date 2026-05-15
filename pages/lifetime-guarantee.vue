<template>
    <div>
        <page-navbar />
   
    <main class="bg-white">
                <TopBar />

        <section class="relative py-24 text-center text-white overflow-hidden bg-black">
            <div class="absolute inset-0 z-0">
                <NuxtImg src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=1600"
                    class="w-full h-full object-cover opacity-40" />
                <div class="absolute inset-0 bg-black/40"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <span class="text-[#e31e24] font-black text-sm tracking-[4px] uppercase block mb-4">
                    Our Commitment
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tighter uppercase">
                    Lifetime Replacement <span class="text-[#e31e24]">Guarantee</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    All parts purchased from Logic Auto Parts are guaranteed for the life of your ownership.
                    No fine print, just a promise of quality that lasts.
                </p>

            </div>
        </section>

        <section class="py-24 relative z-20 bg-white">
    <div class="container max-w-[1200px] mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">
                How It <span class="text-[#e31e24]">Works</span>
            </h2>
            <div class="w-12 h-1 bg-[#e31e24] mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group relative bg-white p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 hover:border-[#e31e24] transition-all duration-500 overflow-hidden">
                <div class="absolute right-0 -top-4 text-8xl font-black text-gray-50 group-hover:text-red-50/50 transition-colors duration-500 italic pointer-events-none">01</div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#e31e24] group-hover:rotate-[10deg] transition-all duration-500 shadow-lg">
                        <i class="fa-solid fa-cart-shopping text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase mb-4 tracking-tight">Buy A Part</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium">
                        Purchase any part through our store. All parts are inspected and verified for quality before they reach your doorstep.
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1.5 bg-[#e31e24] group-hover:w-full transition-all duration-700"></div>
            </div>

            <div class="group relative bg-white p-10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 hover:border-[#e31e24] transition-all duration-500 overflow-hidden">
                <div class="absolute right-0 -top-4 text-8xl font-black text-gray-50 group-hover:text-red-50/50 transition-colors duration-500 italic pointer-events-none">02</div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#e31e24] group-hover:rotate-[10deg] transition-all duration-500 shadow-lg">
                        <i class="fa-solid fa-screwdriver-wrench text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase mb-4 tracking-tight">Use It</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium">
                        Install it on your daily driver or track car. We stand by our high-quality reputable brands regardless of how you drive.
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 w-0 h-1.5 bg-[#e31e24] group-hover:w-full transition-all duration-700"></div>
            </div>

            <div class="group relative bg-[#1a1a1a] p-10 rounded-3xl shadow-2xl border border-[#e31e24] transition-all duration-500 overflow-hidden">
                <div class="absolute right-0 -top-4 text-8xl font-black text-white/5 italic pointer-events-none">03</div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-[#e31e24] rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-red-900/20">
                        <i class="fa-solid fa-rotate-left text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white uppercase mb-4 tracking-tight">Replace It</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-medium">
                        When it wears out, buy a replacement first. Send the old one back with our returns form, and we'll issue your credit.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-24 bg-[#f8f9fa] border-y border-gray-100">
    <div class="container max-w-[1200px] mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">

            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                <div class="inline-flex items-center gap-3 mb-8">
                    <div class="w-10 h-1 bg-[#e31e24]"></div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">
                        The Fine <span class="text-[#e31e24]">Rules</span>
                    </h2>
                </div>
                
                <ul class="space-y-8">
                    <li class="flex gap-5 group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-50 flex items-center justify-center group-hover:bg-[#e31e24] transition-colors duration-300">
                            <i class="fa-solid fa-check text-[#e31e24] group-hover:text-white text-sm"></i>
                        </div>
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed pt-1">
                            Replacement orders must be for the <span class="font-bold text-gray-900 border-b-2 border-red-100">same item</span> originally purchased.
                        </p>
                    </li>
                    <li class="flex gap-5 group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-50 flex items-center justify-center group-hover:bg-[#e31e24] transition-colors duration-300">
                            <i class="fa-solid fa-check text-[#e31e24] group-hover:text-white text-sm"></i>
                        </div>
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed pt-1">
                            A fully completed <span class="font-bold text-gray-900 border-b-2 border-red-100">Returns Form</span> is mandatory for all claims.
                        </p>
                    </li>
                    <li class="flex gap-5 group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-50 flex items-center justify-center group-hover:bg-[#e31e24] transition-colors duration-300">
                            <i class="fa-solid fa-check text-[#e31e24] group-hover:text-white text-sm"></i>
                        </div>
                        <p class="text-gray-600 text-sm md:text-base leading-relaxed pt-1">
                            Credit is issued as <span class="font-bold text-gray-900 border-b-2 border-red-100">Store Credit</span>, visible in your account immediately.
                        </p>
                    </li>
                </ul>
            </div>

            <div class="bg-[#1a1a1a] p-8 md:p-12 rounded-[2.5rem] shadow-2xl relative overflow-hidden flex flex-col justify-center">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#e31e24] opacity-10 blur-[60px]"></div>
                
                <h4 class="text-[#f2a900] font-black uppercase tracking-[3px] text-xs mb-8 flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full border border-[#f2a900] flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </span>
                    Unacceptable Returns
                </h4>

                <div class="grid grid-cols-1 gap-6">
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <h5 class="text-white font-bold text-xs uppercase mb-2">Non-Logic Parts</h5>
                        <p class="text-gray-400 text-xs leading-relaxed uppercase tracking-wider">Items not originally purchased from us.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <h5 class="text-white font-bold text-xs uppercase mb-2">One-Time Use</h5>
                        <p class="text-gray-400 text-xs leading-relaxed uppercase tracking-wider">Fluids, additives, and cleaners are ineligible.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <h5 class="text-white font-bold text-xs uppercase mb-2">System Abuse</h5>
                        <p class="text-gray-400 text-xs leading-relaxed uppercase tracking-wider">Excessively high return rates or proactive returns.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <h5 class="text-white font-bold text-xs uppercase mb-2">Merchandise</h5>
                        <p class="text-gray-400 text-xs leading-relaxed uppercase tracking-wider">Branded apparel is ineligible for LRG.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

        <section class="py-20 bg-white">
            <div class="container max-w-[900px] mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">FAQ'S</h2>
                    <p class="text-gray-500 mt-4 uppercase text-xs tracking-[3px]">Everything you need to know</p>
                </div>

                <div class="space-y-4">
                        <div v-for="(faq, index) in faqs" :key="index"
                            class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                            <button @click="toggleFaq(index)"
                                class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                                <span class="font-bold text-gray-900 uppercase tracking-tight text-sm md:text-base">
                                    {{ faq.q }}
                                </span>
                                <i
                                    :class="['fa-solid fa-chevron-down text-[#e31e24] transition-transform duration-300', activeIndex === index ? 'rotate-180' : '']"></i>
                            </button>

                            <div v-show="activeIndex === index"
                                class="p-6 bg-gray-50 border-t border-gray-100 text-gray-600 text-sm leading-relaxed italic">
                                {{ faq.a }}
                            </div>
                        </div>
                    </div>
            </div>
        </section>

        <section class="py-20 bg-[#f3f4f6] relative overflow-hidden">
  <div class="absolute top-0 right-0 w-96 h-96 bg-[#e31e24]/5 blur-[120px] rounded-full -mr-48 -mt-48"></div>
  
  <div class="container max-w-[1200px] mx-auto px-4 relative z-10">
    <div class="text-center mb-10">
      <span class="text-[#e31e24] font-black text-xs tracking-[5px] uppercase block mb-3">Testimonials</span>
      <h2 class="text-3xl md:text-5xl font-black text-gray-900  tracking-tighter">What Our Drivers Say</h2>
      <div class="w-12 h-1 bg-[#e31e24] mx-auto mt-6"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      
      <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 group relative">
        <div class="absolute top-8 right-10 text-gray-100 group-hover:text-[#e31e24]/10 transition-colors">
          <i class="fa-solid fa-quote-right text-6xl"></i>
        </div>
        
        <div class="flex gap-1 text-[#f2a900] mb-6">
          <i class="fa-solid fa-star text-xs" v-for="i in 5" :key="i"></i>
        </div>

        <p class="text-gray-600 font-medium italic leading-relaxed mb-10 relative z-10">
          "The quality is first-rate and many of the parts they stock are original equipment. Their return policy is unbelievable. I use Logic Auto Parts first for all my BMW builds."
        </p>

        <div class="flex items-center gap-4 border-t border-gray-50 pt-6">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md">
            <img src="https://ui-avatars.com/api/?name=Frederick+J&background=e31e24&color=fff" alt="User">
          </div>
          <div>
            <h6 class="font-black text-gray-900 text-sm uppercase tracking-wider">Frederick J.</h6>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Verified Customer</span>
          </div>
        </div>
      </div>

      <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 group relative">
        <div class="absolute top-8 right-10 text-gray-100 group-hover:text-[#e31e24]/10 transition-colors">
          <i class="fa-solid fa-quote-right text-6xl"></i>
        </div>
        
        <div class="flex gap-1 text-[#f2a900] mb-6">
          <i class="fa-solid fa-star text-xs" v-for="i in 5" :key="i"></i>
        </div>

        <p class="text-gray-600 font-medium italic leading-relaxed mb-10 relative z-10">
          "Fast shipping and the Lifetime Replacement Guarantee is a game changer for track days. No one else in the industry stands behind their parts like these guys do."
        </p>

        <div class="flex items-center gap-4 border-t border-gray-50 pt-6">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md">
            <img src="https://ui-avatars.com/api/?name=Michael+S&background=1a1a1a&color=fff" alt="User">
          </div>
          <div>
            <h6 class="font-black text-gray-900 text-sm uppercase tracking-wider">Michael S.</h6>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Performance Driver</span>
          </div>
        </div>
      </div>

      <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 group relative">
        <div class="absolute top-8 right-10 text-gray-100 group-hover:text-[#e31e24]/10 transition-colors">
          <i class="fa-solid fa-quote-right text-6xl"></i>
        </div>
        
        <div class="flex gap-1 text-[#f2a900] mb-6">
          <i class="fa-solid fa-star text-xs" v-for="i in 5" :key="i"></i>
        </div>

        <p class="text-gray-600 font-medium italic leading-relaxed mb-10 relative z-10">
          "Customer service is top-notch. They helped me find the exact OEM cooling kit for my Audi. The packaging was secure and the parts were pristine."
        </p>

        <div class="flex items-center gap-4 border-t border-gray-50 pt-6">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md">
            <img src="https://ui-avatars.com/api/?name=Sarah+K&background=e31e24&color=fff" alt="User">
          </div>
          <div>
            <h6 class="font-black text-gray-900 text-sm uppercase tracking-wider">Sarah K.</h6>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">Audi Enthusiast</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<section class="py-20 bg-white">
    <div class="container max-w-[1200px] mx-auto px-4">
        <div class="relative bg-[#1a1a1a] rounded-[2.5rem] p-10 md:p-20 overflow-hidden shadow-2xl group">
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#e31e24] opacity-10 blur-[120px] -mr-32 -mt-32 transition-opacity duration-700 group-hover:opacity-20"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-black opacity-40 blur-[80px] -ml-32 -mb-32"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                
                <div class="text-center md:text-left max-w-xl">
                    <div class="inline-flex items-center gap-3 mb-6">
                        <span class="w-8 h-[2px] bg-[#e31e24]"></span>
                        <span class="text-[#e31e24] font-black text-xs tracking-[4px] uppercase">Ready to Upgrade?</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter leading-none mb-6">
                        Shop parts <br> <span class="text-gray-400">for your car</span>
                    </h2>
                    
                    <p class="text-gray-400 text-lg font-medium leading-relaxed">
                        Experience the industry's most reliable parts with our <span class="text-white border-b border-[#e31e24]">Lifetime Replacement Guarantee</span>. Built for performance, backed by Logic.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <a href="/shop" class="group/btn relative bg-[#e31e24] hover:bg-white text-white hover:text-black px-12 py-6 rounded-full font-black uppercase tracking-[3px] text-sm transition-all duration-500 flex items-center gap-4 shadow-[0_15px_30px_rgba(227,30,36,0.3)] hover:shadow-white/10">
                        Explore Catalog
                        <i class="fa-solid fa-arrow-right group-hover/btn:translate-x-2 transition-transform duration-300"></i>
                    </a>
                    <span class="text-[10px] text-gray-500 font-black uppercase tracking-[2px]">Over 50,000+ Parts in Stock</span>
                </div>

            </div>

            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#e31e24] to-transparent opacity-30"></div>
        </div>
    </div>
</section>
    </main>
    <page-footer />
     </div>
</template>

<script setup>
const activeIndex = ref(0); // Pehla FAQ open rahega

const toggleFaq = (index) => {
    activeIndex.value = activeIndex.value === index ? null : index;
};

const faqs = [
    { q: "Is this real?", a: "Absolutely. We've built our reputation on this promise. If a part you bought from us wears out, we replace it." },
    { q: "Does this include every part you sell?", a: "Yes, with very few exceptions like one-time use consumables (fluids, cleaners)." },
    { q: "Do you cover labor costs?", a: "Our guarantee covers the replacement of the part itself. We do not cover labor, towing, or incidental charges." },
    { q: "Does this apply if I track my car?", a: "Yes! Whether it's a daily driver or a dedicated track machine, the guarantee stands." },
    { q: "What about return shipping?", a: "Customers are responsible for the cost of shipping the old part back to our facility." }
];
</script>