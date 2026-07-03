<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-16 bg-[#f8f9fa] ">
        <div class="container max-w-[550px] mx-auto px-4">
          <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-100 shadow-xl">

            <div class="mb-8 text-center">
              <h3 class="text-2xl font-black uppercase tracking-tight text-gray-900">
                Create An <span class="text-[#e31e24]">Account</span>
              </h3>
              <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wide">
                Join us to track orders and manage your garage.
              </p>
            </div>

            <form @submit.prevent="handleRegister" class="space-y-4">

              <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">
                  Full name / Username
                </label>
                <input type="text" v-model="registerForm.username" required placeholder="e.g. jondoe123"
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
              </div>

              <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">
                  Email Address
                </label>
                <input type="email" v-model="registerForm.email" required placeholder="e.g. jon@example.com"
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
              </div>

              <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">
                  Password
                </label>
                <div class="relative">
                  <input :type="showRegisterPassword ? 'text' : 'password'" v-model="registerForm.password" required placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors pr-10" />
                  <button type="button" @click="showRegisterPassword = !showRegisterPassword" 
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i :class="showRegisterPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i>
                  </button>
                </div>
              </div>

              <button type="submit" :disabled="loadingRegister"
                class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                <i v-if="loadingRegister" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></i>
                <span>Register & Continue</span>
              </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-50 pt-4">
              <p class="text-[11px] font-bold text-gray-400 uppercase">
                Already have an account?
                <NuxtLink to="/checkout-auth" class="text-[#e31e24] hover:underline ml-1">Sign In</NuxtLink>
              </p>
            </div>

          </div>
        </div>
      </div>
      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const registerForm = ref({ username: '', email: '', password: '' })
const loadingRegister = ref(false)
const showRegisterPassword = ref(false) // Dynamic state tracking variable for layout behavior

const handleRegister = async () => {
    loadingRegister.value = true
    try {
        // ✅ Direct hit mapped straight to custom_app_users segment
        const response = await $fetch(`${WP_URL}/wp-json/custom/v1/register`, {
            method: 'POST',
            body: {
                username: registerForm.value.username,
                email: registerForm.value.email,
                password: registerForm.value.password
            }
        })

        if (response?.code === 200 || response?.success) {
            alert('Registration Successful! Redirecting to login...')
            navigateTo('/checkout-auth')
        } else {
            alert(response?.message || 'Registration failed, try a different username/email.')
        }
    } catch (err) {
        console.error("Registration endpoint error structure:", err)
        alert(err.data?.message || 'Error communicating with custom user framework.')
    } finally {
        loadingRegister.value = false
    }
}
</script>