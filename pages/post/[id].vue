<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import { Navigation } from "swiper/modules";
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'

const route = useRoute()
const postId = Number(route.params.id) // Convert string to number

const loading = ref(true);
const headerFooter = ref(null);
const posts = ref([]);
const selectedPost = ref(null);

async function fetchData() {
    loading.value = true;

    const [headerFooterRes, headerFooterErr] = await getHeaderFooter();
    const [postsRes, postsErr] = await getPosts();

    if (headerFooterRes) headerFooter.value = headerFooterRes;
    if (postsRes) {
        posts.value = postsRes;
        selectedPost.value = posts.value.find(post => post.id === postId); // ✅ Filter post by ID
    }

    if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);
    if (postsErr) console.error("Error fetching posts:", postsErr);

    loading.value = false;
}
onMounted(fetchData);

</script>
<template>
    <div>
        <article v-if="loading">
            <SpinLoader />
        </article>

        <article v-else>
            <PageNavbar :headerData="headerFooter" />

            <div v-if="selectedPost">
                <swiper :navigation="{ nextEl: '.nextArrow', prevEl: '.prevArrow' }" :spaceBetween="0"
                    :modules="[Navigation]" :centeredSlides="true" :autoHeight="true" class="w-full relative"
                    :breakpoints="{
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
                            :style="{ backgroundImage: `url('${selectedPost.acf.bg_image || 'https://www.x-trekkers.com/wp-content/uploads/2025/04/DRUK-PATH-1.png'}')` }">
                            <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                                <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                                    style="line-height: 56px;">
                                    {{ selectedPost.acf.title }}<br>
                                    {{ selectedPost.acf.title_2 }}
                                </h1>
                                <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3">
                                    {{ selectedPost.acf.subtitle ?? 'Subtitle not available' }} </p>
                                <div class="p-5 py-7 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                                    <div class="text-lg text-white uppercase flex flex-wrap gap-5">
                                        <span>{{ selectedPost.acf.difficulty_title ?? 'difficulty title empty' }} :
                                            <b>{{ selectedPost.acf.difficulty_value ?? 'difficulty value not empty' }}
                                            </b></span>
                                        <span>{{ selectedPost.acf.trip_price_title ?? 'trip_price title empty' }}
                                            <b>{{ selectedPost.acf.trip_price_value ?? 'trip_price value empty'
                                                }}</b></span> <span>
                                            {{ selectedPost.acf.departure_title ?? 'departure title empty' }}:
                                            <b> {{ selectedPost.acf.departure_value ?? 'departure value empty'
                                                }}</b></span>
                                    </div>
                                    <div class="text-xl text-white font-light mt-6">{{ selectedPost.acf.description ??
                                        'description is empty' }} </div>
                                </div>

                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide>
                        <div class=" bg-cover bg-center bg-no-repeat"
                            :style="{ backgroundImage: `url('${selectedPost.acf.why_choose_bg_image || 'https://www.x-trekkers.com/wp-content/uploads/2025/04/DRUK-PATH-1.png'}')` }">
                            <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                                <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                                    style="line-height: 56px;">
                                    {{ selectedPost.acf.title ?? ' Title Empty' }}

                                </h1>
                                <p class="text-center md:text-left text-xl text-white font-light uppercase mt-3">
                                    {{ selectedPost.acf.why_choose_subtitle ?? 'Subtitle Empty' }}</p>
                                <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                                    <div class="text-xl text-white font-light flex flex-wrap gap my-2 gap-5 sm:gap-16">
                                        <div class="max-w-[240px]">
                                            <span class="border-b border-white block">
                                                {{ selectedPost.acf.option_1_title ?? 'Option 1 Title Empty' }}
                                            </span>
                                            <span>
                                                {{ selectedPost.acf.option_1_description ?? 'Option 1 Description Empty'
                                                }}
                                            </span>
                                        </div>
                                        <div class="max-w-[240px]">
                                            <span class="border-b border-white block">
                                                {{ selectedPost.acf.option_2_title ?? 'Option 2 Title Empty' }}
                                            </span>
                                            <span>
                                                {{ selectedPost.acf.option_2_description ?? 'Option 2 Description Empty'
                                                }}
                                            </span>
                                        </div>
                                        <div class="max-w-[230px]">
                                            <span class="border-b border-white block">
                                                {{ selectedPost.acf.option_3_title ?? 'Option 3 Title Empty' }}
                                            </span>
                                            <span>
                                                {{ selectedPost.acf.option_3_description ?? 'Option 3 Description Empty'
                                                }}
                                            </span>
                                        </div>
                                        <div class="max-w-[240px]">
                                            <span class="border-b border-white block">
                                                {{ selectedPost.acf.option_4_title ?? 'Option 4 Title Empty' }}
                                            </span>
                                            <span>
                                                {{ selectedPost.acf.option_4_description ?? 'Option 4 Description Empty'
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide>
                        <div class=" bg-cover bg-center bg-no-repeat"
                            :style="{ backgroundImage: `url('${selectedPost.acf.additional_bg_image || 'https://www.x-trekkers.com/wp-content/uploads/2025/04/DRUK-PATH-1.png'}')` }">
                            <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                                <h1 class=" text-center md:text-left text-white font-bold uppercase mt-10 text-3xl md:text-5xl heading-line-ht"
                                    style="line-height: 56px;">
                                    {{ selectedPost.acf.title ?? 'Title Empty' }}

                                </h1>
                                <p class="text-center md:text-left text-xl text-white uppercase font-light mt-3">
                                    {{ selectedPost.acf.additional_information_subtitle ?? 'subtitle Empty' }}</p>
                                <div class="p-5 bg-[#30303091] backdrop-blur-sm rounded-xl mt-16">
                                    <div class="text-xl text-white font-light">
                                        {{ selectedPost.acf.information_title ?? 'Information Title Empty' }}
                                    </div>

                                    <div class="text-xl text-white font-light flex flex-wrap gap my-5 gap-5 sm:gap-16">
                                        <div class="max-w-[240px]">
                                            <b class="font-semibold italic">
                                                {{ selectedPost.acf.information_1_title ?? 'Information 1 Title Empty'
                                                }}
                                            </b><br>
                                            {{ selectedPost.acf.service_provider_title ?? 'Service Provider Title' }}:
                                            {{ selectedPost.acf.service_provider_value ?? 'Service Provider Value'
                                            }}<br>
                                            {{ selectedPost.acf.estimated_cost_title ?? 'Estimated Cost Title' }}:
                                            {{ selectedPost.acf.estimated_cost_value ?? 'Estimated Cost Value' }}<br>
                                            {{ selectedPost.acf.more_details_title ?? 'More Details' }}:
                                            <NuxtLink :to="selectedPost.acf.additional_action?.url ?? '#'"
                                                class="text-[#76DDFF]">
                                                {{ selectedPost.acf.additional_action?.title ?? 'Read More' }}
                                            </NuxtLink>
                                        </div>

                                        <div class="max-w-[240px]">
                                            <b class="font-semibold italic">
                                                {{ selectedPost.acf.information_2_title ?? 'Information 2 Title Empty'
                                                }}
                                            </b><br>
                                            {{ selectedPost.acf.service_provider_title ?? 'Service Provider Title' }}:
                                            {{ selectedPost.acf.service_provider_value ?? 'Service Provider Value'
                                            }}<br>
                                            {{ selectedPost.acf.estimated_cost_title ?? 'Estimated Cost Title' }}:
                                            {{ selectedPost.acf.estimated_cost_value ?? 'Estimated Cost Value' }}<br>
                                            {{ selectedPost.acf.more_details_title ?? 'More Details' }}:
                                            <NuxtLink :to="selectedPost.acf.additional_action?.url ?? '#'"
                                                class="text-[#76DDFF]">
                                                {{ selectedPost.acf.additional_action?.title ?? 'Read More' }}
                                            </NuxtLink>
                                        </div>

                                        <div class="max-w-[240px]">
                                            <b class="font-semibold italic">
                                                {{ selectedPost.acf.information_3_title ?? 'Information 3 Title Empty'
                                                }}
                                            </b><br>
                                            {{ selectedPost.acf.airlines_title ?? 'Airlines Title' }}:
                                            {{ selectedPost.acf.airlines_value ?? 'Airlines Value' }}<br>
                                            {{ selectedPost.acf.estimated_cost_title ?? 'Estimated Cost Title' }}:
                                            {{ selectedPost.acf.estimated_cost_value ?? 'Estimated Cost Value' }}<br>
                                            {{ selectedPost.acf.more_details_title ?? 'More Details' }}:
                                            <NuxtLink :to="selectedPost.acf.additional_action?.url ?? '#'"
                                                class="text-[#76DDFF]">
                                                {{ selectedPost.acf.additional_action?.title ?? 'Read More' }}
                                            </NuxtLink>
                                        </div>

                                        <div class="max-w-[240px]">
                                            <b class="font-semibold italic">
                                                {{ selectedPost.acf.information_4_title ?? 'Information 4 Title Empty'
                                                }}
                                            </b><br>
                                            {{ selectedPost.acf.operator_title ?? 'Operator Title' }}:
                                            {{ selectedPost.acf.operator_value ?? 'Operator Value' }}<br>
                                            {{ selectedPost.acf.estimated_cost_title ?? 'Estimated Cost Title' }}:
                                            {{ selectedPost.acf.estimated_cost_value ?? 'Estimated Cost Value' }}<br>
                                            {{ selectedPost.acf.more_details_title ?? 'More Details' }}:
                                            <NuxtLink :to="selectedPost.acf.additional_action?.url ?? '#'"
                                                class="text-[#76DDFF]">
                                                {{ selectedPost.acf.additional_action?.title ?? 'action empty' }}
                                            </NuxtLink>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide>
                        <div class=" bg-cover bg-center bg-no-repeat"
                            :style="{ backgroundImage: `url('${selectedPost.acf.book_bg_image || 'https://www.x-trekkers.com/wp-content/uploads/2025/04/DRUK-PATH-1.png'} ')` }">
                            <div class="max-w-[1260px] mx-auto py-24 sm:py-32 px-5 sm:px-8 lg:px-5">
                                <h1 class="text-center md:text-left text-white font-bold mt-10 uppercase text-3xl md:text-5xl heading-line-ht"
                                    style="line-height: 56px;">
                                    {{ selectedPost.acf.title ?? ' Title Empty' }}

                                </h1>
                                <p class="text-center md:text-left text-xl text-white font-light  mt-3 uppercase">
                                    {{ selectedPost.acf.book_subtitle ?? ' subtitle Empty' }}
                                </p>
                                <div
                                    class="text-center md:text-left text-lg text-white font-light  mt-16 md:max-w-[330px] ">
                                    {{ selectedPost.acf.book_description ?? ' description Empty' }}
                                </div>
                                <div class="flex mt-8">
                                    <NuxtLink :to="selectedPost.acf.book_action.url ?? '#'"
                                        class="bg-[#ffffff1f] mx-auto md:ml-0 md:mr-auto delay-300 text-white rounded-xl shadow-md py-2 px-10 font-medium border-2 border-white">
                                        {{ selectedPost.acf.book_action.title ?? 'action empty' }}</NuxtLink>
                                </div>

                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
            <article v-else>
                <div class="text-center py-20 text-white text-2xl mt-20">
                    🙁 Koi post nahi mila is ID ke sath.
                </div>
            </article>


            <PageFooter :footerData="headerFooter" />
        </article>
    </div>
</template>
