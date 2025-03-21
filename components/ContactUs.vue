<template>
    <div id="contactUs">
      <div class=" bg-[url('/form-image.jpg')] bg-cover bg-top bg-no-repeat px-4 sm:px-10">
        <div class="max-w-[1230px] mx-auto py-16">
  
  
          <h2 class="text-white text-xl md:text-2xl mb-10 border-b uppercase inline-block ">
            <i>Contact Us</i>
          </h2>
          <div class="max-w-[900px] mx-auto ">
  
            <h2 class="text-white uppercase text-3xl md:text-5xl mt-5 font-bold text-center heading-line-ht" style="line-height: 50px;">
              ENQUIRES? <br> REACH OUT ANYTIME!
            </h2>
  
            <form class="mt-10 md:mt-20 ">
  
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                  <label class="text-white text-md font-bold">Name</label>
                  <input type="text" class="w-full px-5 py-2 border bg-[#D9D9D9] rounded mt-1" required />
                </div>
                <div class="md:col-span-3">
                  <label class="text-white text-md font-bold">E-mail</label>
                  <input type="text" class="w-full px-5 py-2 border bg-[#D9D9D9]  rounded mt-1" required />
                </div>
                <div class="mt-4 md:mt-7">
                  <input type="email" class="w-full px-5 py-2 border bg-[#D9D9D9]  rounded " required />
                </div>
  
              </div>
              <div class="mt-4">
                <label class="text-white text-md font-bold">Message</label>
              <textarea class="h-52 w-full px-5 py-3 border bg-[#D9D9D9] rounded mt-2" required></textarea>
              </div>
  
              <div class="flex mt-3 items-center justify-end gap-4">
                <button
                  class="bg-[#ffffff13]   delay-300 text-white rounded-xl shadow-md py-2 px-4 font-bevietnam font-medium border-2 border-white"
                  onclick="location.href='#courses';">Send Message</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  
  </script>
  