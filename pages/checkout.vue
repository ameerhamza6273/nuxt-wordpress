<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-16 bg-[#f8f9fa] min-h-screen flex items-center">
        <div class="container max-w-[650px] mx-auto px-4">
          <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-100 shadow-xl">
            
            <h2 class="text-2xl font-black uppercase tracking-tight mb-6 text-gray-900 text-center md:text-left">
              Secure <span class="text-[#e31e24]">Checkout</span>
            </h2>

            <div class="bg-gray-50 p-5 rounded-2xl mb-6 flex justify-between items-center border border-gray-100 shadow-sm">
              <span class="text-xs font-black text-gray-400 uppercase tracking-wider">Final Payable Price:</span>
              <span class="text-2xl font-black text-gray-900">£{{ finalPayablePrice.toFixed(2) }}</span>
            </div>

            <div class="space-y-3 mb-6">
              <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block">Credit / Debit Card</label>
              
              <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 min-h-[100px] flex flex-col justify-center transition-all">
                <div id="card-container" class="w-full"></div>
              </div>
              
              <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight flex items-center gap-1">
                <i class="fa-solid fa-lock text-green-600"></i> Fully Encrypted & Secured by Square.
              </p>
            </div>

            <button @click="handlePaymentSubmit" :disabled="processing || finalPayablePrice <= 0"
              class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-4 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 shadow-md disabled:opacity-40">
              <i v-if="processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></i>
              <span>{{ processing ? 'Authorizing Transaction...' : 'Pay Now' }}</span>
            </button>
            
          </div>
        </div>
      </div>
      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref, onMounted, computed, watchEffect } from 'vue'
import { cartItems } from '~/composables/useCart.js'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

// ⚠️ Credentials link target placeholders
const APPLICATION_ID = 'sandbox-sq0idb-YOUR_ACTUAL_ID' 
const LOCATION_ID = 'LXYZ_YOUR_LOCATION_ID' 

const processing = ref(false)
let cardInstance = null

// ✅ Dynamic Live Price Calculation with reactive tracking layers
const finalPayablePrice = computed(() => {
  if (!cartItems.value || !Array.isArray(cartItems.value) || cartItems.value.length === 0) return 0
  
  return cartItems.value.reduce((total, item) => {
    let priceString = String(item.price)
    let cleanPrice = parseFloat(priceString.replace(/[^0-9.]/g, '')) || 0
    let quantity = parseInt(item.quantity) || 1
    return total + (cleanPrice * quantity)
  }, 0)
})

// 🔄 Force cache busting logic when component builds inside browser
onMounted(() => {
  if (!process.client) return
  
  // ⚡ Try loading fresh state from localStorage to bypass reactive freezes
  try {
    const savedCart = localStorage.getItem('atms_cart')
    if (savedCart) {
      cartItems.value = JSON.parse(savedCart)
    }
  } catch (e) {
    console.error("Failed to parse fresh state from local storage:", e)
  }

  if (window.Square) {
    initializeSquareForm()
    return
  }

  const script = document.createElement('script')
  script.src = 'https://web.squarecdn.com/v1/square.js'
  script.async = true
  script.onload = () => {
    initializeSquareForm()
  }
  document.head.appendChild(script)
})

const initializeSquareForm = async () => {
  if (!window.Square) return
  try {
    const payments = window.Square.payments(APPLICATION_ID, LOCATION_ID)
    cardInstance = await payments.card()
    await cardInstance.attach('#card-container')
  } catch (err) {
    console.error("Square instantiation structural interface failure:", err)
  }
}

const handlePaymentSubmit = async () => {
  if (!cardInstance || processing.value) return
  processing.value = true

  try {
    const result = await cardInstance.tokenize()
    if (result.status === 'OK') {
      const sourceId = result.token
      const amountCents = Math.round(finalPayablePrice.value * 100)

      const paymentResponse = await $fetch(`${WP_URL}/wp-json/custom/v1/process-square-payment`, {
        method: 'POST',
        body: { sourceId, amountCents }
      })

      if (paymentResponse.success) {
        alert('Payment Successful! Order Confirmed. 🔥')
        cartItems.value = []
        if (process.client) {
            localStorage.removeItem('atms_cart')
            localStorage.removeItem('atms_checkout_mode')
        }
        navigateTo('/')
      } else {
        alert(paymentResponse.message || 'Payment capture failed.')
      }
    } else {
      alert('Square Validation Notice: ' + result.errors[0].message)
    }
  } catch (err) {
    console.error("Transaction lifecycle runtime exception failure:", err)
    alert('Failed communicating with core gateway endpoint pipelines.')
  } finally {
    processing.value = false
  }
}
</script>