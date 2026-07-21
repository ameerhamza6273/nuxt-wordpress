<template>
  <section>
    <article>
      <PageNavbar />

      <div class="py-16 bg-[#f8f9fa] min-h-screen flex items-center">
        <div class="container mx-auto px-4 max-w-[500px]">
          <div class="bg-white p-8 md:p-12 rounded-[2rem] border border-gray-100 shadow-xl relative overflow-hidden space-y-6">
            <div>
              <h3 class="text-xl font-black uppercase tracking-tight text-gray-900">Admin Login</h3>
              <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Access the product admin panel.</p>
            </div>

            <form @submit.prevent="login" class="space-y-4">
              <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Username</label>
                <input type="text" v-model="username" required placeholder="Enter Username"
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
              </div>

              <div>
                <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Password</label>
                <div class="relative">
                  <input :type="showPassword ? 'text' : 'password'" v-model="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors pr-10" />
                  <button type="button" @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i :class="showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i>
                  </button>
                </div>
              </div>

              <p v-if="loginError" class="text-[#e31e24] text-xs font-bold uppercase">{{ loginError }}</p>

              <button type="submit"
                class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300">
                Sign In
              </button>
            </form>
          </div>
        </div>
      </div>

      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const config = useRuntimeConfig()

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const loginError = ref('')

function login() {
  if (username.value === config.public.importUsername && password.value === config.public.importPassword) {
    if (process.client) sessionStorage.setItem('import_logged_in', '1')
    navigateTo('/admin/products')
  } else {
    loginError.value = 'Invalid username or password.'
  }
}
</script>
