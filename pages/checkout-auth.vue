<template>
    <section>
        <article>
            <PageNavbar />
            <div class="py-16 bg-[#f8f9fa] min-h-screen flex items-center">
                <div :class="['container mx-auto px-4', isCheckoutMode ? 'max-w-[1100px]' : 'max-w-[700px]']">

                    <div
                        :class="['bg-white p-8 md:p-12 rounded-[2rem] border border-gray-100 shadow-xl relative overflow-hidden grid grid-cols-1 gap-12', isCheckoutMode ? 'md:grid-cols-2' : '']">

                        <div
                            :class="['space-y-6', isCheckoutMode ? 'md:pr-12 md:border-r border-gray-100' : 'max-w-[500px] mx-auto w-full']">
                            <div>
                                <h3 class="text-xl font-black uppercase tracking-tight text-gray-900">Returning
                                    Customers</h3>
                                <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Sign in to speed up the
                                    process.</p>
                            </div>

                            <form @submit.prevent="handleLogin" class="space-y-4">
                                <div>
                                    <label
                                        class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Email
                                        / Username</label>
                                    <input type="text" v-model="loginForm.email" required
                                        placeholder="Enter Email or Username"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                                </div>

                                <div>
                                    <label
                                        class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Password</label>
                                    <div class="relative">
                                        <input :type="showLoginPassword ? 'text' : 'password'"
                                            v-model="loginForm.password" required placeholder="••••••••"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors pr-10" />
                                        <button type="button" @click="showLoginPassword = !showLoginPassword"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i
                                                :class="showLoginPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <NuxtLink to="/forgot-password"
                                        class="text-[11px] font-bold text-gray-400 hover:text-[#e31e24] transition-colors uppercase">
                                        Forgot Password?
                                    </NuxtLink>
                                </div>

                                <button type="submit" :disabled="loadingLogin"
                                    class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                                    <i v-if="loadingLogin"
                                        class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></i>
                                    <span>Sign In & Continue</span>
                                </button>
                            </form>
                        </div>

                        <div v-if="isCheckoutMode" class="space-y-8 md:pl-6 flex flex-col justify-between">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-xl font-black uppercase tracking-tight text-gray-900">New Customers
                                    </h3>
                                    <p class="text-xs text-gray-400 font-bold mt-1 uppercase">Create an account or
                                        checkout securely as a guest.</p>
                                </div>

                                <div class="space-y-3">
                                    <button @click="proceedAsGuest"
                                        class="w-full bg-[#e31e24] hover:bg-black text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-user-secret"></i> Checkout As Guest
                                    </button>

                                    <div class="relative flex py-2 items-center">
                                        <div class="flex-grow border-t border-gray-100"></div>
                                        <span
                                            class="flex-shrink mx-4 text-[10px] text-gray-300 font-black uppercase tracking-widest">OR</span>
                                        <div class="flex-grow border-t border-gray-100"></div>
                                    </div>

                                    <button @click="navigateTo('/register')"
                                        class="w-full bg-white border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-black py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-user-plus"></i> Create An Account
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center border-t border-gray-100 pt-6 w-full">
                            <p class="text-[12px] font-bold text-gray-400 uppercase">
                                Don't have an account yet?
                                <NuxtLink to="/register" class="text-[#e31e24] hover:underline ml-1">Create an account
                                </NuxtLink>
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
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { cartItems } from '~/composables/useCart.js'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'
const route = useRoute()

const loginForm = ref({ email: '', password: '' })
const loadingLogin = ref(false)
const showLoginPassword = ref(false)

const isCheckoutMode = computed(() => {
    return route.query.checkout === 'true'
})

// Live Guard: Page load par check karein ke cart empty to nahi
onMounted(() => {
    if (process.client && isCheckoutMode.value) {
        if (!cartItems.value || cartItems.value.length === 0) {
            navigateTo('/')
        }
    }
})

const handleLogin = async () => {
    loadingLogin.value = true
    try {
        const response = await $fetch(`${WP_URL}/wp-json/custom/v1/login`, {
            method: 'POST',
            body: {
                username: loginForm.value.email,
                password: loginForm.value.password
            }
        })

        if (response?.success && response?.token) {
            if (process.client) {
                // Production-ready keys setup
                localStorage.setItem('atms_user_token', response.token)
                localStorage.setItem('atms_user_display', response.user_display_name || '')
                localStorage.setItem('atms_user_email', response.user_email || loginForm.value.email)
                localStorage.removeItem('atms_checkout_mode') // Remove guest mode if logging in
            }
            if (isCheckoutMode.value) {
                navigateTo('/checkout')
            } else {
                navigateTo('/')
            }
        }
    } catch (err) {
        console.error("Authentication exception:", err)
        alert(err.data?.message || 'Invalid credentials, please try again.')
    } finally {
        loadingLogin.value = false
    }
}

const proceedAsGuest = () => {
    if (process.client) {
        if (!cartItems.value || cartItems.value.length === 0) {
            alert('Your cart is empty.')
            navigateTo('/')
            return
        }
        localStorage.setItem('atms_checkout_mode', 'guest')
    }
    navigateTo('/checkout')
}
</script>