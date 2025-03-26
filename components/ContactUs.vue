<template>
  <div id="contactUs" class="bg-[url('/form-image.jpg')] bg-cover bg-top bg-no-repeat px-4 sm:px-10">
    <div class="max-w-[1230px] mx-auto py-16">
      <div class="text-center md:text-left">
        <h2 class="text-white text-xl md:text-2xl mb-10 border-b uppercase inline-block">
          <i>Contact Us</i>
        </h2>
      </div>
      <div class="max-w-[900px] mx-auto">
        <h2 class="text-white uppercase text-3xl md:text-5xl mt-5 font-bold text-center leading-tight">
          ENQUIRIES? <br /> REACH OUT ANYTIME!
        </h2>

        <form @submit.prevent="handleSubmit" class="mt-10 md:mt-20">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Name Input -->
            <div>
              <label class="text-white text-md font-bold">Name</label>
              <input type="text" class="w-full px-5 py-2 border bg-[#D9D9D9] rounded mt-1" required />
            </div>

            <!-- Email Input -->
            <div class="md:col-span-3">
              <label class="text-white text-md font-bold">E-mail</label>
              <input type="email" class="w-full px-5 py-2 border bg-[#D9D9D9] rounded mt-1" required />
            </div>

            <!-- Dropdown 1 -->
            <div class="mt-4 md:mt-7 relative">
              <button @click="toggleDropdown('dropdown1')"
                class="inline-flex w-full justify-between items-center bg-[#D9D9D9] px-3 py-2 rounded-md shadow ring-1 ring-gray-300 text-gray-900">
                Select Subject
                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                  data-slot="icon">
                  <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
                </svg>
              </button>
              <ul v-if="dropdown1"
                class="absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black/5">
                <li class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Account Settings</li>
                <li class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Support</li>
                <li class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">License</li>
                <li class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">Sign Out</li>
              </ul>
            </div>


          </div>

          <!-- Message Textarea -->
          <div class="mt-4">
            <label class="text-white text-md font-bold">Message</label>
            <textarea class="h-52 w-full px-5 py-3 border bg-[#D9D9D9] rounded mt-2" required></textarea>
          </div>

          <!-- Submit Button -->
          <div class="flex mt-3 items-center justify-end gap-4">
            <button type="submit"
              class="bg-[#ffffff13] text-white rounded-xl shadow-md py-2 px-4 font-medium border-2 border-white">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const dropdown1 = ref(false);

const toggleDropdown = (dropdown) => {

  dropdown1.value = !dropdown1.value;

};

const handleSubmit = () => {
  alert("Message Sent Successfully!");
};
</script>

<style scoped>
/* Dropdown arrow transition */
button span {
  transition: transform 0.3s;
}
</style>
