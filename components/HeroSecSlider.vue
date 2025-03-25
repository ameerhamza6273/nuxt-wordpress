<template>
    <div class="bg-transparent mt-10 md:mt-0">
        <!-- Swiper Slider -->
        <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :pagination="{ clickable: true }"
            :spaceBetween="20" :slidesPerView="2.5" :modules="[Navigation]" :loop="true"
            class="w-full relative" style="padding-bottom: 70px !important;" :breakpoints="{
                340: { slidesPerView: 1.5 },
                600: { slidesPerView: 2 },
                1124: { slidesPerView: 2.5 }
            }">
            <!-- Navigation Arrows -->
            <section class="parallax-slider-navigation cursor-pointer">
                <article class="nav-indicator prevArrow">
                    <img src="/public/left-slide-icon.svg" alt="Left Arrow"
                        class="w-10 absolute z-40 bottom-0 left-[20px]" />
                </article>
                <article class="nav-indicator nextArrow">
                    <img src="/public/right-slide-icon.svg" alt="Right Arrow"
                        class="w-10 absolute z-40 bottom-0 left-[100px]" />
                </article>
            </section>

            <!-- Swiper Slides -->
            <swiper-slide v-for="(data, index) in pagesLinks" :key="index">
                <div class="rounded-xl mx-3 min-h-[300px] overflow-hidden relative"
    :style="{ backgroundImage: `url(${data.bgImage})`, backgroundSize: 'cover', backgroundPosition: 'center' }">

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent pointer-events-none"></div>

    <div class="p-6 px-7 z-10 flex flex-col justify-end min-h-[300px] relative">
        <!-- Dynamic Data -->
        <p class="text-lg text-white text-center z-20">
            <b>{{ data.title }}</b><br />
            {{ data.description }}
        </p>
        <NuxtLink :to="data.link"
            class="bg-[#ffffff1f] mt-3 delay-300 text-white rounded-xl shadow-md py-2 px-4 block font-medium text-center border-2 border-white cursor-pointer z-20">
            {{ data.btnText }}
        </NuxtLink>
    </div>
</div>

            </swiper-slide>
        </swiper>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation } from "swiper/modules";
import { ref } from "vue";

const pagesLinks = ref([
    { bgImage: "/slider-bg-1.png", title: "Druk Path Trek:", description: "The Ultimate Bhutan Trekking Adventure", btnText: "Book Now", link: "/druk-path" },
    { bgImage: "/slider-bg-1.png", title: "Dolomites of Cortina:", description: "Guided Hiking Adventure", btnText: "Book Now", link: "/dolomites" },
    { bgImage: "/slider-bg-1.png", title: "Tour du Mont Blanc:", description: "A Complete 9-Day Alpine Adventure", btnText: "Book Now", link: "/mont-blanc" },
    { bgImage: "/slider-bg-1.png", title: "Druk Path Trek:", description: "The Ultimate Bhutan Trekking Adventure", btnText: "Book Now", link: "/druk-path" },
    { bgImage: "/slider-bg-1.png", title: "Dolomites of Cortina:", description: "Guided Hiking Adventure", btnText: "Book Now", link: "/dolomites" },
    { bgImage: "/slider-bg-1.png", title: "Tour du Mont Blanc:", description: "A Complete 9-Day Alpine Adventure", btnText: "Book Now", link: "/mont-blanc" },
]);
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