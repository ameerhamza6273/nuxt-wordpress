<template>
  <section>
    <article>
      <PageNavbar />

      <main class="overflow-hidden bg-white">
        <TopBar />
        <section class="relative py-24 text-center text-white bg-black">
          <div class="absolute inset-0 z-0 opacity-40">
            <NuxtImg 
              src="https://images.unsplash.com/photo-1560179707-f14e90ef3623?q=80&w=1600" 
              class="w-full h-full object-cover"
            />
          </div>
          <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-black/80 z-0"></div>
          
          <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Contact Our Specialists</h1>
            <p class="text-md text-gray-300  opacity-90">
              Need help finding a specific part? Our technical team is ready to assist.
            </p>
          </div>
        </section>

        <section class="py-16 relative z-20">
          <div class="container max-w-[1200px] mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
              
              <div class="lg:col-span-7 bg-white rounded-[15px] p-8 md:p-12 shadow-2xl shadow">
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Send us a Message</h3>
                
                <form @submit.prevent class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                      <input type="text" placeholder="John Doe" class="w-full bg-[#fcfcfc] border border-gray-100 rounded-lg p-4 text-sm focus:border-[#e31e24] focus:bg-white focus:outline-none transition-all duration-300">
                    </div>
                    <div>
                      <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                      <input type="email" placeholder="john@example.com" class="w-full bg-[#fcfcfc] border border-gray-100 rounded-lg p-4 text-sm focus:border-[#e31e24] focus:bg-white focus:outline-none transition-all duration-300">
                    </div>
                  </div>

                  <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Vehicle VIN / Model</label>
                    <input type="text" placeholder="e.g. BMW E46 M3 / 17-digit VIN" class="w-full bg-[#fcfcfc] border border-gray-100 rounded-lg p-4 text-sm focus:border-[#e31e24] focus:bg-white focus:outline-none transition-all duration-300">
                  </div>

                  <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">How can we help?</label>
                    <textarea rows="5" placeholder="Tell us about the parts you need..." class="w-full bg-[#fcfcfc] border border-gray-100 rounded-lg p-4 text-sm focus:border-[#e31e24] focus:bg-white focus:outline-none transition-all duration-300 resize-none"></textarea>
                  </div>

                  <button class="w-full bg-[#e31e24] hover:bg-[#bd171d] text-white font-bold py-4 rounded-lg shadow-lg shadow-red-900/20 transition-all active:scale-[0.98] uppercase tracking-widest text-sm">
                    Submit Inquiry
                  </button>
                </form>
              </div>

              <div class="lg:col-span-5 lg:pl-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Direct Support</h3>
                
                <div class="space-y-6">
                  <div v-for="(info, index) in contactInfo" :key="index" 
                       class="flex items-center gap-5 p-5 bg-gray-50 rounded-xl hover:bg-white hover:shadow-xl hover:shadow-gray-100 hover:translate-x-2 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-[#e31e24] text-white rounded-xl flex items-center justify-center text-xl shrink-0 shadow-lg shadow-red-900/20">
                      <i :class="info.icon"></i>
                    </div>
                    <div>
                      <h6 class="font-bold text-gray-900 mb-0.5">{{ info.title }}</h6>
                      <p class="text-gray-500 text-sm">{{ info.value }}</p>
                    </div>
                  </div>

                  <div class="mt-10 p-6 rounded-2xl border-2 border-dashed border-gray-100">
                    <h5 class="font-bold flex items-center gap-3 text-gray-900 mb-4">
                      <i class="fa-solid fa-clock text-[#e31e24]"></i> Business Hours
                    </h5>
                    <ul class="space-y-3">
                      <li class="flex justify-between text-sm text-gray-600">
                        <span>Mon - Fri:</span> <span class="font-bold">9:00 AM - 6:00 PM</span>
                      </li>
                      <li class="flex justify-between text-sm text-gray-600">
                        <span>Saturday:</span> <span class="font-bold">10:00 AM - 4:00 PM</span>
                      </li>
                      <li class="flex justify-between text-sm text-[#e31e24] font-bold">
                        <span>Sunday:</span> <span>Closed</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>

        <section class="container max-w-[1200px] mx-auto px-4 py-16">
          <div class="h-[400px] rounded-2xl overflow-hidden shadow-sm grayscale hover:grayscale-0 transition-all duration-700">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2426.6342177395!2d-2.1462089!3d52.5854746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870811e7a50616b%3A0x67310f88a385f782!2sWolverhampton%2C%20UK!5e0!3m2!1sen!2s!4v1715760000000!5m2!1sen!2s" 
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
            ></iframe>
          </div>
        </section>
      </main>

      <PageFooter />
    </article>
  </section>
</template>

<script setup>
const contactInfo = [
  { icon: 'fa-solid fa-phone-volume', title: 'Call Technical Support', value: '+1 (800) LOGIC-AUTO' },
  { icon: 'fa-solid fa-envelope-open-text', title: 'Email Inquiries', value: 'support@logicautoparts.com' },
  { icon: 'fa-solid fa-location-dot', title: 'Distribution Center', value: '123 Logistics Way, Wolverhampton, UK' }
]
</script>

