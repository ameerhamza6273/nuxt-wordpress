<template>
    <section>
        <article>
            <PageNavbar />
            <div class="py-16 bg-[#f8f9fa] min-h-screen flex items-center">
                <div class="container max-w-[500px] mx-auto px-4">
                    <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-100 shadow-xl">

                        <div class="mb-6 text-center">
                            <h3 class="text-xl font-black uppercase tracking-tight text-gray-900">
                                Reset Your <span class="text-[#e31e24]">Password</span>
                            </h3>
                            <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wide">
                                Enter your account details to update password instantly.
                            </p>
                        </div>

                        <form @submit.prevent="handleChangePassword" class="space-y-4">
                            <div>
                                <label
                                    class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Email
                                    / Username</label>
                                <input type="text" v-model="formData.email" required placeholder="e.g. user@example.com"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">New
                                    Password</label>
                                <div class="relative">
                                    <input :type="showResetPassword ? 'text' : 'password'"
                                        v-model="formData.newPassword" required placeholder="Enter new password"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors pr-10" />
                                    <button type="button" @click="showResetPassword = !showResetPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i :class="showResetPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" :disabled="loading"
                                class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                                <i v-if="loading"
                                    class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></i>
                                <span>Update Password</span>
                            </button>
                        </form>

                        <div class="mt-6 text-center border-t border-gray-50 pt-4">
                            <p class="text-[11px] font-bold text-gray-400 uppercase">
                                <NuxtLink to="/checkout-auth" class="text-[#e31e24] hover:underline">Back to Sign In
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
import { ref } from 'vue'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const formData = ref({ email: '', newPassword: '' })
const loading = ref(false)
const showResetPassword = ref(false)

const handleChangePassword = async () => {
    loading.value = true
    try {
        const response = await $fetch(`${WP_URL}/wp-json/custom/v1/change-password`, {
            method: 'POST',
            body: {
                email: formData.value.email,
                newPassword: formData.value.newPassword
            }
        })

        if (response?.success) {
            alert(response.message)
            navigateTo('/checkout-auth')
        }
    } catch (err) {
        console.error("Password modification error:", err)
        alert(err.data?.message || 'Failed to update password. Verify account details.')
    } finally {
        loading.value = false
    }
}
</script>