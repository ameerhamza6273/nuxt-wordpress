<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation } from "swiper/modules";
import { computed } from 'vue';

const props = defineProps({
    druckPage: {
    type: Array,
    required: true,
  },
});

const druckPageId = 27395;

// Ensure druckPage is not null/undefined before calling .find()
const druckPage = computed(() => (props.druckPage?.length ? props.druckPage.find((m) => m.id === druckPageId) : null));
</script>

<template>
    <div v-if="druckPage">
        <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :spaceBetween="0" :modules="[Navigation]"
            :centeredSlides="true"
            :autoHeight="true" class="w-full relative" :breakpoints="{
                568: { slidesPerView: 1 },
                768: { slidesPerView: 1 },
                1024: { slidesPerView: 1 }
            }">

            <section class="parallax-slider-navigation cursor-pointer">
                <article class="nav-indicator prevArrow">
                    <NuxtImg src="/left-slide-icon.svg" alt="Left Arrow"
                        class="w-10 absolute z-40 bottom-0 bottom-[40px] md:bottom-[60px] left-[30%] sm:left-[6%]"
                        width="40" height="40" />
                </article>

                <article class="nav-indicator nextArrow">
                    <NuxtImg src="/right-slide-icon.svg" alt="Right Arrow"
                        class="w-10 absolute z-40 bottom-0 bottom-[40px] md:bottom-[60px] left-[60%] sm:left-[12%] lg:left-[10%]"
                        width="40" height="40" />
                </article>
            </section>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${druckPage.acf.bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{druckPage.acf.title}}                             
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3"> {{druckPage.acf.subtitle}} </p>
                        <div class="p-5 py-7 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-lg text-white uppercase flex flex-wrap gap-5"><span>{{druckPage.acf.difficulty_title}} :
                                    <b>{{druckPage.acf.difficulty_value}} </b></span> <span>{{druckPage.acf.trip_price_title}} <b>{{druckPage.acf.trip_price_value}}</b></span> <span> {{druckPage.acf.departure_title}}:
                                    <b> {{druckPage.acf.departure_value}}</b></span></div>
                            <div class="text-xl text-white font-light mt-6">{{druckPage.acf.description}} </div>
                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${druckPage.acf.why_choose_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{druckPage.acf.title}}
                           
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3"> {{druckPage.acf.why_choose_subtitle}}</p>
                        <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{druckPage.acf.option_1_title}}</span> <br>  {{druckPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{druckPage.acf.option_2_title}}</span> <br>  {{druckPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[230px]">
                                    <span class="border-b border-white">{{druckPage.acf.option_3_title}}</span> <br>  {{druckPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{druckPage.acf.option_4_title}}</span> <br>  {{druckPage.acf.option_1_description}}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${druckPage.acf.additional_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class=" text-center md:text-left text-white font-bold uppercase mt-10 text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{druckPage.acf.title}}
                            
                        </h1>
                        <p class="text-center md:text-left text-xl text-white uppercase font-light mt-3">{{druckPage.acf.additional_information_subtitle}}</p>
                        <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-xl text-white font-light"> {{druckPage.acf.information_title}}</div>
                            <div class="text-xl text-white font-light flex flex-wrap gap my-5 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{druckPage.acf.information_1_title}}</b> <br>  {{druckPage.acf.service_provider_title}}:  {{druckPage.acf.service_provider_value}}
                                    <br>  {{druckPage.acf.estimated_cost_title}}:  {{druckPage.acf.estimated_cost_value}} <br> {{druckPage.acf.more_details_title}}: <NuxtLink :to="druckPage.acf.additional_action.url"
                                        class="text-[#76DDFF]">{{druckPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{druckPage.acf.information_2_title}}</b> <br> {{druckPage.acf.service_provider_title}}: {{druckPage.acf.service_provider_value}} <br>
                                    {{druckPage.acf.estimated_cost_title}}: {{druckPage.acf.estimated_cost_value}} <br> {{druckPage.acf.more_details_title}}: <NuxtLink :to="druckPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{druckPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{druckPage.acf.information_3_title}}</b> <br> {{druckPage.acf.airlines_title}} : {{druckPage.acf.airlines_value}}  <br>
                                    {{druckPage.acf.estimated_cost_title}}: {{druckPage.acf.estimated_cost_value}} <br> {{druckPage.acf.more_details_title}}: <NuxtLink :to="druckPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{druckPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{druckPage.acf.information_4_title}}</b> <br>  {{druckPage.acf.operator_title}}:  {{druckPage.acf.operator_value}} <br>
                                    {{druckPage.acf.estimated_cost_title}}: {{druckPage.acf.estimated_cost_value}} <br> {{druckPage.acf.more_details_title}}: <NuxtLink :to="druckPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{druckPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${druckPage.acf.book_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{druckPage.acf.title}}
                           
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light  mt-3 uppercase">{{druckPage.acf.book_subtitle}}
                        </p>
                        <div class="text-center md:text-left text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                            {{druckPage.acf.book_description}}
                        </div>
                        <div class="flex mt-8">
                            <NuxtLink
                            :to="druckPage.acf.book_action.url"
                                class="bg-[#ffffff1f] mx-auto md:ml-0 md:mr-auto delay-300 text-white rounded-xl shadow-md py-2 px-10 font-medium border-2 border-white"
                                > {{druckPage.acf.book_action.title}}</NuxtLink>
                        </div>

                    </div>
                </div>
            </swiper-slide>
        </swiper>
        <PageFooter />

    </div>
</template>





