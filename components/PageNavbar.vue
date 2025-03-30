<template>
  <div>
    <!-- Desktop & Tablet Navbar -->
    <nav
      :class="[
        'fixed w-full z-50 top-0 left-0 text-white transition-all duration-300',
        isScrolled ? 'bg-[#161616] shadow-[0_4px_10px_rgba(255,255,255,0.2)]' : 'bg-transparent'
      ]"
    >
      <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-5 px-5 sm:px-8">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-3 rtl:space-x-reverse">
          <img src="/public/Logo.png" class="w-16 md:w-24" alt="Logo" />
        </NuxtLink>

        <!-- Mobile Menu Toggle -->
        <button
          class="inline-flex items-center p-2 w-10 h-10 rounded-lg focus:outline-none md:hidden"
          @click="toggleMenu"
        >
          <span class="sr-only">Open main menu</span>
          <svg
            v-if="!isToggled"
            class="w-6 h-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
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
                  activeLink === item.title
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(item.title)"
              >
                <template v-if="item.image">
                  <img :src="item.image" alt="icon" 
                  class="inline w-7 mr-2"
                  :class="activeLink === item.title ? ' filter brightness-50' : ''"
                   />
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
      class="fixed inset-0 z-40 bg-[#000] flex items-center justify-center md:hidden"
      
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
      isScrolled: false,
      activeLink: "Home",
      pagesLinks: [
        { title: "Home", link: "/#home" },
        { title: "About Us", link: "/#aboutUs" },
        { title: "Reviews", link: "/#reviews" },
        { title: "Contact Us", link: "/#contactUs" },
        { title: "Find A Trip", link: "/#exploreTrips", image: "Search Trips Icon.svg" },
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
  handleScroll() {
    this.isScrolled = window.scrollY > 50;
  },
},
mounted() {
  window.addEventListener("scroll", this.handleScroll, { passive: true });
},
beforeDestroy() {
  window.removeEventListener("scroll", this.handleScroll);
}

};
</script>

<style scoped>
.active-link {
  font-weight: bold;
}
</style>
