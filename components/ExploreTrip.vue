<template>
  <div class=" bg-[#0D0D0D]" id="exploreTrips">
    <div class="grid grid-cols-1  md:grid-cols-11 mx-auto">
      <div class="col-span-1 md:col-span-5 self-center px-6 md:px-16 py-16 ">
        <h3 class="text-white  text-xl lg:text-2xl mb-6 lg:mb-16 uppercase border-b inline-block ">
          <i>explore trips</i>
        </h3>
        <h2 class="text-white font-bold text-3xl lg:text-5xl mt-3 uppercase font-bevietnam heading-line-ht"
          style="line-height: 56px;">
          Explore <br>
          the World's <br>
          Best Treks
        </h2>
        <p class="text-lg text-white  mt-5">Pick your ideal journey from our handpicked trekking destinations. Every
          itinerary is designed by experts to ensure a safe and thrilling adventure. Let us guide you through this
          breathtaking adventure of a lifetime.</p>

      </div>
      <div class="col-span-2 hidden md:block">
        <NuxtLink to="/druk-path"><img src="/public/DRUK.png" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
      <div class="col-span-2 hidden md:block" >
        <NuxtLink to="/dolomites"><img src="/public/DOLO.png" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
      <div class="col-span-2 hidden md:block">
        <NuxtLink to="/mont-blanc"><img src="/public/MONTBLANC.png" alt="" class="hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>

    </div>
    <div class="block md:hidden">
      <div class="flex flex-wrap ">
        <NuxtLink to="/druk-path"><img src="/public/DRUK-mob.png" alt="" class="w-1/3 object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
        <NuxtLink to="/dolomites"><img src="/public/DOLO-mob.png" alt="" class="w-1/3 object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
        <NuxtLink to="/mont-blanc"><img src="/public/MONTBLANC-mob.png" alt="" class="w-1/3  object-contain hover:opacity-[0.7] cursor-pointer"></NuxtLink>
      </div>
    </div>

  </div>
</template>