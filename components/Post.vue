<template>
  <div class="bg-[url('/Blogimage-bg.jpg')] bg-cover bg-center bg-no-repeat">
      <div class="max-w-[1230px] mx-auto px-3 py-16">
          <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht" style="line-height: 50px;">
              Trail tracking: <br> x-pedition journal
          </h2>
          <p class="mt-5 mb-12 max-w-2xl text-lg text-white text-center mx-auto">
              First hand insights on our team’s trekking expeditions.
          </p>

          <!-- Swiper Slider -->
          <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :pagination="{ clickable: true }"
              :spaceBetween="0" :modules="[Navigation, Pagination]" :loop="true" :centeredSlides="true" class="w-full relative " style="padding-bottom: 70px !important;"
              :breakpoints="{
                  568: { slidesPerView: 1 },
                  768: { slidesPerView: 2 },
                  1024: { slidesPerView: 3 }
              }">
              
              <!-- Navigation Arrows -->
              <section class="parallax-slider-navigation cursor-pointer">
                  <article class="nav-indicator prevArrow">
                      <img src="/public/left-slide-icon.svg" alt="Left Arrow" class="w-10 absolute z-40 bottom-0 left-[10%] md:left-[30%] lg:left-[37%]" />
                  </article>
                  <article class="nav-indicator nextArrow">
                      <img src="/public/right-slide-icon.svg" alt="Right Arrow" class="w-10 absolute z-40 bottom-0 right-[10%] md:right-[30%] lg:right-[37%]" />
                  </article>
              </section>

              <!-- Swiper Slides -->
              <swiper-slide v-for="post in data" :key="post.uri">
                  <div class="rounded-lg bg-[#191818] mx-3 mt-6 min-h-[490px] overflow-hidden">
                      <NuxtLink :to="{ path: post.uri }">
                          <img :src="post.featuredImage?.node?.sourceUrl || '/public/trip1.jpg'" alt="Featured Image"
                              class="rounded-t-lg w-full h-[250px] object-cover" />
                          <div class="p-4 sm:px-5">
                              <!-- Truncated Title -->
                              <h3 class="font-semibold text-xl text-white mt-2">{{ truncateText(post.title, 5) }}</h3>
                              
                              <!-- Truncated Excerpt without HTML Tags -->
                              <p class="text-[14px] text-white my-2 max-h-[130px] overflow-hidden" v-html="post.excerpt"></p>
                              <NuxtLink :to="{ path: post.uri }" class="text-white border-b border-white">Read More</NuxtLink>
                          </div>
                      </NuxtLink>
                  </div>
              </swiper-slide>
          </swiper>
      </div>
  </div>
</template>

<script setup lang="js">
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation, Pagination } from "swiper/modules";

const config = useRuntimeConfig();

const { data } = await useFetch(config.public.wordpressUrl, {
  method: 'post',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
      query: `
      query {
        posts {
          nodes {
            title
            uri
            excerpt
            featuredImage {
              node {
                sourceUrl
              }
            }
          }
        }
      }
    `,
  }),
  transform: (data) => data.data.posts.nodes,
});

const truncateText = (html, wordLimit) => {
  if (process.client) {
      const div = document.createElement("div");
      div.innerHTML = html;
      const text = div.textContent || div.innerText || "";
      const words = text.split(" ");
      return words.length > wordLimit ? words.slice(0, wordLimit).join(" ") + "..." : text;
  }
  return "";
};

</script>

<style scoped>
.mySwiper {
  margin-top: 20px;
}
:deep(.swiper-pagination-bullet) {
background-color: #fff !important;
margin-right: 8px !important;
}

:deep(.swiper-pagination-bullet-active) {
background-color: transparent !important;
border: 2px solid #fff;
}
</style>
