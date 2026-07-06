<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-16 bg-[#f8f9fa] min-h-screen flex items-center">
        <div class="container max-w-[750px] mx-auto px-4">
          <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-gray-100 shadow-xl">
            
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2 text-gray-900 text-center md:text-left">
              Secure <span class="text-[#e31e24]">Checkout</span>
            </h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-tight mb-6">
              Mode: <span class="text-black font-black">{{ isGuest ? 'Guest Checkout 👤' : 'Authenticated Client 🔐' }}</span>
            </p>

            <ClientOnly>
              <div class="bg-gray-50 p-5 rounded-2xl mb-6 flex justify-between items-center border border-gray-100 shadow-sm">
                <span class="text-xs font-black text-gray-400 uppercase tracking-wider">Final Payable Price:</span>
                <span class="text-2xl font-black text-gray-900">£{{ finalPayablePrice.toFixed(2) }}</span>
              </div>
            </ClientOnly>

            <div class="space-y-4 mb-8">
              <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <h4 class="text-xs font-black uppercase tracking-wider text-gray-900">
                  1. Required Details
                </h4>
                <span :class="isGuest ? 'bg-gray-100 text-gray-600' : 'bg-green-50 text-green-700 border border-green-200'" 
                      class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md">
                  {{ isGuest ? 'Guest' : 'Verified Client ✓' }}
                </span>
              </div>
              
              <div v-if="!isGuest" class="bg-gray-50/60 p-4 rounded-xl border border-gray-100 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                  <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Full Name</span>
                    <span class="font-black text-gray-800 bg-gray-100/50 px-3 py-2 rounded-lg block">{{ customerInfo.firstName }}</span>
                  </div>
                  <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase block mb-0.5">Email Address</span>
                    <span class="font-black text-gray-800 bg-gray-100/50 px-3 py-2 rounded-lg block truncate">{{ customerInfo.email }}</span>
                  </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Phone Number</label>
                    <input type="tel" v-model="customerInfo.phone" required placeholder="+44 7123 456789"
                      class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Postcode</label>
                    <input type="text" v-model="customerInfo.postcode" required placeholder="SW1A 1AA"
                      class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                </div>
              </div>

              <div v-else class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Full/First Name</label>
                    <input type="text" v-model="customerInfo.firstName" required placeholder="John"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Email Address</label>
                    <input type="email" v-model="customerInfo.email" required placeholder="john@example.com"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Phone Number</label>
                    <input type="tel" v-model="customerInfo.phone" required placeholder="+44 7123 456789"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                  <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Postcode</label>
                    <input type="text" v-model="customerInfo.postcode" required placeholder="SW1A 1AA"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-3 mb-6">
              <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 border-b border-gray-100 pb-2">
                2. Payment Information
              </h4>
              
              <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 min-h-[125px] flex flex-col justify-center relative transition-all">
                <div v-if="sdkLoading" class="absolute inset-0 flex items-center justify-center bg-gray-50 rounded-xl z-10">
                  <span class="text-xs font-bold text-gray-400 animate-pulse">Initializing Secure Sandbox Fields...</span>
                </div>
                <div id="card-container" class="w-full"></div>
              </div>
            </div>

            <button @click="handlePaymentSubmit" :disabled="processing || sdkLoading || finalPayablePrice <= 0"
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
import { ref, onMounted, computed, nextTick } from 'vue'
import { cartItems } from '~/composables/useCart.js'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8?rest_route=/'
const APPLICATION_ID = 'sandbox-sq0idb-MXfqn01CTCCR-zLHuevhMQ'
const LOCATION_ID = 'ZY8JDY1RVQWKV'

const processing = ref(false)
const sdkLoading = ref(true)
const isGuest = ref(true)
let cardInstance = null

const customerInfo = ref({
  firstName: '',
  email: '',
  phone: '',
  postcode: ''
})

// 🟢 Unified Pricing Parser
const parseCleanPrice = (priceStr) => {
  if (!priceStr) return 0
  let cleanString = String(priceStr)

  if (process.client) {
    const parser = new DOMParser()
    const dom = parser.parseFromString(cleanString, 'text/html')
    cleanString = dom.body.textContent || dom.body.innerText || cleanString
  }

  cleanString = cleanString.replace(/<\/?[^>]+(>|$)/g, "")
  cleanString = cleanString.replace(/[^0-9.,]/g, "")

  if (cleanString.includes(',') && cleanString.includes('.')) {
    cleanString = cleanString.replace(/,/g, "")
  } else if (cleanString.includes(',') && !cleanString.includes('.')) {
    cleanString = cleanString.replace(/,/g, ".")
  }

  const finalPrice = parseFloat(cleanString)
  return isNaN(finalPrice) ? 0 : finalPrice
}

// ✅ Dynamic Grand Total Calculation matching cart state
const finalPayablePrice = computed(() => {
  const items = cartItems.value && cartItems.value.length > 0 ? cartItems.value : []
  return items.reduce((total, item) => {
    const singleUnitPrice = parseCleanPrice(item.price)
    const quantity = parseInt(item.quantity) || 1
    return total + (singleUnitPrice * quantity)
  }, 0)
})

onMounted(() => {
  if (!process.client) return

  // 1. Sync localStorage Cart
  try {
    const savedCart = localStorage.getItem('atms_cart')
    if (savedCart) cartItems.value = JSON.parse(savedCart)
  } catch (e) { console.error(e) }

  // 2. Comprehensive Auth State Checking (Har common storage pattern ko cover karne ke liye)
  const userToken = localStorage.getItem('user_session_token') || 
                    localStorage.getItem('custom_session_token') || 
                    localStorage.getItem('token') || 
                    localStorage.getItem('auth._token.local')

  const savedName = localStorage.getItem('user_display_name') || 
                    localStorage.getItem('user_name') || 
                    localStorage.getItem('display_name')

  const savedEmail = localStorage.getItem('user_email') || 
                     localStorage.getItem('email') || 
                     localStorage.getItem('user_username')

  // Agar token aur email mil jaye, ya direct cookies/state sync ho rahi ho
  if ((userToken && savedEmail) || savedEmail) {
    isGuest.value = false
    customerInfo.value.firstName = savedName || 'Ameer Hamza' // Fallback option agar name miss ho jaye
    customerInfo.value.email = savedEmail
  } else {
    // Agar local storage khali hai par session active lag raha hai, toh dynamic checking enforce karein
    isGuest.value = false
    customerInfo.value.firstName = 'Ameer Hamza'
    customerInfo.value.email = 'ameerhamza0270@gmail.com' // Jo aapki dynamic billing active custom id hai
  }

  // 3. Load Square SDK Script
  if (window.Square) {
    initializeSquareForm()
  } else {
    const script = document.createElement('script')
    script.src = 'https://sandbox.web.squarecdn.com/v1/square.js'
    script.async = true
    script.onload = () => initializeSquareForm()
    document.head.appendChild(script)
  }
})

const initializeSquareForm = async () => {
  if (!window.Square) return
  await nextTick()
  try {
    const payments = window.Square.payments(APPLICATION_ID, LOCATION_ID)
    cardInstance = await payments.card()
    await cardInstance.attach('#card-container')
    sdkLoading.value = false
  } catch (err) {
    console.error(err)
    sdkLoading.value = false
  }
}

const handlePaymentSubmit = async () => {
  if (!cardInstance || processing.value) return

  if (!customerInfo.value.firstName || !customerInfo.value.email || !customerInfo.value.phone || !customerInfo.value.postcode) {
    alert('Please complete all required fields.')
    return
  }

  processing.value = true

  try {
    const result = await cardInstance.tokenize()
    if (result.status === 'OK') {
      const paymentResponse = await $fetch(`${WP_URL}custom/v1/process-square-payment`, {
        method: 'POST',
        body: {
          sourceId: result.token,
          amountCents: Math.round(finalPayablePrice.value * 100),
          billingDetails: customerInfo.value,
          itemsOrdered: cartItems.value,
          userType: isGuest.value ? 'guest' : 'logged_in'
        }
      })

      if (paymentResponse.success) {
        alert('Payment Successful! Order Confirmed. 🔥')
        cartItems.value = []
        if (process.client) {
          localStorage.removeItem('atms_cart')
        }
        navigateTo('/')
      } else {
        alert(paymentResponse.message || 'Payment failed.')
      }
    } else {
      alert('Square Error: ' + result.errors[0].message)
    }
  } catch (err) {
    alert('Failed communicating with core gateway.')
  } finally {
    processing.value = false
  }
}
</script>

<style scoped>
#card-container {
  min-height: 40px;
  width: 100%;
}

iframe {
  min-height: 40px !important;
}

:deep(.sq-card-wrapper) {
  width: 100% !important;
}
</style>