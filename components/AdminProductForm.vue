<template>
  <form @submit.prevent="submit" class="space-y-6">
    <p v-if="error" class="text-[#e31e24] text-xs font-bold uppercase">{{ error }}</p>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">Product Details</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">SKU *</label>
          <input v-model="form.sku" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Price</label>
          <input v-model="form.price" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div class="md:col-span-2">
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Title</label>
          <input v-model="form.title" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div class="md:col-span-2">
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Description</label>
          <textarea v-model="form.description" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]"></textarea>
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Brand</label>
          <input v-model="form.brand" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Placement On Vehicle</label>
          <input v-model="form.placement_on_vehicle" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Manufacturer Part Number</label>
          <input v-model="form.manufacturer_part_number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Interchange Part Number</label>
          <input v-model="form.interchange_part_number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Other Part Number</label>
          <input v-model="form.other_part_number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div>
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">VIN Required Message</label>
          <input v-model="form.vin_required_message" placeholder="Leave blank if not needed" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        </div>
        <div class="md:col-span-2">
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Fitment Notes</label>
          <textarea v-model="form.fitment_notes" rows="3" placeholder="One note per line" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]"></textarea>
        </div>
      </div>
    </section>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">Images</h2>
        <button type="button" @click="images.push('')" class="text-[11px] font-bold text-[#e31e24] uppercase">+ Add Image</button>
      </div>
      <div v-for="(_, i) in images" :key="i" class="flex gap-2">
        <input v-model="images[i]" placeholder="https://..." class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]" />
        <button type="button" @click="images.splice(i, 1)" class="px-3 text-gray-400 hover:text-[#e31e24]">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
      <p v-if="images.length === 0" class="text-xs text-gray-400 font-bold">No images yet.</p>
    </section>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">Fitment (Vehicle Compatibility)</h2>
        <button type="button" @click="fitment.push({ year: '', make: '', model: '', submodel: '', engine: '' })" class="text-[11px] font-bold text-[#e31e24] uppercase">+ Add Row</button>
      </div>
      <div v-for="(fit, i) in fitment" :key="i" class="grid grid-cols-2 md:grid-cols-5 gap-2 items-center border-b border-gray-50 pb-3 last:border-0">
        <input v-model="fit.year" placeholder="Year" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.make" placeholder="Make" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.model" placeholder="Model" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.submodel" placeholder="Submodel" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <div class="flex gap-2">
          <input v-model="fit.engine" placeholder="Engine" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
          <button type="button" @click="fitment.splice(i, 1)" class="px-2 text-gray-400 hover:text-[#e31e24]">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>
      <p v-if="fitment.length === 0" class="text-xs text-gray-400 font-bold">No fitment rows yet - this product will show as fitting all vehicles until you add some.</p>
    </section>

    <button type="submit" :disabled="saving"
      class="w-full bg-black hover:bg-[#e31e24] text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 disabled:opacity-50">
      {{ saving ? 'Saving...' : 'Save Product' }}
    </button>
  </form>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { WP_URL } from '~/composables/useVehicleData'

const props = defineProps({
  initial: { type: Object, default: () => ({}) },
})
const emit = defineEmits(['saved'])

const config = useRuntimeConfig()
const saving = ref(false)
const error = ref('')

const form = reactive({
  item_id: props.initial.item_id || props.initial.id || 0,
  sku: props.initial.sku || '',
  title: props.initial.title || '',
  price: (props.initial.price || '').toString().replace(/[^0-9.]/g, ''),
  description: props.initial.description || '',
  brand: props.initial.brand || '',
  placement_on_vehicle: props.initial.placement_on_vehicle || '',
  manufacturer_part_number: props.initial.manufacturer_part_number || '',
  interchange_part_number: props.initial.interchange_part_number || '',
  other_part_number: props.initial.other_part_number || '',
  fitment_notes: props.initial.fitment_notes || '',
  vin_required_message: props.initial.vin_required_message || '',
})

const images = ref(Array.isArray(props.initial.images) ? [...props.initial.images] : [])
const fitment = ref(
  Array.isArray(props.initial.fitment)
    ? props.initial.fitment.map(f => ({ year: f.year || '', make: f.make || '', model: f.model || '', submodel: f.submodel || '', engine: f.engine || '' }))
    : []
)

async function submit() {
  error.value = ''
  saving.value = true
  try {
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/admin-product-save`, {
      method: 'POST',
      headers: {
        'X-Import-Secret': config.public.importSecret,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        ...form,
        images: images.value.filter(u => u.trim() !== ''),
        fitment: fitment.value.filter(f => f.year || f.make || f.model),
      }),
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      error.value = data.message || 'Save failed.'
      return
    }
    emit('saved', data.item_id)
  } catch (e) {
    error.value = 'Request failed: ' + e.message
  } finally {
    saving.value = false
  }
}
</script>
