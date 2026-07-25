<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { WP_URL } from '~/composables/useVehicleData'

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const route = useRoute()

const product = ref(null)
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/product-detail?id=${route.params.id}`)
    const data = await res.json()
    if (!data.success) {
      error.value = data.message || 'Product not found.'
      return
    }
    product.value = data
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    loading.value = false
  }
})

function onSaved() {
  navigateTo('/admin/products')
}
</script>

<template>
  <div class="max-w-3xl mx-auto space-y-6">
    <div class="text-center space-y-1">
      <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center mx-auto">
        <i class="fa-solid fa-pen"></i>
      </div>
      <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Edit Product</h1>
      <p class="text-xs text-gray-400 font-bold">Update the details below and save your changes.</p>
    </div>
    <p v-if="loading" class="text-xs font-bold text-gray-400 text-center">Loading...</p>
    <p v-else-if="error" class="text-[#e31e24] text-xs font-bold uppercase text-center">{{ error }}</p>
    <AdminProductForm v-else :initial="product" @saved="onSaved" />
  </div>
</template>
