<script setup>
import { ref } from 'vue'
import { WP_URL } from '~/composables/useVehicleData'

definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const config = useRuntimeConfig()

const productsFile = ref(null)
const fitmentFile = ref(null)
const productsResult = ref(null)
const fitmentResult = ref(null)
const loading = ref(false)
const error = ref('')

async function upload(kind) {
  const file = kind === 'products' ? productsFile.value : fitmentFile.value
  if (!file) {
    error.value = 'Please select a file first.'
    return
  }

  error.value = ''
  loading.value = true

  const endpoint = kind === 'products' ? 'import-products' : 'import-fitment'
  const formData = new FormData()
  formData.append('file', file)

  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/${endpoint}`, {
      method: 'POST',
      headers: { 'X-Import-Secret': config.public.importSecret },
      body: formData,
    })
    const data = await res.json()
    if (kind === 'products') productsResult.value = data
    else fitmentResult.value = data
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Import failed.'
    }
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    loading.value = false
  }
}

function onFileChange(e, kind) {
  const file = e.target.files?.[0] || null
  if (kind === 'products') productsFile.value = file
  else fitmentFile.value = file
}

const exporting = ref({ products: false, fitment: false })

async function exportCsv(kind) {
  exporting.value[kind] = true
  error.value = ''
  try {
    const endpoint = kind === 'products' ? 'export-products' : 'export-fitment'
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/${endpoint}`, {
      headers: { 'X-Import-Secret': config.public.importSecret },
    })
    if (!res.ok) {
      error.value = 'Export failed.'
      return
    }
    const blob = await res.blob()
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = kind === 'products' ? 'products-export.csv' : 'fitment-export.csv'
    a.click()
    URL.revokeObjectURL(url)
  } catch (e) {
    error.value = 'Export failed: ' + e.message
  } finally {
    exporting.value[kind] = false
  }
}
</script>

<template>
  <div class="w-full space-y-8">
    <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Bulk Product Import</h1>

    <p v-if="error" class="text-[#e31e24] text-xs font-bold uppercase">{{ error }}</p>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">1. Products CSV</h2>
      <p class="text-xs text-gray-400 font-bold">
        Columns: sku, title, price, description, brand, placement_on_vehicle,
        manufacturer_part_number, interchange_part_number, other_part_number,
        fitment_notes, vin_required_message, images (URLs separated by "|"). Extra
        columns are allowed - they'll be added automatically.
      </p>
      <input type="file" accept=".csv" @change="onFileChange($event, 'products')" class="text-xs font-bold" />
      <div class="flex gap-3">
        <button @click="upload('products')" :disabled="loading"
          class="px-10 bg-black hover:bg-[#e31e24] text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
          Upload Products
        </button>
        <button @click="exportCsv('products')" :disabled="exporting.products" type="button"
          class="px-10 bg-white border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
          {{ exporting.products ? 'Exporting...' : 'Export Current Products' }}
        </button>
      </div>
      <pre v-if="productsResult" class="bg-gray-50 border border-gray-100 text-[11px] p-4 rounded-xl overflow-auto">{{ productsResult }}</pre>
    </section>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">2. Fitment CSV</h2>
      <p class="text-xs text-gray-400 font-bold">
        Columns: sku, year, make, model, submodel, engine. Upload this
        <span class="text-gray-700">after</span> the products file - each SKU must already exist.
      </p>
      <input type="file" accept=".csv" @change="onFileChange($event, 'fitment')" class="text-xs font-bold" />
      <div class="flex gap-3">
        <button @click="upload('fitment')" :disabled="loading"
          class="px-10 bg-black hover:bg-[#e31e24] text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
          Upload Fitment
        </button>
        <button @click="exportCsv('fitment')" :disabled="exporting.fitment" type="button"
          class="px-10 bg-white border-2 border-gray-900 hover:bg-gray-900 hover:text-white text-gray-900 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
          {{ exporting.fitment ? 'Exporting...' : 'Export Current Fitment' }}
        </button>
      </div>
      <pre v-if="fitmentResult" class="bg-gray-50 border border-gray-100 text-[11px] p-4 rounded-xl overflow-auto">{{ fitmentResult }}</pre>
    </section>
  </div>
</template>
