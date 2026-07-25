<script setup>
import { ref, onMounted, watch } from 'vue'
import { WP_URL } from '~/composables/useVehicleData'

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const config = useRuntimeConfig()

const items = ref([])
const total = ref(0)
const page = ref(1)
const totalPages = ref(1)
const search = ref('')
const status = ref('')
const loading = ref(false)
const error = ref('')

const statusOptions = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded']

async function load() {
  loading.value = true
  error.value = ''
  try {
    const url = new URL(`${WP_URL}/wp-json/custom/v1/admin-orders`)
    url.searchParams.set('page', page.value)
    url.searchParams.set('per_page', 20)
    if (search.value.trim()) url.searchParams.set('search', search.value.trim())
    if (status.value) url.searchParams.set('status', status.value)

    const res = await fetch(url.toString(), {
      headers: { 'X-Import-Secret': config.public.importSecret },
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Failed to load orders.'
      return
    }
    items.value = data.items
    total.value = data.total
    totalPages.value = data.total_pages
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    loading.value = false
  }
}

let searchTimeout = null
watch(search, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { page.value = 1; load() }, 400)
})
watch(status, () => { page.value = 1; load() })
watch(page, load)

onMounted(load)
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Orders ({{ total }})</h1>
      <div class="flex gap-3">
        <input v-model="search" placeholder="Search by order #, name or email"
          class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] w-72" />
        <select v-model="status"
          class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]">
          <option value="">All Statuses</option>
          <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
        </select>
      </div>
    </div>

    <p v-if="error" class="text-[#e31e24] text-xs font-bold uppercase">{{ error }}</p>

    <div class="bg-white rounded-[1rem] border-2 border-gray-200 shadow-xl overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50">
          <tr class="border-b-2 border-gray-200">
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Order #</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Customer</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Total</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Status</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Date</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="6" class="px-6 py-8 text-center text-xs font-bold text-gray-400">Loading...</td></tr>
          <tr v-else-if="items.length === 0"><td colspan="6" class="px-6 py-8 text-center text-xs font-bold text-gray-400">No orders found.</td></tr>
          <tr v-for="item in items" :key="item.id" class="border-b border-gray-200 last:border-0 hover:bg-gray-50/50">
            <td class="px-6 py-3 text-xs font-bold text-gray-900 border-r border-gray-100">{{ item.order_number }}</td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">
              <div>{{ item.customer_name }}</div>
              <div class="text-gray-400">{{ item.customer_email }}</div>
            </td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">${{ item.total_amount }}</td>
            <td class="px-6 py-3 text-xs font-bold border-r border-gray-100">
              <span class="px-2 py-1 rounded-md bg-gray-100 uppercase">{{ item.status }}</span>
            </td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">{{ item.created_at }}</td>
            <td class="px-6 py-3 text-right whitespace-nowrap">
              <NuxtLink :to="`/admin/orders/${item.id}`" class="text-xs font-bold text-gray-500 hover:text-[#e31e24]">
                <i class="fa-solid fa-eye"></i> View
              </NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="totalPages > 1" class="flex justify-center gap-2">
      <button v-for="p in totalPages" :key="p" @click="page = p"
        :class="['w-8 h-8 rounded-lg text-xs font-black', p === page ? 'bg-black text-white' : 'bg-white text-gray-500 border border-gray-200']">
        {{ p }}
      </button>
    </div>
  </div>
</template>
