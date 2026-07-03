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

        <ul class="hidden lg:flex items-center space-x-6 mx-auto">
          <li v-for="brand in ['BMW', 'VOLVO', 'AUDI', 'VW', 'MERCEDES', 'PORSCHE']" :key="brand">
            <NuxtLink to="#"
              class="text-sm font-bold text-gray-900 hover:text-[#e31e24] transition-colors uppercase tracking-tight">
              {{ brand }}
            </NuxtLink>
          </li>
        </ul>

        <div class="flex items-center gap-3">
          <div class="relative hidden xl:block w-[250px]">
            <input type="text" placeholder="Search Part..."
              class="w-full bg-[#fcfcfc] border-2 border-gray-100 rounded-lg py-2 pl-4 pr-10 text-sm focus:border-[#f2a900] outline-none" />
            <button class="absolute right-1 top-1 bottom-1 bg-[#e31e24] text-white px-3 rounded-md">
              <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
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

              </div>
            </div>
          </div>

          <NuxtLink to="/cart"
            class="relative cursor-pointer group p-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-all duration-300">
            <i class="fa-solid fa-cart-shopping text-gray-700 text-sm group-hover:text-[#e31e24] transition-colors"></i>
            <span v-if="totalCartItems > 0"
              class="absolute -top-2 -right-2 bg-[#e31e24] text-white rounded-full min-w-[18px] h-[18px] text-[10px] font-black flex items-center justify-center px-1 shadow-sm">
              {{ totalCartItems }}
            </span>
          </NuxtLink>

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
            <ul class="py-2">
              <li v-for="brand in ['BMW', 'VOLVO', 'AUDI', 'VW', 'MERCEDES', 'PORSCHE']" :key="brand">
                <NuxtLink to="#"
                  class="block px-6 py-4 text-sm font-black border-b border-gray-50 hover:bg-gray-50 hover:text-[#e31e24] uppercase transition-colors">
                  {{ brand }}
                </NuxtLink>
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

              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { cartItems } from '~/composables/useCart.js'

const isMobileMenuOpen = ref(false)
const isAccountDropdownOpen = ref(false)

// Session Auth States Variables
const isLoggedIn = ref(false)
const userName = ref('')

// Initialize login state context verification straight from browser storage pipeline
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
})

// Cart item numeric count calculator
const totalCartItems = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

// Correct mapped path routing arrays according to our pages strategy structure
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
  checkAuthSession() // Sync route context session dynamic components states modifications
})
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