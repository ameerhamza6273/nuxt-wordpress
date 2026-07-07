<script setup>
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { allMakes, allMakesLoaded, ensureAllMakes } from '~/composables/useVehicleData.js'

const router = useRouter()

const loading = computed(() => !allMakesLoaded.value)

// 🟢 Hamein sirf name chahiye array mein render karne ke liye
const brands = computed(() => allMakes.value.map(item => item.name || item))

// 🟢 Card click hone par product page par filters ke sath bhejna
const handleBrandClick = (brandName) => {
  router.push({
    path: '/products',
    query: {
      make: brandName, // E.g., ?make=BMW ya ?make=Mercedes-Benz
      page: 1
    }
  })
}

onMounted(() => {
  ensureAllMakes()
})
</script>

<template>
    <section class="py-20 bg-white border-t border-gray-100">
        <div class="max-w-[1300px] mx-auto px-4 md:px-6">

            <div class="text-center mb-12">
                <span class="text-[11px] font-black text-[#e31e24] tracking-[3px] uppercase block mb-2">
                    Specialist Inventory
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">
                    Supported Brands
                </h2>
                <p v-if="loading" class="text-xs text-gray-400 mt-2 animate-pulse">Syncing dynamic inventory...</p>
                <div class="w-10 h-1 bg-[#f2a900] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
                <div v-for="brand in brands" :key="brand"
                    @click="handleBrandClick(brand)"
                    class="group cursor-pointer bg-[#fafafa] border border-gray-100 rounded-xl p-5 
                    text-center transition-all duration-400 ease-out
                    hover:bg-gray-900 hover:border-gray-900 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-gray-900/20">

                    <p class="text-sm md:text-base font-black text-gray-600 uppercase tracking-wider 
                    transition-colors duration-400 group-hover:text-[#f2a900]">
                        {{ brand }} <span class="block md:inline text-[10px] opacity-60 ml-1">Parts</span>
                    </p>

                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Smooth font rendering */
h1 {
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
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