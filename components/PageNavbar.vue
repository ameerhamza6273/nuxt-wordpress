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
            <input type="text" v-model="searchQuery" placeholder="Search By Make (e.g. BMW)"
              @input="handleSearchInput" @focus="handleSearchInput" @blur="handleSearchBlur"
              @keydown.enter.prevent="handleSearchSubmit" @keydown.esc="showSuggestions = false"
              class="w-full bg-[#fcfcfc] border-2 border-gray-100 rounded-lg py-2 pl-4 pr-10 text-[12px] focus:border-[#f2a900] outline-none" />
            <button type="button" @click="handleSearchSubmit"
              class="absolute right-1 top-1 bottom-1 bg-[#e31e24] text-white px-3 rounded-md">
              <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>

            <div v-if="showSuggestions"
              class="absolute left-0 right-0 top-full mt-2 bg-white shadow-2xl rounded-lg border border-gray-100 overflow-hidden z-50 py-1">
              <template v-if="searchSuggestions.length > 0">
                <button v-for="make in searchSuggestions" :key="make.slug || make.name" type="button"
                  @mousedown.prevent="selectSuggestion(make.name)"
                  class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-[#e31e24] transition-colors flex items-center gap-2">
                  <i class="fa-solid fa-car-side text-[10px] text-gray-300"></i>
                  {{ make.name }}
                </button>
              </template>
              <p v-else class="px-4 py-3 text-[11px] font-bold text-gray-400 uppercase tracking-wide">
                No matching brand found
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
import { navBrandsData, ensureNavMenu, allMakes, ensureAllMakes } from '~/composables/useVehicleData.js'
import { showToast } from '~/composables/useToast.js'

const isMobileMenuOpen = ref(false)
const isAccountDropdownOpen = ref(false)
const activeMobileBrand = ref(null)

const searchQuery = ref('')
const showSuggestions = ref(false)

// 🟢 Sirf makes/brands ke against filter - navbar search "search by make" hai,
// part-number/keyword search nahi (uske liye koi backend endpoint mojood nahi)
const searchSuggestions = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (!q) return []
  return allMakes.value
    .filter((m) => (m.name || '').toLowerCase().includes(q))
    .slice(0, 6)
})

const handleSearchInput = () => {
  showSuggestions.value = searchQuery.value.trim().length > 0
}

const handleSearchBlur = () => {
  // Chhota delay taake suggestion button ka click blur se pehle register ho jaye
  setTimeout(() => { showSuggestions.value = false }, 150)
}

const goToMakeSearch = (brandName) => {
  searchQuery.value = ''
  showSuggestions.value = false
  navigateTo({ path: '/products', query: { make: brandName, page: 1 } })
}

// 🟢 Suggestion click par seedha navigate nahi karna - sirf input mein value bhar dein,
// user ko phir search icon/Enter se explicitly search karna hoga
const selectSuggestion = (brandName) => {
  searchQuery.value = brandName
  showSuggestions.value = false
}

const handleSearchSubmit = () => {
  const query = searchQuery.value.trim()
  if (!query) {
    showToast('Please enter a brand to search.', 'error')
    return
  }

  // Exact match mile to seedha wahan bhej dein
  const exactMatch = allMakes.value.find((m) => (m.name || '').toLowerCase() === query.toLowerCase())
  if (exactMatch) {
    goToMakeSearch(exactMatch.name)
    return
  }

  // Warna partial matches dekhein - agar sirf ek hi ho to wahi sahi maan lein,
  // zyada hon to user se select karwayein, koi na ho to error dikhayein
  const matches = searchSuggestions.value
  if (matches.length === 1) {
    goToMakeSearch(matches[0].name)
  } else if (matches.length > 1) {
    showSuggestions.value = true
    showToast('Multiple matching brands found — please pick one from the list.', 'info')
  } else {
    showSuggestions.value = true
    showToast(`No brand matching "${query}" found. Please pick from the suggestions.`, 'error')
  }
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
  ensureAllMakes()
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