<template>
  <div>
    <footer v-if="footerData">
      <div class="bg-black p-5 sm:p-10">
        <div class=" flex flex-col md:flex-row md:items-center max-w-[1220px] mx-auto">
          <div class="w-100 md:w-[40%] text-center md:text-left">
            <h2 class="text-white font-bold max-w-[340px] text-3xl md:text-5xl mt-3 uppercase heading-line-ht"
              style="line-height: 56px;">
              {{ footerData.acf.title }}
            </h2>
            <p class="text-lg text-white  mt-3">{{ footerData.acf.description }}</p>
            <p class="text-lg text-white font-bold ">{{ footerData.acf.number_title }} <NuxtLink to="tel:+65 94876198">
                {{ footerData.acf.number }}</NuxtLink>
            </p>
            <p class="text-lg text-white font-bold ">{{ footerData.acf.email_title }} <NuxtLink
                to="mailto:info@x-trekkers.com"> {{ footerData.acf.email }}</NuxtLink>
            </p>
          </div>
          <div class="flex items-center justify-between sm:justify-around m-5 md:m-0 w-100 md:w-[60%]">

            <NuxtLink to="https://wa.me/6594876198" class=" cursor-pointer" external target="_blank"> 
              <NuxtImg :src="footerData.acf.whatsapp_image" alt="user"
                class="max-w-[40px] md:max-w-[70px]" />
            </NuxtLink>
            <NuxtLink to="https://www.instagram.com/xtrekkers" class=" cursor-pointer" external target="_blank"> 
              <NuxtImg :src="footerData.acf.insta_image" alt="user"
                class="max-w-[40px] md:max-w-[70px]" />
            </NuxtLink>
            <NuxtLink to="https://www.facebook.com/xtrekkers" class=" cursor-pointer" external target="_blank"> 
              <NuxtImg :src="footerData.acf.facebook_image" alt="user"
                class="max-w-[40px] md:max-w-[70px]" />
            </NuxtLink>
            <NuxtLink to="mailto:info@x-trekkers.com" class=" cursor-pointer "> 
              <NuxtImg :src="footerData.acf.email_image" alt="user"
                class="max-w-[40px] md:max-w-[70px]" />
            </NuxtLink>

          </div>
        </div>
      </div>
      <div class="bg-[#161616] p-10 md:p-12 px-5 md:px-12 ">
        <div class=" flex items-center flex-col md:flex-row justify-around gap-5 max-w-[1220px] mx-auto">

          <NuxtLink :to="footerData.acf.page_1.url" class="font-bold text-white text-lg md:text-xl uppercase">
            {{ footerData.acf.page_1.title }}</NuxtLink>
          <NuxtLink :to="footerData.acf.page_2.url" class="font-bold text-white text-lg md:text-xl uppercase">
            {{ footerData.acf.page_2.title }}</NuxtLink>
          <div class="font-bold text-white text-lg md:text-xl uppercase">
            {{ footerData.acf.page_3.title }}</div>

        </div>
      </div>
    </footer>
  </div>
</template>
<script setup>
import { computed } from "vue";

const props = defineProps({
  footerData: {
    type: Array,
    required: true,
    default: () => [] // ✅ Prevents undefined errors
  }
});
const OtherPages = [
  { title: "Terms & conditions", url: "" },
  { title: "privacy policy", url: "" },
  { title: "CERTIFIED TRAVEL AGENCY: 01261", url: "" },

];
// ✅ Sirf ek specific ID ka data fetch karo
const footerData = computed(() => props.footerData?.find(member => member.id === 27328) || null);
</script>
