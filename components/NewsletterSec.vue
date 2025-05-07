<script setup>
import { ref, computed } from 'vue';
import { sendEmail } from '@/composables/email';

const props = defineProps({
  movies: {
    type: Array,
    required: true,
  },
});
const movieId = 27248;
const movie = computed(() => props.movies.find((m) => m.id === movieId));


const email = ref("");

const submitEmailForm = async () => {
  // console.log(email.value)
  await postSubscriber(email.value)

  alert('Your subscription has been saved successfully, thank you!')
  email.value = ''
  // const config = useRuntimeConfig();

  // if (email.value) {
  //   const htmlContent = `<p>Email: ${email.value}</p>`;

  //   const body = {
  //     htmlContent,
  //     from: config.public.FROM_EMAIL,
  //     to: config.public.TO_EMAIL,
  //     replyTo: config.public.REPLY_TO_EMAIL,
  //     subject: "New Email Signup", // Subject ko customize kar sakte ho
  //   };

  //   sendEmail(body);
  //   alert("Your email has been submitted.");
  //   email.value = "";
  // } else {
  //   alert("Please enter a valid email.");
  // }
};

</script>

<template>
  <div v-if="movie">
    <div class="h-screen flex flex-col justify-center items-center bg-cover bg-center bg-no-repeat px-5 sm:px-10"
      :style="{ backgroundImage: `url('${movie.acf.bgimage}')` }">
      <div class="max-w-[1230px] mx-auto py-16">

        <div class="max-w-[1230px] md:mx-auto ">
          <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
            style="line-height: 50px;">
            {{ movie.acf.title }}
          </h2>
          <h2 class="text-white uppercase text-3xl md:text-5xl font-bold text-center max-w-3xl mx-auto heading-line-ht"
            style="line-height: 50px;">
            {{ movie.acf.title_2 }}
          </h2>

          <p class="mt-5 sm:mt-10 max-w-2xl text-lg text-white text-center mx-auto">{{ movie.acf.description }}
          </p>
          <div class="flex flex-col sm:flex-row mt-10 sm:mt-14 items-center sm:justify-center ">
            <form @submit.prevent="submitEmailForm" class="text-center">
              <input v-model="email" type="email" placeholder="Enter e-mail address"
                class="text-white bg-[#575757] rounded-xl p-2 px-4 min-w-[330px] border-2 border-[#575757]" required>
              <button type="submit"
                class="bg-[#ffffff1f] sm:ml-4 mt-4 md:mt-0 text-white rounded-xl shadow-md py-2 px-4 font-medium border-2 border-white">
                Sign me up!
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>