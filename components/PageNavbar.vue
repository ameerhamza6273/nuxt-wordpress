<template>
  <header class="w-full z-50 bg-white sticky top-0 shadow-sm">
    <nav class="border-b border-gray-100 py-2">
      <div class="max-w-[1300px] mx-auto px-4 md:px-6 flex items-center justify-between">

        <NuxtLink to="/" class="shrink-0">
          <NuxtImg src="/logic-auto-parts-website-logo.jpeg" alt="Logic Auto Parts" class="h-10 md:h-16 w-auto" />
        </NuxtLink>

        <div class="lg:hidden">
          <a href="tel:+18005550199"
            class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200 transition-active active:scale-95">
            <i class="fa-solid fa-phone text-[#e31e24] text-xs"></i>
            <span class="text-[12px] font-bold text-gray-900 tracking-tight">(800) 555-0199</span>
          </a>
        </div>

        <ul class="hidden lg:flex items-center space-x-6 mx-auto h-full">
          <li v-for="item in brandsData" :key="item.brand" class="relative group py-4">
            <div class="cursor-pointer text-sm font-bold text-gray-900 group-hover:text-[#e31e24] transition-colors uppercase tracking-tight flex items-center gap-1 selective-none select-none">
              {{ item.brand }}
              <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-[#e31e24] transition-transform group-hover:rotate-180"></i>
            </div>

            <div v-if="item.categories && item.categories.length > 0" class="absolute left-[70px] -translate-x-1/2 top-full pt-2 hidden group-hover:block w-48 z-50 animate-in fade-in slide-in-from-top-2">
              <div class="bg-white shadow-2xl rounded-lg border border-gray-100 overflow-hidden py-3">
                <div class="max-h-[350px] overflow-y-auto px-2 space-y-0.5">
                  <NuxtLink v-for="modelName in item.categories" :key="modelName"
                    :to="{ path: '/products', query: { make: item.brand, model: modelName, page: 1 } }"
                    class="block px-3 py-2 text-[13px] font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] rounded-md transition-colors">
                    {{ modelName }}
                  </NuxtLink>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <div class="flex items-center gap-3">
          <div class="relative hidden xl:block w-[250px]">
            <input type="text" v-model="searchQuery" placeholder="Search By SKU / Part Number"
              @input="handleSearchInput" @focus="handleSearchInput" @blur="handleSearchBlur"
              @keydown.enter.prevent="handleSearchSubmit" @keydown.esc="showSuggestions = false"
              class="w-full bg-[#fcfcfc] border-2 border-gray-100 rounded-lg py-2 pl-4 pr-10 text-[12px] focus:border-[#f2a900] outline-none" />
            <button type="button" @click="handleSearchSubmit"
              class="absolute right-1 top-1 bottom-1 bg-[#e31e24] text-white px-3 rounded-md">
              <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>

            <div v-if="showSuggestions"
              class="absolute left-0 right-0 top-full mt-2 bg-white shadow-2xl rounded-lg border border-gray-100 overflow-hidden z-50 py-1">
              <template v-if="searchLoading">
                <p class="px-4 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wide">Searching...</p>
              </template>
              <template v-else-if="searchSuggestions.length > 0">
                <button v-for="item in searchSuggestions" :key="item.id" type="button"
                  @mousedown.prevent="selectSuggestion(item)"
                  class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] transition-colors flex items-center gap-2">
                  <i class="fa-solid fa-gear text-[10px] text-gray-300"></i>
                  <span class="truncate">{{ item.title }}</span>
                  <span class="ml-auto text-[10px] text-gray-400 shrink-0">{{ item.sku }}</span>
                </button>
              </template>
              <p v-else class="px-4 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wide">
                No matching part found - press Enter to search anyway
              </p>
            </div>
          </div>

          <div class="hidden md:block relative group">
            <button
              class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-2 text-[12px] font-bold text-gray-800 group-hover:border-[#f2a900] transition-all">
              <i class="fa-solid fa-user-circle text-lg text-gray-400 group-hover:text-[#f2a900]"></i>
              <span>{{ isLoggedIn ? userName.toUpperCase() : 'MY ACCOUNT' }}</span>
              <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform group-hover:rotate-180"></i>
            </button>

            <div
              class="absolute right-0 top-full pt-2 hidden group-hover:block w-52 z-50 animate-in fade-in slide-in-from-top-1">
              <div class="bg-white shadow-xl rounded-lg border border-gray-100 overflow-hidden py-1">
                <template v-if="!isLoggedIn">
                  <NuxtLink v-for="item in guestLinks" :key="item.label" :to="item.to"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] transition-colors">
                    <i :class="[item.icon, 'text-gray-400 w-5']"></i>
                    {{ item.label }}
                  </NuxtLink>
                </template>

                <template v-else>
                  <NuxtLink to="/orders" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] transition-colors">
                    <i class="fa-solid fa-box text-gray-400 w-5"></i> My Orders
                  </NuxtLink>
                  <button @click="handleLogout" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-right-from-bracket text-red-400 w-5"></i> Log Out
                  </button>
                </template>

                <NuxtLink to="/admin" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] transition-colors border-t border-gray-100">
                  <i class="fa-solid fa-user-shield text-gray-400 w-5"></i> Admin
                </NuxtLink>
              </div>
            </div>
          </div>

          <div @click="handleCartClick"
            class="relative cursor-pointer group p-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-all duration-300">
            <i class="fa-solid fa-cart-shopping text-gray-700 text-sm group-hover:text-[#e31e24] transition-colors"></i>
            <span v-if="totalCartItems > 0"
              class="absolute -top-2 -right-2 bg-[#e31e24] text-white rounded-full min-w-[18px] h-[18px] text-[10px] font-black flex items-center justify-center px-1 shadow-sm">
              {{ totalCartItems }}
            </span>
          </div>

          <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden text-2xl text-gray-800 p-1">
            <i :class="isMobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars-staggered'"></i>
          </button>
        </div>
      </div>
    </nav>

    <Transition name="slide">
      <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[60] lg:hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="isMobileMenuOpen = false"></div>
        <div class="absolute right-0 top-0 bottom-0 w-[280px] bg-white flex flex-col shadow-2xl">

          <div class="p-5 border-b flex justify-between items-center bg-gray-50">
            <span class="font-black text-[10px] tracking-[3px] uppercase text-gray-500">Navigation</span>
            <button @click="isMobileMenuOpen = false" class="text-3xl text-gray-400">&times;</button>
          </div>

          <div class="overflow-y-auto flex-1">
            <ul class="py-1">
              <li v-for="item in brandsData" :key="item.brand" class="border-b border-gray-50">
                <button @click="toggleMobileBrand(item.brand)"
                  class="w-full flex items-center justify-between px-6 py-4 text-sm font-black hover:bg-gray-50 uppercase text-gray-900 transition-colors">
                  <span>{{ item.brand }}</span>
                  <i :class="['fa-solid fa-chevron-down text-xs transition-transform duration-300', activeMobileBrand === item.brand ? 'rotate-180 text-[#e31e24]' : 'text-gray-400']"></i>
                </button>
                
                <div v-show="activeMobileBrand === item.brand && item.categories && item.categories.length > 0" class="bg-gray-50 border-t border-gray-100/50 transition-all duration-300">
                  <NuxtLink v-for="modelName in item.categories" :key="modelName"
                    :to="{ path: '/products', query: { make: item.brand, model: modelName, page: 1 } }"
                    class="block pl-10 pr-6 py-3 text-[13px] font-bold text-gray-600 hover:text-[#e31e24] transition-colors border-b border-gray-100 last:border-0">
                    {{ modelName }}
                  </NuxtLink>
                </div>
              </li>
            </ul>

            <div class="mt-4 border-t-4 border-gray-50">
              <button @click="isAccountDropdownOpen = !isAccountDropdownOpen"
                class="w-full flex items-center justify-between px-6 py-5 text-sm font-black text-gray-900 uppercase transition-colors hover:bg-gray-50">
                <div class="flex items-center gap-3">
                  <i class="fa-solid fa-user-circle text-lg text-[#e31e24]"></i>
                  <span>{{ isLoggedIn ? userName : 'My Account' }}</span>
                </div>
                <i :class="['fa-solid fa-chevron-down text-xs transition-transform duration-300', isAccountDropdownOpen ? 'rotate-180' : '']"></i>
              </button>

              <div v-show="isAccountDropdownOpen" class="bg-gray-50 overflow-hidden transition-all duration-300">
                <template v-if="!isLoggedIn">
                  <NuxtLink v-for="item in guestLinks" :key="item.label" :to="item.to"
                    class="flex items-center gap-4 px-10 py-4 text-xs font-black border-b border-gray-100 last:border-0 hover:text-[#e31e24] uppercase transition-colors">
                    <i :class="[item.icon, 'text-[#e31e24] w-5']"></i>
                    {{ item.label }}
                  </NuxtLink>
                </template>

                <template v-else>
                  <NuxtLink to="/orders" class="flex items-center gap-4 px-10 py-4 text-xs font-black border-b border-gray-100 hover:text-[#e31e24] uppercase transition-colors">
                    <i class="fa-solid fa-box text-[#e31e24] w-5"></i> My Orders
                  </NuxtLink>
                  <button @click="handleLogout" class="w-full text-left flex items-center gap-4 px-10 py-4 text-xs font-black text-red-600 uppercase transition-colors">
                    <i class="fa-solid fa-right-from-bracket text-red-500 w-5"></i> Log Out
                  </button>
                </template>

                <NuxtLink to="/admin" class="flex items-center gap-4 px-10 py-4 text-xs font-black border-t border-gray-100 hover:text-[#e31e24] uppercase transition-colors">
                  <i class="fa-solid fa-user-shield text-[#e31e24] w-5"></i> Admin
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { navigateTo } from '#app'
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { cartItems } from '~/composables/useCart.js'
import { navBrandsData, ensureNavMenu, WP_URL } from '~/composables/useVehicleData.js'
import { showToast } from '~/composables/useToast.js'

const isMobileMenuOpen = ref(false)
const isAccountDropdownOpen = ref(false)
const activeMobileBrand = ref(null)

const searchQuery = ref('')
const showSuggestions = ref(false)

// 🟢 Real SKU / part-number search - the client confirmed this is the storefront's
// primary, fastest search input (vehicle Year/Make/Model search stays in HeroSection
// as a separate flow; manufacturer-brand logos in the footer are a secondary filter).
const searchSuggestions = ref([])
const searchLoading = ref(false)
let searchDebounceTimer = null

const handleSearchInput = () => {
  showSuggestions.value = searchQuery.value.trim().length > 0
  if (searchDebounceTimer) clearTimeout(searchDebounceTimer)

  const q = searchQuery.value.trim()
  if (!q) {
    searchSuggestions.value = []
    searchLoading.value = false
    return
  }

  searchDebounceTimer = setTimeout(async () => {
    searchLoading.value = true
    try {
      const res = await $fetch(`${WP_URL}/wp-json/custom/v1/product-search`, {
        method: 'GET',
        params: { q, page: 1, per_page: 6 }
      })
      searchSuggestions.value = Array.isArray(res?.data) ? res.data : []
    } catch (err) {
      console.error('Product search suggestion fetch failed:', err)
      searchSuggestions.value = []
    } finally {
      searchLoading.value = false
    }
  }, 300)
}

const handleSearchBlur = () => {
  // Chhota delay taake suggestion button ka click blur se pehle register ho jaye
  setTimeout(() => { showSuggestions.value = false }, 150)
}

// Suggestion is a specific product match - clicking it goes straight to that product.
const selectSuggestion = (product) => {
  searchQuery.value = ''
  showSuggestions.value = false
  navigateTo(`/product/${product.id}`)
}

const handleSearchSubmit = () => {
  const query = searchQuery.value.trim()
  if (!query) {
    showToast('Please enter a SKU or part number to search.', 'error')
    return
  }
  showSuggestions.value = false
  navigateTo({ path: '/products', query: { q: query, page: 1 } })
}

const isLoggedIn = ref(false)
const userName = ref('')

// 🟢 Fallback computed logic: Navbar empty na lage jab tak API hit clear na kare
const brandsData = computed(() => {
  if (navBrandsData.value && navBrandsData.value.length > 0) {
    return navBrandsData.value
  }
  // Base skeleton titles structured before API response resolution
  return [
    { brand: 'BMW', categories: [] },
    { brand: 'Mercedes-Benz', categories: [] },
    { brand: 'Land Rover', categories: [] }
  ]
})

const checkAuthSession = () => {
  if (process.client) {
    const token = localStorage.getItem('atms_user_token')
    const savedName = localStorage.getItem('atms_user_display')
    
    if (token) {
      isLoggedIn.value = true
      userName.value = savedName || 'User'
    } else {
      isLoggedIn.value = false
      userName.value = ''
    }
  }
}

onMounted(() => {
  checkAuthSession()
  ensureNavMenu()
})

const toggleMobileBrand = (brand) => {
  if (activeMobileBrand.value === brand) {
    activeMobileBrand.value = null
  } else {
    activeMobileBrand.value = brand
  }
}

const totalCartItems = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

const guestLinks = [
  { label: 'Sign In', to: '/checkout-auth', icon: 'fa-solid fa-right-to-bracket' },
  { label: 'Create Account', to: '/register', icon: 'fa-solid fa-user-plus' },
]

const handleLogout = () => {
  if (process.client) {
    localStorage.removeItem('atms_user_token')
    localStorage.removeItem('atms_user_display')
    localStorage.removeItem('atms_checkout_mode')
  }
  isLoggedIn.value = false
  userName.value = ''
  isMobileMenuOpen.value = false
  isAccountDropdownOpen.value = false
  navigateTo('/')
}

const route = useRoute()
watch(() => route.fullPath, () => {
  isMobileMenuOpen.value = false
  isAccountDropdownOpen.value = false
  activeMobileBrand.value = null
  showSuggestions.value = false
  checkAuthSession()
})

const handleCartClick = () => {
  // Live Safe Guard: Agar cart items zero hain ya cart exist nahi karta
  if (!totalCartItems.value || totalCartItems.value === 0) {
    showToast('Your cart is empty. Add parts to the cart first!', 'error')
    return
  }
  
  // Agar items hain, to safely route par bhej do
  navigateTo('/cart')
}

</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
}
</style>