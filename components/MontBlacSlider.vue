<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation } from "swiper/modules";
import { computed } from 'vue';

const props = defineProps({
    montBlancPage: {
    type: Array,
    required: true,
  },
});

const montBlancPageId = 27397;

// Ensure montBlancPage is not null/undefined before calling .find()
const montBlancPage = computed(() => (props.montBlancPage?.length ? props.montBlancPage.find((m) => m.id === montBlancPageId) : null));
</script>

<template>
    <div v-if="montBlancPage">
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
                :style="{ backgroundImage: `url('${montBlancPage.acf.bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{montBlancPage.acf.title}} <br>                          
                            {{montBlancPage.acf.title_2}}                             
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3"> {{montBlancPage.acf.subtitle}} </p>
                        <div class="p-5 py-7 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-lg text-white uppercase flex flex-wrap gap-5"><span>{{montBlancPage.acf.difficulty_title}} :
                                    <b>{{montBlancPage.acf.difficulty_value}} </b></span> <span>{{montBlancPage.acf.trip_price_title}} <b>{{montBlancPage.acf.trip_price_value}}</b></span> <span> {{montBlancPage.acf.departure_title}}:
                                    <b> {{montBlancPage.acf.departure_value}}</b></span></div>
                            <div class="text-xl text-white font-light mt-6">{{montBlancPage.acf.description}} </div>
                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${montBlancPage.acf.why_choose_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold uppercase mt-10 text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{montBlancPage.acf.title}} <br>                          
                            {{montBlancPage.acf.title_2}}       
                           
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3"> {{montBlancPage.acf.why_choose_subtitle}}</p>
                        <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{montBlancPage.acf.option_1_title}}</span> <br>  {{montBlancPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{montBlancPage.acf.option_2_title}}</span> <br>  {{montBlancPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[230px]">
                                    <span class="border-b border-white">{{montBlancPage.acf.option_3_title}}</span> <br>  {{montBlancPage.acf.option_1_description}}
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">{{montBlancPage.acf.option_4_title}}</span> <br>  {{montBlancPage.acf.option_1_description}}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${montBlancPage.acf.additional_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold uppercase mt-10 text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{montBlancPage.acf.title}} <br>                          
                            {{montBlancPage.acf.title_2}}       
                            
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3">{{montBlancPage.acf.additional_information_subtitle}}</p>
                        <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                            <div class="text-xl text-white font-light"> {{montBlancPage.acf.information_title}}</div>
                            <div class="text-xl text-white font-light flex flex-wrap gap my-5 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{montBlancPage.acf.information_1_title}}</b> <br>  {{montBlancPage.acf.service_provider_title}}:  {{montBlancPage.acf.service_provider_value}}
                                    <br>  {{montBlancPage.acf.estimated_cost_title}}:  {{montBlancPage.acf.estimated_cost_value}} <br> {{montBlancPage.acf.more_details_title}}: <NuxtLink :to="montBlancPage.acf.additional_action.url"
                                        class="text-[#76DDFF]">{{montBlancPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{montBlancPage.acf.information_2_title}}</b> <br> {{montBlancPage.acf.service_provider_title}}: {{montBlancPage.acf.service_provider_value}} <br>
                                    {{montBlancPage.acf.estimated_cost_title}}: {{montBlancPage.acf.estimated_cost_value}} <br> {{montBlancPage.acf.more_details_title}}: <NuxtLink :to="montBlancPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{montBlancPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{montBlancPage.acf.information_3_title}}</b> <br> {{montBlancPage.acf.airlines_title}} : {{montBlancPage.acf.airlines_value}}  <br>
                                    {{montBlancPage.acf.estimated_cost_title}}: {{montBlancPage.acf.estimated_cost_value}} <br> {{montBlancPage.acf.more_details_title}}: <NuxtLink :to="montBlancPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{montBlancPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic"> {{montBlancPage.acf.information_4_title}}</b> <br>  {{montBlancPage.acf.operator_title}}:  {{montBlancPage.acf.operator_value}} <br>
                                    {{montBlancPage.acf.estimated_cost_title}}: {{montBlancPage.acf.estimated_cost_value}} <br> {{montBlancPage.acf.more_details_title}}: <NuxtLink :to="montBlancPage.acf.additional_action.url" class="text-[#76DDFF]">
                                        {{montBlancPage.acf.additional_action.title}}</NuxtLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class=" bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('${montBlancPage.acf.book_bg_image}')` }">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                            style="line-height: 56px;">
                            {{montBlancPage.acf.title}} <br>                          
                            {{montBlancPage.acf.title_2}}       
                           
                        </h1>
                        <p class="text-center md:text-left text-xl text-white font-light mt-3 max-w-[300px] uppercase">{{montBlancPage.acf.book_subtitle}}
                        </p>
                        <div class="text-center md:text-left text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                            {{montBlancPage.acf.book_description}}
                        </div>
                        <div class="flex mt-8">
                            <NuxtLink
                            :to="montBlancPage.acf.book_action.url"
                                class="bg-[#ffffff1f] mx-auto md:ml-0 md:mr-auto delay-300 text-white rounded-xl shadow-md py-2 px-10 font-medium border-2 border-white"
                                > {{montBlancPage.acf.book_action.title}}</NuxtLink>
                        </div>

                    </div>
                </div>
            </swiper-slide>
        </swiper>
        <PageFooter />

    </div>
</template>





