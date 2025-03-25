<template>
    <div>
        <PageNavbar />

        <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :spaceBetween="0" :modules="[Navigation]"
            :centeredSlides="true"
            :autoHeight="true" class="w-full relative" :breakpoints="{
                568: { slidesPerView: 1 },
                768: { slidesPerView: 1 },
                1024: { slidesPerView: 1 }
            }">

            <!-- Navigation Arrows -->
            <section class="parallax-slider-navigation cursor-pointer">
                <article class="nav-indicator prevArrow">
                    <img src="/public/left-slide-icon.svg" alt="Left Arrow"
                        class="w-10 absolute z-40 bottom-0 bottom-[60px] left-[35px]  sm:left-[6%]" />
                </article>
                <article class="nav-indicator nextArrow">
                    <img src="/public/right-slide-icon.svg" alt="Right Arrow"
                        class="w-10 absolute z-40 bottom-0 bottom-[60px] left-[90px] sm:left-[12%] lg:left-[10%]" />
                </article>
            </section>
            <!-- Swiper Slides -->
            <swiper-slide>
                <div class="bg-[url('/DRUK-PATH-1.png')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DRUK PATH TREK </h1>
                        <p class="text-xl text-white font-light mt-3">The Ultimate Bhutan Trekking Adventure</p>
                        <div class="p-5 py-7 bg-[#303030b5] rounded-xl mt-16">
                            <div class="text-lg text-white uppercase flex flex-wrap gap-5"><span>Difficulty:
                                    <b>intermediate</b></span> <span>Trip price <b>~$1000/pax</b></span> <span>Departure:
                                    <b>march - may</b></span></div>
                            <div class="text-xl text-white font-light mt-6">Embark on the Druk Path Trek, a breath-taking journey through Bhutan’s ancient trails and soaring mountain passes. This iconic route connects Paro and Thimphu, offering panoramic views of Mount Jumolhari and the Himalayas along the Bhutan-Tibet border. Trek through apple orchards, pine forests, and alpine meadows, where crystal-clear lakes, yak herder camps, and the serene Phadjoding Monastery await. More than just a trek, this is an immersion into Bhutan’s stunning landscapes and rich cultural heritage.</div>
                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/DRUK-PATH-2.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DRUK PATH TREK</h1>
                        <p class="text-xl text-white font-light mt-3">Why Choose the Druk Path Trek?</p>
                        <div class="p-5 bg-[#303030b5] rounded-xl mt-16">
                            <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">Scenic Himalayan Views</span> <br> Witness Mount Jumolhari and the snow-capped peaks along the Bhutan-Tibet border.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">Diverse Landscapes</span> <br> Hike through apple orchards, rhododendron forests, alpine meadows, and crystal clear lakes.
                                </div>
                                <div class="max-w-[230px]">
                                    <span class="border-b border-white">Cultural Encounters</span> <br> Pass traditional yak 
                                    herder camps and visit 
                                    the historic Phadjoding Monastery overlooking Thimphu Valley.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white">Beginner-Friendly</span> <br>
                                    Ideal for those new to 
                                    high-altitude trekking, 
                                    with moderate difficulty 
                                    and well-marked trails.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/DRUK-PATH-3.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DRUK PATH TREK</h1>
                        <p class="text-xl text-white font-light mt-3">WE HAVE EVERYTHING COVERED!</p>
                        <div class="p-5 bg-[#303030b5] rounded-xl mt-16">
                            <div class="text-xl text-white font-light">ADDITIONAL INFORMATION</div>
                            <div class="text-xl text-white font-light flex flex-wrap gap my-5 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic">TRAVEL INSURANCE</b> <br> Service Provider: XXXXXXX
                                    <br> Estimated Cost: XXXXX <br> More Details: <NuxtLink to=""
                                        class="text-[#76DDFF]">Website Link</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic">SIM CARDS</b> <br> Service Provider: XXXXXXX <br>
                                    Estimated Cost: XXXXX <br> More Details: <NuxtLink to="" class="text-[#76DDFF]">
                                        Website Link</NuxtLink>
                                </div>
                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic">FLIGHT TICKETS</b> <br> Airlines: XXXXXXX <br>
                                    Estimated Cost: XXXXX <br> More Details: <NuxtLink to="" class="text-[#76DDFF]">
                                        Website Link</NuxtLink>
                                </div>

                                <div class="max-w-[240px]">
                                    <b class="font-semibold italic">TRAIN TICKEST</b> <br> Operator: XXXXXXX <br>
                                    Estimated Cost: XXXXX <br> More Details: <NuxtLink to="" class="text-[#76DDFF]">
                                        Website Link</NuxtLink>
                                </div>


                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/DRUK-PATH-4.png')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DRUK PATH TREK</h1>
                        <p class="text-xl text-white font-light mt-3 uppercase">Witness the essence of bhutanese landscapes
                        </p>
                        <div class="text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                            Contact us to receive a detailed itinerary and our team will be happy to share insights and help you plan this unforgettable journey through Bhutan’s breath-taking landscapes.
                        </div>

                        <div class="flex mt-8">

                            <button
                                class="bg-[#ffffff1f]   delay-300 text-white rounded-xl shadow-md py-2 px-10 font-bevietnam font-medium border-2 border-white"
                                onclick="location.href='#courses';">Book your journey now!</button>
                        </div>

                    </div>
                </div>
            </swiper-slide>
        </swiper>
        <PageFooter />

    </div>
</template>

<script setup lang="ts">

import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation, Pagination } from "swiper/modules";

</script>
