<template>
  <div id="contactUs" v-if="movie" class="bg-[url('/form-image.jpg')] bg-cover bg-top bg-no-repeat px-4 sm:px-10">
    <div class="max-w-[1230px] mx-auto py-16">
      <div class="text-center md:text-left">
        <h2 class="text-white text-xl md:text-2xl mb-10 border-b uppercase inline-block">
          <i>Contact Us</i>
        </h2>
      </div>
      <div class="max-w-[900px] mx-auto">
        <h2 class="text-white uppercase text-3xl md:text-5xl mt-5 font-bold text-center leading-tight">
          {{ movie.acf.title }}<br>
          {{ movie.acf.title_2 }}
        </h2>

        <form @submit.prevent="submitForm" class="mt-10 md:mt-20">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Name Input -->
            <div>
              <label class="text-white text-md font-bold">Name</label>
              <input v-model="name" type="text" class="w-full px-5 py-2 border bg-[#D9D9D9] rounded mt-1" required />
            </div>

            <!-- Email Input -->
            <div class="md:col-span-3">
              <label class="text-white text-md font-bold">E-mail</label>
              <input v-model="email" type="email" class="w-full px-5 py-2 border bg-[#D9D9D9] rounded mt-1" required />
            </div>

            <!-- Subject Dropdown -->
            <div class=" relative">
              <label class="text-white text-md font-bold">Subject</label>
              <button @click="toggleDropdown"
                class="inline-flex w-full justify-between items-center bg-[#D9D9D9] px-3 py-2 rounded-md shadow ring-1 ring-gray-300 text-gray-900 mt-1">
                {{ selectedSubject || "Select Subject" }}
                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
                </svg>
              </button>
              <ul v-if="dropdownVisible"
                class="absolute z-10 mt-2 w-full bg-white rounded-md shadow-lg ring-1 ring-black/5">
                <li @click="selectSubject(movie.acf.subject_field_1)"
                  class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                  {{ movie.acf.subject_field_1 }}
                </li>

                <li @click="selectSubject(movie.acf.subject_field_2)"
                  class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                  {{ movie.acf.subject_field_2 }}
                </li>

                <li @click="selectSubject(movie.acf.subject_field_3)"
                  class="px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                  {{ movie.acf.subject_field_3 }}
                </li>

              </ul>
            </div>


          </div>


          <!-- Message Textarea -->
          <div class="mt-4">
            <label class="text-white text-md font-bold">Message</label>
            <textarea v-model="message" class="h-52 w-full px-5 py-3 border bg-[#D9D9D9] rounded mt-2"
              required></textarea>
          </div>

          <!-- Submit Button -->
          <div class="flex mt-3 items-center justify-end gap-4">
            <button type="submit"
              class="bg-[#ffffff13] mx-auto md:ml-auto  md:mr-0 mt-3 text-white rounded-xl shadow-md py-2 px-4 font-medium border-2 border-white">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { sendEmail } from '@/composables/email';

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});

const movieId = 27296;
const movie = computed(() => props.movies.find((m) => m.id === movieId));

const name = ref("");
const email = ref("");
const message = ref("");
const selectedSubject = ref("");
const dropdownVisible = ref(false);

const toggleDropdown = () => {
  dropdownVisible.value = !dropdownVisible.value;
};

const selectSubject = (subject) => {
  selectedSubject.value = subject;
  dropdownVisible.value = false;
};

const submitForm = async () => {
  const config = useRuntimeConfig();
  if (name.value && email.value && message.value && selectedSubject.value) {
    const htmlContent = `
      <p>Name: ${name.value}</p>
      <p>Email: ${email.value}</p>
      <p>Subject: ${selectedSubject.value}</p>
      <p>Message: ${message.value}</p>
    `;

    const body = {
      htmlContent,
      from: config.public.FROM_EMAIL,
      to: config.public.TO_EMAIL,
      replyTo: config.public.REPLY_TO_EMAIL,
      subject: " Contact Us Form Data ",
    };

    sendEmail(body);
    alert("Your details have been sent to us. Someone will be in touch.");
    name.value = "";
    email.value = "";
    message.value = "";
    selectedSubject.value = "";
  } else {
    alert("Please fill in all fields before submitting.");
  }
};
</script>

<style scoped>
/* Additional styling if needed */
</style>
