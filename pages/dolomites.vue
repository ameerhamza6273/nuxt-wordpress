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
                        class="w-10 absolute z-40 bottom-0 bottom-[40px]  md:bottom-[60px] left-[35px]  sm:left-[6%]" />
                </article>
                <article class="nav-indicator nextArrow">
                    <img src="/public/right-slide-icon.svg" alt="Right Arrow"
                        class="w-10 absolute z-40 bottom-0 bottom-[40px]  md:bottom-[60px] left-[90px] sm:left-[12%] lg:left-[10%]" />
                </article>
            </section>
            <!-- Swiper Slides -->
            <swiper-slide>
                <div class="bg-[url('/dolomites-1.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DOLOMITES <br>
                            OF CORTINA</h1>
                        <p class="text-xl text-white font-light mt-3">A GUIDED HIKING ADVENTURE</p>
                        <div class="p-5 py-7 bg-[#303030b5] rounded-xl mt-6">
                            <div class="text-lg text-white uppercase flex flex-wrap gap-5"><span>Difficulty:
                                    <b>beginner</b></span> <span>Trip price <b>~$1000/pax</b></span> <span>Departure:
                                    <b>August - September</b></span></div>
                            <div class="text-xl text-white font-light mt-6">Experience the awe-inspiring beauty of the
                                Dolomites on
                                this 7-day guided trek through Italy’s most iconic mountain landscapes. From the
                                dramatic peaks of Tre Cime de Lavaredo to the hidden valleys of Pocol, explore rugged
                                trails, alpine lakes, and historic refuges, all while carrying only a light daypack.
                                With stunning panoramas, diverse terrain, and rich cultural heritage, this adventure is
                                perfect for hikers of all levels.</div>
                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/dolomites-2.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DOLOMITES <br>
                            OF CORTINA</h1>
                        <p class="text-xl text-white font-light mt-3">WHY CHOOSE THE DOLOMITES OF CORTINA TREK?</p>
                        <div class="p-5 bg-[#303030b5] rounded-xl mt-6">
                            <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Spectacular Scenery</span> <br> Hike beneath the
                                    Cinque Torri, Tofane Massif, and Tre Cime de Lavaredo, with jaw-dropping mountain
                                    views at every turn.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Varied Terrain</span> <br> Traverse forests,
                                    high-altitude meadows, and rocky passes, stopping at crystal-clear lakes and
                                    traditional mountain refuges.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Perfect for All Levels</span> <br> A mix of easy
                                    to intermediate trails, with expert guides leading the way.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Hassle-Free Adventure</span> <br>
                                    Stay in comfortable mountain lodges, with luggage transfers so you can trek light.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/dolomites-3.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DOLOMITES <br>
                            OF CORTINA</h1>
                        <p class="text-xl text-white font-light mt-3">WE HAVE EVERYTHING COVERED!</p>
                        <div class="p-5 bg-[#303030b5] rounded-xl mt-6">
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
                <div class="bg-[url('/dolomites-4.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            DOLOMITES <br>
                            OF CORTINA</h1>
                        <p class="text-xl text-white font-light mt-3 uppercase">uncover the secrets of the mountains!
                        </p>
                        <div class="text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                            Contact us to receive a detailed itinerary and start planning your next adventure in Italy’s
                            most stunning mountain range!
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
