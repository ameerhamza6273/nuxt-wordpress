<template>
    <div>
      <footer>
        <div class="bg-black p-5 sm:p-10">
          <div class=" flex flex-col md:flex-row md:items-center max-w-[1220px] mx-auto">
            <div class="w-100 md:w-[40%]">
              <h2 class="text-white font-bold text-3xl md:text-5xl mt-3 uppercase heading-line-ht"
                style="line-height: 56px;">
                SPEAK TO <br>
                US at ANYTIME!
              </h2>
              <p class="text-lg text-white  mt-3">Let’s connect! Join our growing community!</p>
              <p class="text-lg text-white font-bold ">Office Number: +65 94876198</p>
              <p class="text-lg text-white font-bold ">Email: info@x-trekkers.com</p>
  
            </div>
            <div class="flex items-center justify-between sm:justify-around mt-4 w-100 md:w-[60%]">
              <div v-for="value in socialImages">
                <NuxtLink :to="value.url" class=" cursor-pointer "><img :src="value.img" alt="user" class="max-w-[40px] md:max-w-[70px]" /></NuxtLink>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-[#161616] p-10 md:p-12 px-5 md:px-12 ">
          <div class=" flex items-center flex-col md:flex-row justify-around gap-5 max-w-[1220px] mx-auto">
            <div v-for="value in OtherPages">
              <NuxtLink :to="value.url" class="font-bold text-white text-lg md:text-xl uppercase">{{ value.title }}</NuxtLink>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </template>
  <script setup>
  const socialImages = [
    { img: "/whatsapp_2582600.svg", url: "" },
    { img: "/instagram-icon.svg", url: "" },
    { img: "/facebook_icon-footer.svg", url: "" },
    { img: "/Mail-icon.svg", url: "" }
  ];
  const OtherPages = [
    { title: "Terms & conditions", url: "" },
    { title: "privacy policy", url: "" },
    { title: "CERTIFIED TRAVEL AGENCY: 01261", url: "" },
  
  ];
  
  </script>
  