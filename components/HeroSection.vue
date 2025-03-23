<template>
    <div class=" pl-6 sm:pl-8 md:pl-16 md:pt-24 bg-[url('/LandingPageMainImage.jpg')] bg-cover bg-center bg-no-repeat" id="home">
        <div class="grid md:grid-cols-2 grid-cols-1 py-24 sm:py-32">
            <div class=" self-end mt-10 md:mt-0">
                <h1 class="text-white font-bold text-3xl md:text-5xl font-bevietnam heading-line-ht" style="line-height: 56px;">
                    FIND <br>
                    YOURSELF <br>
                    OUTSIDE</h1>
                <p class="text-lg text-white max-w-[430px] mt-3 pr-4">The platform that turns aspirations and dreams into the exploration iconic environments across the globe. Join us now and unleash your potential in the world of trekking!</p>
                <div class="flex mt-8">
                    
                    <button
                        class="bg-[#ffffff1f]   delay-300 text-white rounded-xl shadow-md py-2 px-4 font-bevietnam font-medium border-2 border-white"
                        onclick="location.href='#courses';">Start your journey with us now!</button>
                </div>
            </div>
            <HeroSecSlider />

        </div>
    </div>
</template>