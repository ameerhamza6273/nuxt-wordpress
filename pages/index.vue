<script setup>


const handleSearch = () => {
  console.log('Searching for parts...');
};
const categories = [
  { title: 'Brake Kits', image: 'Brake-Kits.png', link: '#' },
  { title: 'Engine Parts', image: 'Engine-Parts.png', link: '#' },
  { title: 'Oil Change Kits', image: 'Oil-Change-Kits.png', link: '#' },
  { title: 'Suspension', image: 'Suspension.png', link: '#' }
];

const benefits = [
  { 
    title: 'Lifetime Guarantee', 
    icon: 'fa-solid fa-shield-halved', 
    isFeatured: false, // Normal card
    desc: 'We provide an industry-leading lifetime replacement guarantee on every part we sell, ensuring your peace of mind.' 
  },
  { 
    title: 'Global Logistics', 
    icon: 'fa-solid fa-truck-fast', 
    isFeatured: true, // Exact Featured style from picture
    desc: 'With our optimized shipping routes, your premium auto parts reach your doorstep faster than the competition.' 
  },
  { 
    title: 'Technical Mastery', 
    icon: 'fa-solid fa-headset', 
    isFeatured: false, // Normal card
    desc: 'Connect with our expert team for precision part matching and technical advice tailored to your specific model.' 
  }
];
const blogPosts = [
  {
    title: "Brake Fluid Explained: What's Best For You?",
    category: "Maintenance",
    excerpt: "The topic of brake fluid is often overlooked, but it's critical for safety and performance in European cars.",
    image: "https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=800",
    link: "#"
  },
  {
    title: "How To Lower Your Car The Right Way",
    category: "Performance",
    excerpt: "Adding power is tricky, but your vehicle's ride height and center of gravity can be even more critical.",
    image: "https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800",
    link: "#"
  },
  {
    title: "What It Takes To Make A Great Exhaust",
    category: "Engineering",
    excerpt: "Engineering goes above and beyond the basics. A tour of our master shop reveals the precision behind the sound.",
    image: "https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=800",
    link: "#"
  }
];
const brands = [
  'BMW',
  'Audi',
  'Mercedes',
  'Volvo',
  'Porsche',
  'VW'
];
</script>


<template>
  <section>
   
    <article>
      <PageNavbar/>
      <TopBar />
      <HeroSection />
      
<section class="py-20 bg-white">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      
      <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div class="relative">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 uppercase tracking-tight">
            Shop by Category
          </h2>
          <div class="w-[60px] h-1 bg-[#f2a900] mt-3"></div>
          <p class="text-gray-500 mt-4 font-medium italic">Premium quality parts for your European vehicle.</p>
        </div>
        <NuxtLink to="/categories" class="px-8 py-2 border-2 border-gray-900 text-[12px] rounded font-black tracking-[2px] hover:bg-gray-900 hover:text-white transition-all duration-300 uppercase">
          View All Categories
        </NuxtLink>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="cat in categories" :key="cat.title" class="group h-full">
          
          <div class="bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col h-full 
                      transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] 
                      hover:-translate-y-3 hover:shadow-[0_22px_50px_rgba(0,0,0,0.08)] hover:border-[#f2a900]">
            
            <div class="h-[200px] bg-[#f8f9fa] flex items-center justify-center overflow-hidden">
              <NuxtImg 
                :src="cat.image" 
                :alt="cat.title"
                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
              />
            </div>
            
            <div class="p-6 text-center border-t border-gray-100 flex-1 flex flex-col justify-center bg-white transition-colors duration-500 group-hover:bg-white">
              <h5 class="text-[1.1rem] font-bold text-gray-900 mb-1 tracking-tight transition-colors duration-500">{{ cat.title }}</h5>
              
              <NuxtLink :to="cat.link" class="text-[#e31e24] text-[0.85rem] font-bold uppercase no-underline inline-flex items-center justify-center gap-1.5 mt-2 transition-all duration-300">
                <span>Shop {{ cat.shortName || 'Now' }}</span>
                <i class="fa-solid fa-chevron-right text-[0.7rem] transition-transform duration-300 group-hover:translate-x-1.5"></i>
              </NuxtLink>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

<section class="py-20 bg-gray-50 border-y border-gray-100">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      
      <div class="text-center mb-16">
        <span class="text-[11px] font-black text-[#e31e24] tracking-[3px] uppercase block mb-3">Our Promise</span>
        <h2 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tighter">Why Professionals Choose Logic</h2>
        <div class="w-16 h-1 bg-[#f2a900] mx-auto mt-6 rounded-full"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 text-center items-stretch">
        <div v-for="benefit in benefits" :key="benefit.title" 
             class="group bg-white p-10 md:p-12 rounded-[20px] shadow-sm border border-gray-100 flex flex-col h-full 
                    transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] 
                    hover:border-[#f2a900] hover:shadow-2xl hover:shadow-[#f2a900]/10 hover:-translate-y-3">
          
          <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-10 transition-all duration-500 bg-[#e31e24]/5 group-hover:bg-[#e31e24]">
            <i :class="[
                benefit.icon, 
                'text-3xl transition-all duration-500 text-[#e31e24] group-hover:text-white group-hover:scale-110'
              ]">
            </i>
          </div>

          <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-5 uppercase tracking-tight">{{ benefit.title }}</h3>
          <p class="text-gray-500 text-sm md:text-base leading-relaxed font-medium flex-1">
            {{ benefit.desc }}
          </p>
        </div>
      </div>
    </div>
  </section>
<section class="py-20 bg-white">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      
      <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div class="relative">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tight">
            Expert DIY & Tech Articles
          </h2>
          <div class="w-[60px] h-1 bg-[#f2a900] mt-3"></div>
          <p class="text-gray-500 mt-4 font-medium italic">Maintenance tips and performance guides from our engineers.</p>
        </div>
        <NuxtLink to="/blog" class="px-8 py-2 border-2 border-[#e31e24] text-[#e31e24] rounded text-[12px] font-black tracking-[2px] hover:bg-[#e31e24] hover:text-white transition-all duration-300 uppercase">
          Read All Posts
        </NuxtLink>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="post in blogPosts" :key="post.title" class="group cursor-pointer">
          <div class="h-full bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] hover:-translate-y-2 hover:shadow-2xl hover:shadow-gray-200">
            
            <div class="relative h-[220px] overflow-hidden">
              <NuxtImg 
                :src="post.image" 
                :alt="post.title"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-[#e31e24] text-[10px] font-black tracking-widest uppercase rounded-full shadow-sm">
                  {{ post.category }}
                </span>
              </div>
            </div>

            <div class="p-6 md:p-8">
              <h5 class="text-xl font-black text-gray-900 mb-3 leading-tight group-hover:text-[#e31e24] transition-colors duration-300">
                {{ post.title }}
              </h5>
              <p class="text-gray-500 text-sm leading-relaxed mb-6 font-medium line-clamp-2">
                {{ post.excerpt }}
              </p>
              
              <NuxtLink :to="post.link" class="inline-flex items-center gap-2 text-[11px] font-black text-gray-900 uppercase tracking-[2px] border-b-2 border-transparent hover:border-[#f2a900] transition-all pb-1">
                Read More <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-2"></i>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<section class="py-20 bg-gray-50">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      
      <div class="flex flex-col lg:flex-row bg-white rounded-[32px] overflow-hidden shadow-2xl shadow-gray-200/50 border border-gray-100">
        
        <div class="lg:w-1/2 relative min-h-[400px] overflow-hidden group">
          <NuxtImg 
            src="https://images.unsplash.com/photo-1502877338535-766e1452684a?q=80&w=1000"
            alt="Logic Auto Parts Warehouse"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
        </div>

        <div class="lg:w-1/2 p-10 flex flex-col justify-center">
          <span class="text-[#e31e24] font-black text-[11px] tracking-[4px] uppercase block mb-4">
            Established Expertise
          </span>
          
          <h2 class="text-3xl font-black text-gray-900 leading-tight uppercase tracking-tighter mb-3">
            The Logic Auto Parts Story
          </h2>
          
          <p class="text-gray-600 text-base md:text-md leading-relaxed font-medium mb-10">
            Driven by the pursuit of quality, Logic Auto Parts has evolved from a local specialist into a
            premier global distributor of Genuine, OE, and OEM parts. Our 100,000 sq. ft. distribution
            center holds over $10 Million in inventory, ready to ship same-day.
          </p>

          <div class="grid grid-cols-2 gap-8 mb-10">
            <div class="border-l-4 border-[#f2a900] pl-5 py-1">
              <h4 class="text-3xl font-black text-gray-900 tracking-tight">190,000+</h4>
              <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Quality Parts</p>
            </div>
            <div class="border-l-4 border-[#f2a900] pl-5 py-1">
              <h4 class="text-3xl font-black text-gray-900 tracking-tight">100%</h4>
              <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Genuine / OEM</p>
            </div>
          </div>

          <div>
            <NuxtLink to="/about" class="inline-block px-10 py-4 bg-gray-900 text-white text-[11px] font-black tracking-[2px] uppercase rounded-lg hover:bg-[#e31e24] hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-gray-900/10">
              Learn More About Us
            </NuxtLink>
          </div>
        </div>

      </div>
    </div>
  </section>
<section class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-[1300px] mx-auto px-4 md:px-6">
      
      <div class="text-center mb-12">
        <span class="text-[11px] font-black text-[#e31e24] tracking-[3px] uppercase block mb-2">
          Specialist Inventory
        </span>
        <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">
          Supported Manufacturers
        </h2>
        <div class="w-10 h-1 bg-[#f2a900] mx-auto mt-4 rounded-full"></div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
        <div v-for="brand in brands" :key="brand" 
             class="group cursor-pointer bg-[#fafafa] border border-gray-100 rounded-xl p-5 
                    text-center transition-all duration-400 ease-out
                    hover:bg-gray-900 hover:border-gray-900 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-gray-900/20">
          
          <p class="text-sm md:text-base font-black text-gray-600 uppercase tracking-wider 
                    transition-colors duration-400 group-hover:text-[#f2a900]">
            {{ brand }} <span class="block md:inline text-[10px] opacity-60">Parts</span>
          </p>
          
        </div>
      </div>
    </div>
  </section>
  <section class="relative py-24 md:py-32 overflow-hidden border-b-[5px] border-[#f2a900]">
    
    <div class="absolute inset-0 z-0">
      <NuxtImg 
        src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=1600" 
        class="w-full h-full object-cover"
        alt="Automotive Background"
      />
      <div class="absolute inset-0 bg-gradient-to-br from-[#b81818]/90 via-[#8b0000]/95 to-black/80"></div>
    </div>

    <div class="max-w-[1300px] mx-auto px-4 md:px-6 relative z-10 text-center text-white">
      <div class="flex justify-center">
        <div class="max-w-4xl">
          
          <h2 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tighter drop-shadow-lg">
            Never Pay For The <br class="hidden md:block" /> Same Part Twice
          </h2>
          
          <p class="text-lg md:text-xl font-bold mb-10 opacity-90 leading-relaxed max-w-3xl mx-auto italic">
            Experience the ultimate peace of mind with our Lifetime Replacement Guarantee. 
            From brake pads to engine components—we've got you covered.
          </p>

          <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-4 md:gap-6">
            
            <NuxtLink to="/shop" class="px-10 py-5 bg-white text-gray-900 font-black text-xs md:text-sm tracking-[2px] uppercase rounded transition-all duration-300 hover:bg-[#f2a900] hover:scale-105 shadow-xl inline-flex items-center justify-center gap-2">
              <i class="fa-solid fa-cart-shopping"></i> Start Shopping
            </NuxtLink>

            <NuxtLink to="/contact" class="px-10 py-5 border-2 border-white text-white font-black text-xs md:text-sm tracking-[2px] uppercase rounded transition-all duration-300 hover:bg-white hover:text-[#b81818] inline-flex items-center justify-center gap-2">
              <i class="fa-solid fa-phone"></i> Contact Expert
            </NuxtLink>

          </div>

        </div>
      </div>
    </div>

    <div class="absolute -top-1/2 -right-10 w-[400px] h-[400px] bg-[#e31e24] blur-[150px] opacity-20 rounded-full z-0"></div>
  </section>


      <PageFooter/>
    </article>
  </section>
</template>

<style scoped>
/* Smooth font rendering */
h1 {
  text-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
/* Parallax-like feel (Fixed background attachment) */
section {
  background-attachment: fixed;
}
/* Custom form focus logic if needed beyond Tailwind */
select:focus {
  background-color: white;
}
</style>