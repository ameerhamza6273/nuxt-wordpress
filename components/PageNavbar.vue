<template>
  <div v-if="headerData">
    <!-- Desktop & Tablet Navbar -->
    <nav :class="[
      'fixed w-full z-50 top-0 left-0 text-white transition-all duration-300',
      isTermsPage || isPrivacyPage ? 'bg-[#161616]' :
      isScrolled ? 'bg-[#161616] shadow-[0_4px_10px_rgba(255,255,255,0.2)]' : 'bg-transparent',
      isNavbarVisible ? 'translate-y-0' : '-translate-y-full'
    ]">
      <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto py-5 px-5 sm:px-8">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center space-x-3 rtl:space-x-reverse">
          <NuxtImg :src="headerData.acf.logo" class="w-20 md:w-24" alt="Logo" />
        </NuxtLink>


        <!-- Mobile Menu Toggle -->
        <button class="inline-flex items-center p-2 w-10 h-10 rounded-lg focus:outline-none md:hidden"
          @click="toggleMenu">
          <span class="sr-only">Open main menu</span>
          <svg v-if="!isToggled" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
          <span v-else class="text-4xl font-medium">×</span>
        </button>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
          <ul class="flex items-center space-x-5">
            <li>
              <NuxtLink :to="headerData.acf.link_1.url" :class="[
                'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                isTermsPage || isPrivacyPage ? 'bg-[#161616]' :
                activeLink === headerData.acf.link_1.url
                  ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                  : 'text-white'
              ]" @click="getActiveLink(headerData.acf.link_1.url)">
                {{ headerData.acf.link_1.title }}
              </NuxtLink>
            </li>
            <li>
              <NuxtLink :to="headerData.acf.link_2.url" :class="[
                'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                activeLink === headerData.acf.link_2.url
                  ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                  : 'text-white'
              ]" @click="getActiveLink(headerData.acf.link_2.url)">
                {{ headerData.acf.link_2.title }}
              </NuxtLink>
            </li>
            <li>
              <NuxtLink :to="headerData.acf.link_3.url" :class="[
                'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                activeLink === headerData.acf.link_3.url
                  ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                  : 'text-white'
              ]" @click="getActiveLink(headerData.acf.link_3.url)">
                {{ headerData.acf.link_3.title }}
              </NuxtLink>
            </li>
            <li>
              <NuxtLink :to="headerData.acf.link_4.url" :class="[
                'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                activeLink === headerData.acf.link_4.url
                  ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                  : 'text-white'
              ]" @click="getActiveLink(headerData.acf.link_4.url)">
                {{ headerData.acf.link_4.title }}
              </NuxtLink>
            </li>
            <li>
              <NuxtLink :to="headerData.acf.link_5.url" :class="[
                'py-2 px-3 rounded-full font-medium uppercase transition-colors text-sm duration-300',
                activeLink === headerData.acf.link_5.url
                  ? isScrolled ? 'text-[#dddddd8a] font-bold' : 'text-black font-bold'
                  : 'text-white'
              ]" @click="getActiveLink(headerData.acf.link_5.url)">
                <NuxtImg :src="headerData.acf.icon" alt="icon" class="inline w-7 mr-2"
                  :class="activeLink === headerData.acf.link_5.url ? ' filter brightness-50' : ''" />
                {{ headerData.acf.link_5.title }}
              </NuxtLink>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Mobile Navbar -->
    <div v-if="isToggled" class="fixed inset-0 z-50 bg-[#000] flex flex-col justify-between pb-5 md:hidden">
      <!-- Close button -->
      <div class="text-white flex items-center justify-between p-4">
        <div class=" md:hidden ">MENU</div>
        <button @click="closeMenu" class="text-4xl">&times;</button>
      </div>

      <!-- Navigation links -->
      <ul class="space-y-4 text-center text-white">
        <li>
          <NuxtLink :to="headerData.acf.link_1.url" class="py-2 text-xl  block uppercase"
            :class="activeLink === headerData.acf.link_1.url ? 'text-[#000] font-bold bg-[#D9D9D9]' : 'text-[#fff] font-bold bg-transparent'"
            @click="getActiveLink(headerData.acf.link_1.url); closeMenu()">
            <NuxtImg :src="headerData.acf.home_icon" alt="icon" class="inline h-5 mr-2"
              :class="activeLink === headerData.acf.link_1.url ? ' filter brightness-0' : ''" />

            {{ headerData.acf.link_1.title }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink :to="headerData.acf.link_2.url" class="py-2 text-xl block uppercase"
            :class="activeLink === headerData.acf.link_2.url ? 'text-[#000] font-bold bg-[#D9D9D9]' : 'text-[#fff] font-bold bg-transparent'"
            @click="getActiveLink(headerData.acf.link_2.url); closeMenu()">
            <NuxtImg :src="headerData.acf.about_icon" alt="icon" class="inline h-5 mr-2"
              :class="activeLink === headerData.acf.link_2.url ? ' filter brightness-0' : ''" />
            {{ headerData.acf.link_2.title }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink :to="headerData.acf.link_3.url" class="py-2 text-xl  block uppercase"
            :class="activeLink === headerData.acf.link_3.url ? 'text-[#000] font-bold bg-[#D9D9D9]' : 'text-[#fff] font-bold bg-transparent'"
            @click="getActiveLink(headerData.acf.link_3.url); closeMenu()">
            <NuxtImg :src="headerData.acf.review_icon" alt="icon" class="inline h-5 mr-2"
              :class="activeLink === headerData.acf.link_3.url ? ' filter brightness-0' : ''" />
            {{ headerData.acf.link_3.title }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink :to="headerData.acf.link_5.url" class="py-2 text-xl  block uppercase"
            :class="activeLink === headerData.acf.link_5.url ? 'text-[#000] font-bold bg-[#D9D9D9]' : 'text-[#fff] font-bold bg-transparent'"
            @click="getActiveLink(headerData.acf.link_5.url); closeMenu()">
            <NuxtImg :src="headerData.acf.icon" alt="icon" class="inline h-5 mr-2"
              :class="activeLink === headerData.acf.link_5.url ? ' filter brightness-0' : ''" />
            {{ headerData.acf.link_5.title }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink :to="headerData.acf.link_4.url" class="py-2 text-xl block uppercase"
            :class="activeLink === headerData.acf.link_4.url ? 'text-[#000] font-bold bg-[#D9D9D9]' : 'text-[#fff] font-bold bg-transparent'"
            @click="getActiveLink(headerData.acf.link_4.url); closeMenu()">
            <NuxtImg :src="headerData.acf.contact_icon" alt="icon" class="inline h-5 mr-2"
              :class="activeLink === headerData.acf.link_4.url ? ' filter brightness-0' : ''" />
            {{ headerData.acf.link_4.title }}
          </NuxtLink>

        </li>

      </ul>
      <NuxtLink to="/" class="flex items-center space-x-3 rtl:space-x-reverse mx-auto">
        <NuxtImg :src="headerData.acf.logo" class="w-24" alt="Logo" @click="getActiveLink('/#home'); closeMenu()" />
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const isTermsPage = computed(() => route.path.includes('terms-conditions'));
const isPrivacyPage = computed(() => route.path.includes('privacy-policy'));

const isToggled = ref(false);
const isScrolled = ref(false);
const activeLink = ref("/#home");
let lastScrollTop = 0;
const isNavbarVisible = ref(true);

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
  const scrollTop = window.scrollY;
  const isDesktop = window.innerWidth >= 1024; // lg: breakpoint (Tailwind default)

  // Always update background color on scroll
  isScrolled.value = scrollTop > 50;

  if (!isDesktop) {
    // Mobile/tablet - always show navbar
    isNavbarVisible.value = true;
    return;
  }

  // Desktop - handle scroll direction
  if (scrollTop > lastScrollTop && scrollTop > 100) {
    // Scrolling down
    isNavbarVisible.value = false;
  } else {
    // Scrolling up
    isNavbarVisible.value = true;
  }

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
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
watch(isToggled, (newVal) => {
  if (newVal) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

</script>

<style scoped>
.active-link {
  font-weight: bold;
}
</style>
