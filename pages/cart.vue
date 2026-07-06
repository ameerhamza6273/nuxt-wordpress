<template>
    <section>
        <article>
            <PageNavbar />
            <div class="py-12 bg-[#f8f9fa] min-h-screen">
                <div class="container max-w-[1200px] mx-auto px-4">
                    <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-gray-900">
                        Shopping Cart <span class="text-[#e31e24]">({{ cartItems.length }} Items)</span>
                    </h1>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                        <div class="lg:col-span-2 space-y-4">
                            <div v-if="cartItems.length === 0"
                                class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm">
                                <p class="text-gray-400 font-bold text-sm uppercase tracking-wider">Your shopping cart
                                    is empty</p>
                                <button @click="navigateTo('/')"
                                    class="mt-4 bg-[#e31e24] text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-black transition-colors">
                                    Browse Parts
                                </button>
                            </div>

                            <div v-else v-for="item in cartItems" :key="item.id"
                                class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-6 relative overflow-hidden">

                                <div
                                    class="absolute top-0 left-0 bg-emerald-500 text-white font-black text-[8px] uppercase tracking-widest px-3 py-1 rounded-br-xl">
                                    ✓ Guaranteed To Fit
                                </div>

                                <div
                                    class="w-24 h-24 flex-shrink-0 bg-gray-50 rounded-2xl p-2 border border-gray-100 flex items-center justify-center">
                                    <img :src="item.image"
                                        class="max-h-full max-w-full object-contain mix-blend-multiply" />
                                </div>

                                <div class="flex-grow text-center sm:text-left">
                                    <span
                                        class="text-[9px] bg-gray-100 font-black px-2 py-0.5 rounded text-gray-400 uppercase tracking-wider block w-max mx-auto sm:mx-0 mb-1">
                                        SKU: {{ item.sku }}
                                    </span>
                                    <h3
                                        class="text-sm font-black text-gray-900 uppercase tracking-tight line-clamp-2 leading-snug">
                                        {{ item.title }}
                                    </h3>
                                    <p class="text-[11px] text-gray-400 font-bold mt-0.5 uppercase tracking-wide">
                                        Brand: {{ item.brand }}
                                    </p>
                                </div>

                                <div
                                    class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto border-t sm:border-t-0 pt-4 sm:pt-0 border-gray-50">
                                    <div
                                        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl h-9 px-2">
                                        <span
                                            class="text-[10px] font-black uppercase text-gray-400 select-none mr-2">Qty</span>
                                        <input type="number" v-model.number="item.quantity" min="1"
                                            @change="updateStorage"
                                            class="bg-transparent text-xs font-black text-gray-800 outline-none text-center w-8" />
                                    </div>

                                    <div class="text-right min-w-[80px]">
                                        <span class="text-base font-black text-gray-900 tracking-tighter"
                                            v-html="item.price"></span>
                                    </div>

                                    <button @click="removeFromCart(item.id)"
                                        class="text-gray-300 hover:text-[#e31e24] transition-colors p-1">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div v-if="cartItems.length > 0"
                            class="bg-gray-900 text-white p-6 rounded-3xl shadow-xl space-y-6 lg:sticky lg:top-6">
                            <h4 class="text-lg font-black uppercase tracking-wider border-b border-gray-800 pb-3">Order
                                Summary</h4>

                            <div class="space-y-3 font-bold text-xs uppercase tracking-wide text-gray-400">
                                <div class="flex justify-between">
                                    <span>Subtotal Items</span>
                                    <span class="text-white">{{ totalItemsCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span class="text-emerald-400">Calculated Next</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-800 pt-4 flex justify-between items-baseline">
                                <span class="text-sm font-black uppercase tracking-wider">Estimated Total</span>
                                <span class="text-2xl font-black text-white tracking-tight">${{ cartSubtotal.toFixed(2)
                                }}</span>
                            </div>

                            <button @click="proceedToSquareCheckout"
                                class="w-full bg-[#e31e24] hover:bg-white hover:text-black text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 shadow-md">
                                <i class="fa-solid fa-credit-card"></i> Proceed To Checkout
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <PageFooter />
        </article>
    </section>
</template>

<script setup>
import { computed } from 'vue'
import { cartItems, removeFromCart } from '~/composables/useCart.js'

// 1. Total Items Count
const totalItemsCount = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

/**
 * 🟢 Absolute Fix for HTML Entities (e.g., &#36;) and Pricing Numbers
 */
const parseCleanPrice = (priceStr) => {
  if (!priceStr) return 0
  
  let cleanString = String(priceStr)

  // 1. Browser context use karke HTML Entities decode karein (&#36; directly ban jayega $)
  if (process.client) {
    const parser = new DOMParser()
    const dom = parser.parseFromString(cleanString, 'text/html')
    cleanString = dom.body.textContent || dom.body.innerText || cleanString
  }

  // 2. Ab saare HTML tags saaf karein
  cleanString = cleanString.replace(/<\/?[^>]+(>|$)/g, "")
  
  // 3. Ab safely sirf numbers, dots (.) aur commas (,) rukhlein (Ab 36 ka lafda nahi hoga!)
  cleanString = cleanString.replace(/[^0-9.,]/g, "")
  
  // 4. Comma conversion decimal standard ke liye
  if (cleanString.includes(',') && cleanString.includes('.')) {
    cleanString = cleanString.replace(/,/g, "")
  } else if (cleanString.includes(',') && !cleanString.includes('.')) {
    cleanString = cleanString.replace(/,/g, ".")
  }
  
  const finalPrice = parseFloat(cleanString)
  return isNaN(finalPrice) ? 0 : finalPrice
}

// 3. Estimated Total Calculation (Ab accurate price aayegi)
const cartSubtotal = computed(() => {
    return cartItems.value.reduce((total, item) => {
        const singleUnitPrice = parseCleanPrice(item.price)
        return total + (singleUnitPrice * item.quantity)
    }, 0)
})

// Sync updates locally if quantity fields modify manually
const updateStorage = () => {
    if (process.client) {
        localStorage.setItem('atms_cart', JSON.stringify(cartItems.value))
    }
}

const proceedToSquareCheckout = () => {
    if (process.client) {
        // Live Guard: Check if cart is empty before processing
        if (!cartItems.value || cartItems.value.length === 0) {
            alert('Your cart is empty. Please add items before checking out.')
            return
        }

        const activeToken = localStorage.getItem('atms_user_token')
        const guestMode = localStorage.getItem('atms_checkout_mode')

        // 1. Agar user pehle se logged in hai ya guest mode select kar chuka hai, to direct checkout par bhejein
        if (activeToken || guestMode) {
            navigateTo('/checkout')
        } else {
            // 2. Agar user authenticated nahi hai, to checkout query ke sath auth screen par bhejein
            navigateTo('/checkout-auth?checkout=true')
        }
    }
}
</script>