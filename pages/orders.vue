<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-12 bg-[#f8f9fa] min-h-screen">
        <div class="container max-w-[900px] mx-auto px-4">
          <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-gray-900">
            My <span class="text-[#e31e24]">Orders</span>
          </h1>

          <p v-if="loading" class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm text-gray-400 font-bold text-sm uppercase tracking-wider">
            Loading your orders...
          </p>

          <p v-else-if="error" class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm text-[#e31e24] font-bold text-sm uppercase tracking-wider">
            {{ error }}
          </p>

          <div v-else-if="orders.length === 0" class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 font-bold text-sm uppercase tracking-wider">You haven't placed any orders yet.</p>
            <button @click="navigateTo('/')"
              class="mt-4 bg-[#e31e24] text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-black transition-colors">
              Browse Parts
            </button>
          </div>

          <div v-else class="space-y-4">
            <div v-for="order in orders" :key="order.id"
              class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-50 pb-4 mb-4">
                <div>
                  <p class="text-sm font-black text-gray-900">{{ order.order_number }}</p>
                  <p class="text-[11px] text-gray-400 font-bold uppercase">{{ order.created_at }}</p>
                </div>
                <div class="flex items-center gap-3">
                  <span class="px-3 py-1 rounded-full bg-gray-100 text-[10px] font-black uppercase tracking-wider text-gray-700">
                    {{ order.status }}
                  </span>
                  <span class="text-lg font-black text-gray-900">${{ order.total_amount }}</span>
                </div>
              </div>

              <div class="space-y-2">
                <div v-for="(item, i) in order.items" :key="i" class="flex justify-between items-center text-xs">
                  <div>
                    <span class="font-bold text-gray-800">{{ item.title }}</span>
                    <span class="text-gray-400"> · SKU: {{ item.sku }}</span>
                  </div>
                  <span class="font-bold text-gray-500">{{ item.quantity }} × ${{ item.price }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { showToast } from '~/composables/useToast.js'

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const orders = ref([])
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  if (!process.client) return

  const token = localStorage.getItem('atms_user_token')
  const email = localStorage.getItem('atms_user_email')

  if (!token || !email) {
    showToast('Please sign in to view your orders.', 'error')
    navigateTo('/checkout-auth')
    return
  }

  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/my-orders?email=${encodeURIComponent(email)}`)
    const data = await res.json()
    if (!data.success) {
      error.value = data.message || 'Could not load your orders.'
      return
    }
    orders.value = data.orders
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    loading.value = false
  }
})
</script>
