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
const loading = ref(false)
const error = ref('')

async function load() {
  loading.value = true
  error.value = ''
  try {
    const url = new URL(`${WP_URL}/wp-json/custom/v1/admin-products`)
    url.searchParams.set('page', page.value)
    url.searchParams.set('per_page', 20)
    if (search.value.trim()) url.searchParams.set('search', search.value.trim())

    const res = await fetch(url.toString(), {
      headers: { 'X-Import-Secret': config.public.importSecret },
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Failed to load products.'
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

async function remove(item) {
  if (!confirm(`Delete "${item.title || item.sku}"? This can't be undone.`)) return
  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/admin-product-delete`, {
      method: 'POST',
      headers: {
        'X-Import-Secret': config.public.importSecret,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ item_id: item.item_id }),
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Delete failed.'
      return
    }
    load()
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  }
}

let searchTimeout = null
watch(search, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => { page.value = 1; load() }, 400)
})
watch(page, load)

onMounted(load)
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Products ({{ total }})</h1>
      <div class="flex gap-3">
        <input v-model="search" placeholder="Search by title or SKU"
          class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] w-64" />
        <NuxtLink to="/admin/products/new"
          class="bg-black hover:bg-[#e31e24] text-white font-black px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 whitespace-nowrap">
          + Add Product
        </NuxtLink>
      </div>
    </div>

    <p v-if="error" class="text-[#e31e24] text-xs font-bold uppercase">{{ error }}</p>

    <div class="bg-white rounded-[1rem] border-2 border-gray-200 shadow-xl overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50">
          <tr class="border-b-2 border-gray-200">
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Image</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">SKU</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Title</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Brand</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Price</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500 border-r border-gray-200">Stock</th>
            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-wider text-gray-500"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="7" class="px-6 py-8 text-center text-xs font-bold text-gray-400">Loading...</td></tr>
          <tr v-else-if="items.length === 0"><td colspan="7" class="px-6 py-8 text-center text-xs font-bold text-gray-400">No products found.</td></tr>
          <tr v-for="item in items" :key="item.item_id" class="border-b border-gray-200 last:border-0 hover:bg-gray-50/50">
            <td class="px-6 py-3 border-r border-gray-100">
              <img v-if="item.thumbnail" :src="item.thumbnail" class="w-12 h-12 object-cover rounded-lg border border-gray-200" />
              <div v-else class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200"></div>
            </td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">{{ item.sku }}</td>
            <td class="px-6 py-3 text-xs font-bold text-gray-900 border-r border-gray-100">{{ item.title }}</td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">{{ item.brand }}</td>
            <td class="px-6 py-3 text-xs font-bold text-gray-500 border-r border-gray-100">{{ item.price }}</td>
            <td class="px-6 py-3 border-r border-gray-100">
              <span :class="['text-[10px] font-black uppercase px-2 py-1 rounded', item.stock_status === 'out_of_stock' ? 'bg-gray-100 text-gray-400' : 'bg-emerald-50 text-emerald-600']">
                {{ item.stock_status === 'out_of_stock' ? 'Out of Stock' : 'In Stock' }}
              </span>
            </td>
            <td class="px-6 py-3 text-right space-x-3 whitespace-nowrap">
              <NuxtLink :to="`/admin/products/${item.item_id}`" class="text-xs font-bold text-gray-500 hover:text-[#e31e24]">
                <i class="fa-solid fa-pen"></i> Edit
              </NuxtLink>
              <button @click="remove(item)" class="text-xs font-bold text-gray-500 hover:text-[#e31e24]">
                <i class="fa-solid fa-trash"></i> Delete
              </button>
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
