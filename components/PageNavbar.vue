<template>
  <div v-if="headerData">
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
          <img :src="headerData.acf.logo" class="w-16 md:w-24" alt="Logo" />
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
            <li>
              <NuxtLink
                :to="headerData.acf.link_1"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_1
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_1)" 
              >
              home 
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_2"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_2
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_2)" 
              >
              About Us
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_3"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_3
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_3)" 
              >
              Reviews
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_4"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_4
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_4)" 
              >
              Contact Us
              </NuxtLink>
            </li>
            <li >
              <NuxtLink
                :to="headerData.acf.link_5"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_5
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_5)"
              >
                  <img :src="headerData.acf.icon" alt="icon" 
                  class="inline w-7 mr-2"
                  :class="activeLink === headerData.acf.link_5 ? ' filter brightness-50' : ''"
                   />
                Find A Trip
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
        <li>
              <NuxtLink
                :to="headerData.acf.link_1"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_1
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_1); closeMenu()" 
              >
              home 
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_2"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_2
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_2); closeMenu()" 
              >
              About Us
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_3"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_3
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_3); closeMenu()" 
              >
              Reviews
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                :to="headerData.acf.link_4"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_4
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_4); closeMenu()" 
              >
              Contact Us
              </NuxtLink>
            </li>
            <li >
              <NuxtLink
                :to="headerData.acf.link_5"
                :class="[
                  'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                  activeLink === headerData.acf.link_5
                    ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                    : 'text-white'
                ]"
                @click="getActiveLink(headerData.acf.link_5); closeMenu()"
              >
                  <img :src="headerData.acf.icon" alt="icon" 
                  class="inline w-7 mr-2"
                  :class="activeLink === headerData.acf.link_5 ? ' filter brightness-50' : ''"
                   />
                Find A Trip
              </NuxtLink>
            </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { ref, onMounted, onBeforeUnmount } from "vue";

const isToggled = ref(false);
const isScrolled = ref(false);
const activeLink = ref("/#Home");

const toggleMenu = () => {
  isToggled.value = !isToggled.value;
};

const getActiveLink = (title) => {
  activeLink.value = title;
};

const closeMenu = () => {
  isToggled.value = false;
};


const handleScroll = () => {
  isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll, { passive: true });
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
});


const props = defineProps({
  headerData: {
    type: Array,
    required: true,
    default: () => [] // ✅ Prevents undefined errors
  }
});
const headerData = computed(() => props.headerData?.find(member => member.id === 27325) || null);

</script>

<style scoped>
.active-link {
  font-weight: bold;
}
</style>
