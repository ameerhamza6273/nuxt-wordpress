<template>
    <div>
      <!-- Desktop & Tablet Navbar -->
      <nav class="fixed w-full z-50 top-0 left-0 bg-[#0000006b] text-white transition-all duration-300">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-5 px-5 sm:px-8">
          <!-- Logo -->
          <NuxtLink to="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/public//Logo.png" class="w-16 md:w-24" alt="Logo" />
          </NuxtLink>
  
          <!-- Mobile Menu Toggle -->
          <button
            class="inline-flex items-center p-2 w-10 h-10 rounded-lg focus:outline-none md:hidden"
            @click="toggleMenu"
          >
            <span class="sr-only">Open main menu</span>
            <svg v-if="!isToggled" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            <span v-else class="text-3xl font-semibold">×</span>
          </button>
  
          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center space-x-6">
            <ul class="flex items-center space-x-5">
              <li v-for="item in pagesLinks" :key="item.link">
                <NuxtLink
                  :to="item.link"
                  :class="[
                    'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  ]"
                  @click="getActiveLink(item.title)"
                >
                  <template v-if="item.image">
                    <img :src="item.image" alt="icon" class="inline w-7 mr-2" />
                  </template>
                  {{ item.title }}
                </NuxtLink>
              </li>
            </ul>
          </div>
        </div>
      </nav>
  
      <!-- Mobile Navbar -->
      <div
        v-if="isToggled"
        class="fixed inset-0 z-40 bg-transparent  flex items-center justify-center md:hidden"
      >
        <ul class="space-y-4 text-center text-white">
          <li v-for="item in pagesLinks" :key="item.link">
            <NuxtLink
              :to="item.link"
              class="block py-2 text-lg font-semibold hover:text-blue-400"
              @click="toggleMenu"
            >
              <template v-if="item.image">
                <img :src="item.image" alt="icon" class="inline w-5 h-5 mr-2" />
              </template>
              {{ item.title }}
            </NuxtLink>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isToggled: false,
        activeLink: "Home",
        pagesLinks: [
          { title: "Home", link: "/#home" },
          { title: "About Us", link: "/#aboutUs" },
          { title: "Reviews", link: "/#reviews" },
          { title: "Contact Us", link: "/#contactUs" },
          { title: "Find A Trip", link: "/#exploreTrips", image: "/Search Trips Icon.svg" },
        ],
      };
    },
    methods: {
      toggleMenu() {
        this.isToggled = !this.isToggled;
      },
      getActiveLink(title) {
        this.activeLink = title;
      },
    },
  };
  </script>
  
  <style scoped>
  body {
    padding-top: 80px; /* Navbar height adjustment */
  }
  .active-link {
    font-weight: bold;
  }
  </style>
  