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
                <div class="bg-[url('/mont-blanc-1.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold uppercase mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            Tour du <br>
                            mont blanc </h1>
                        <p class="text-xl text-white font-light mt-3">A Complete 9-Day Alpine Adventure</p>
                        <div class="p-5 py-7 bg-[#303030b5] rounded-xl mt-6">
                            <div class="text-lg text-white uppercase flex flex-wrap gap-5"><span>Difficulty:
                                    <b>Expert</b></span> <span>Trip price <b>~$2000/pax</b></span> <span>Departure:
                                    <b>June - September</b></span></div>
                            <div class="text-xl text-white font-light mt-6">Embark on an extraordinary 9-day trek through France, Italy, and Switzerland, circling the legendary Mont Blanc massif. 
                                This guided adventure takes you off the beaten path, revealing hidden trails, stunning glaciers, and diverse alpine landscapes. From the balconies of Mont Blanc to the Grand Col Ferret, experience the Alps in their full glory—with luggage transport included, so you can hike light and focus on the journey.</div>
                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/mont-blanc-2.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white uppercase font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            Tour du <br>
                            mont blanc </h1>
                        <p class="text-xl text-white font-light mt-3">Why Choose Tour du Mont Blanc?</p>
                        <div class="p-5 bg-[#303030b5] rounded-xl mt-6">
                            <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Complete Tour</span> <br> 
                                    Experience the full circuit 
                                    of Mont Blanc, crossing three countries and exploring remote alpine trails.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Unique Cultural Route </span> <br> Follow less-traveled paths through French, Italian, 
and Swiss villages that 
offers unique traditions, cuisines, and less crowd at unforgettable landscapes.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic">Glacier Vistas</span> <br> 
                                    Get up close to snowy towering glaciers and dramatic peaks along the route.
                                </div>
                                <div class="max-w-[240px]">
                                    <span class="border-b border-white italic"> Expert Guidance</span> <br>
                                   
Led by experienced mountain guides, ensuring safety and insider knowledge 
along the way.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </swiper-slide>
            <swiper-slide>
                <div class="bg-[url('/mont-blanc-3.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white font-bold uppercase mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            Tour du <br>
                            mont blanc </h1>
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
                <div class="bg-[url('/mont-blanc-4.jpg')] bg-cover bg-center bg-no-repeat">
                    <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                        <h1 class="text-white uppercase font-bold mt-10 text-3xl md:text-5xl font-bevietnam heading-line-ht"
                            style="line-height: 56px;">
                            Tour du <br>
                            mont blanc </h1>
                        <p class="text-xl text-white font-light mt-3 uppercase">Venture into mont blanc’s<br>
                            most spectacular trails
                        </p>
                        <div class="text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                            Contact us to receive a detailed itinerary and start planning your next adventure in Italy’s most stunning mountain range!

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
