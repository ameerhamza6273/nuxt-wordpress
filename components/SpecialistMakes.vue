<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

// 🔴 APNA WORDPRESS URL YAHAN CHECK KARLEIN
const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const router = useRouter()
const brands = ref([])
const loading = ref(false)

// 🟢 Database se saare unique Makes dynamic uthane ka function
const fetchAllDatabaseBrands = async () => {
  loading.value = true
  try {
    // Hamne PHP mein 'pa_make' ko bina parent ke global fetch banaya hua hai
    const apiUrl = `${WP_URL}/wp-json/custom/v2/vehicle?slug=pa_make`
    const data = await $fetch(apiUrl, { method: 'GET' })
    
    if (data && Array.isArray(data)) {
      // Hamein sirf name chahiye array mein render karne ke liye
      brands.value = data.map(item => item.name || item)
    }
  } catch (error) {
    console.error("Error fetching dynamic brands for section:", error)
    // Fallback array agar connection temporary fail ho
    brands.value = ['BMW', 'Mercedes-Benz', 'Ford', 'Toyota', 'Nissan']
  } finally {
    loading.value = false
  }
}

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
  fetchAllDatabaseBrands()
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