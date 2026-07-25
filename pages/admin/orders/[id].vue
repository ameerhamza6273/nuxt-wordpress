<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { WP_URL } from '~/composables/useVehicleData'

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const route = useRoute()
const config = useRuntimeConfig()

const order = ref(null)
const loading = ref(true)
const error = ref('')
const saving = ref(false)

const statusOptions = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded']

async function load() {
  loading.value = true
  error.value = ''
  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/admin-order-detail?id=${route.params.id}`, {
      headers: { 'X-Import-Secret': config.public.importSecret },
    })
    const data = await res.json()
    if (!data.success) {
      error.value = data.message || 'Order not found.'
      return
    }
    order.value = data.order
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    loading.value = false
  }
}

async function updateStatus() {
  saving.value = true
  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/admin-order-update-status`, {
      method: 'POST',
      headers: {
        'X-Import-Secret': config.public.importSecret,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: order.value.id, status: order.value.status }),
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Update failed.'
    }
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    saving.value = false
  }
}

onMounted(load)
</script>

<template>
  <div class="max-w-3xl mx-auto space-y-6">
    <div class="text-center space-y-1">
      <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center mx-auto">
        <i class="fa-solid fa-receipt"></i>
      </div>
      <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Order Detail</h1>
    </div>

    <p v-if="loading" class="text-xs font-bold text-gray-400 text-center">Loading...</p>
    <p v-else-if="error" class="text-[#e31e24] text-xs font-bold uppercase text-center">{{ error }}</p>

    <template v-else-if="order">
      <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">{{ order.order_number }}</h2>
          <div class="flex items-center gap-2">
            <select v-model="order.status"
              class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]">
              <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
            </select>
            <button @click="updateStatus" :disabled="saving"
              class="bg-black hover:bg-[#e31e24] text-white font-black px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
              {{ saving ? 'Saving...' : 'Update' }}
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Customer</label>
            <p class="text-xs font-bold text-gray-900">{{ order.customer_name }}</p>
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Email</label>
            <p class="text-xs font-bold text-gray-900">{{ order.customer_email }}</p>
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Phone</label>
            <p class="text-xs font-bold text-gray-900">{{ order.customer_phone || '-' }}</p>
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Postcode</label>
            <p class="text-xs font-bold text-gray-900">{{ order.customer_postcode || '-' }}</p>
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Total</label>
            <p class="text-xs font-bold text-gray-900">${{ order.total_amount }}</p>
          </div>
          <div>
            <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Placed</label>
            <p class="text-xs font-bold text-gray-900">{{ order.created_at }}</p>
          </div>
        </div>
      </section>

      <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
        <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">Items</h2>
        <div v-for="item in order.items" :key="item.id"
          class="flex justify-between items-center border-b border-gray-50 pb-3 last:border-0">
          <div>
            <p class="text-xs font-bold text-gray-900">{{ item.title }}</p>
            <p class="text-[10px] text-gray-400 font-bold">SKU: {{ item.sku }}<span v-if="item.vin"> · VIN: {{ item.vin }}</span></p>
          </div>
          <div class="text-xs font-bold text-gray-500">{{ item.quantity }} × ${{ item.price }}</div>
        </div>
        <p v-if="!order.items || order.items.length === 0" class="text-xs text-gray-400 font-bold">No items on this order.</p>
      </section>
    </template>
  </div>
</template>
