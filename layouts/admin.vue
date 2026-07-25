<template>
  <div class="min-h-screen bg-[#f8f9fa] flex">
    <aside class="w-60 bg-black text-white shrink-0 hidden md:flex md:flex-col md:sticky md:top-0 md:h-screen md:overflow-y-auto">
      <div class="p-6 border-b border-white/10">
        <NuxtLink to="/">
          <NuxtImg src="/logic-auto-parts-website-logo.jpeg" alt="Logic Auto Parts" class="h-14 w-full rounded" />
        </NuxtLink>
        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-3">Admin Panel</p>
      </div>

      <nav class="flex-1 py-4 px-3 space-y-1">
        <NuxtLink v-for="item in navItems" :key="item.to" :to="item.to"
          class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200"
          active-class="!text-white !bg-[#e31e24] shadow-lg shadow-[#e31e24]/20">
          <i :class="[item.icon, 'w-4']"></i> {{ item.label }}
        </NuxtLink>
      </nav>

      <div class="p-3 border-t border-white/10">
        <button @click="logout"
          class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white hover:bg-[#e31e24] transition-all duration-200">
          <i class="fa-solid fa-right-from-bracket w-4"></i> Log Out
        </button>
      </div>
    </aside>

    <Transition name="fade">
      <div v-if="mobileOpen" class="fixed inset-0 z-50 md:hidden">
        <div class="absolute inset-0 bg-black/60" @click="mobileOpen = false"></div>
        <div class="absolute left-0 top-0 bottom-0 w-64 bg-black text-white flex flex-col">
          <div class="p-6 border-b border-white/10 flex justify-between items-center">
            <NuxtLink to="/" class="flex items-center gap-2" @click="mobileOpen = false">
              <NuxtImg src="/logic-auto-parts-website-logo.jpeg" alt="Logic Auto Parts" class="h-9 w-auto rounded" />
              <span class="font-black uppercase tracking-wider text-xs text-white leading-tight">Logic Auto<br />Parts</span>
            </NuxtLink>
            <button @click="mobileOpen = false" class="text-2xl">&times;</button>
          </div>
          <nav class="flex-1 py-4 px-3 space-y-1">
            <NuxtLink v-for="item in navItems" :key="item.to" :to="item.to" @click="mobileOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200"
              active-class="!text-white !bg-[#e31e24] shadow-lg shadow-[#e31e24]/20">
              <i :class="[item.icon, 'w-4']"></i> {{ item.label }}
            </NuxtLink>
          </nav>
          <div class="p-3 border-t border-white/10">
            <button @click="logout"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wide text-gray-400 hover:text-white hover:bg-[#e31e24] transition-all duration-200">
              <i class="fa-solid fa-right-from-bracket w-4"></i> Log Out
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <div class="flex-1 flex flex-col min-w-0">
      <header class="bg-white border-b border-gray-100 shadow-sm px-6 py-4 flex items-center justify-between md:hidden">
        <button class="text-xl text-gray-800" @click="mobileOpen = true">
          <i class="fa-solid fa-bars"></i>
        </button>
        <span class="text-xs font-black uppercase tracking-widest text-gray-400">Admin Panel</span>
        <div class="w-6"></div>
      </header>

      <main class="flex-1 p-6 w-full">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const mobileOpen = ref(false)

const navItems = [
  { label: 'Products', to: '/admin/products', icon: 'fa-solid fa-boxes-stacked' },
  { label: 'Add Product', to: '/admin/products/new', icon: 'fa-solid fa-plus' },
  { label: 'Bulk Import', to: '/admin/import', icon: 'fa-solid fa-file-import' },
  { label: 'Orders', to: '/admin/orders', icon: 'fa-solid fa-receipt' },
]

function logout() {
  if (process.client) sessionStorage.removeItem('import_logged_in')
  navigateTo('/admin')
}
</script>
