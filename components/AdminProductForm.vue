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
          <label class="text-[10px] font-black uppercase tracking-wider text-gray-500 block mb-1">Availability</label>
          <select v-model="form.stock_status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24]">
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
          </select>
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
        <button type="button" @click="images.push('')" class="text-[11px] font-bold text-[#e31e24] uppercase">+ Add Image Slot</button>
      </div>
      <div v-for="(_, i) in images" :key="i" class="flex items-center gap-3 border border-gray-200 rounded-xl p-3">
        <div class="w-16 h-16 shrink-0 bg-gray-50 border border-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
          <img v-if="images[i]" :src="images[i]" class="w-full h-full object-cover" />
          <i v-else class="fa-solid fa-image text-gray-300 text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
          <input type="file" :id="`admin-image-upload-${i}`" accept="image/*" class="hidden" @change="e => onFileChosen(e, i)" />
          <button type="button" @click="triggerUpload(i)" :disabled="uploading[i]"
            class="text-[11px] font-bold text-white bg-black hover:bg-[#e31e24] disabled:opacity-50 px-4 py-2 rounded-lg uppercase transition-colors">
            {{ uploading[i] ? (compressing[i] ? 'Compressing Image...' : 'Uploading...') : (images[i] ? 'Change Image' : 'Upload Image') }}
          </button>
          <p v-if="compressing[i]" class="text-[10px] text-gray-400 font-bold mt-1">
            Large file detected - compressing on the server before upload, this can take a few extra seconds.
          </p>
          <p v-if="uploadErrors[i]" class="text-[10px] text-[#e31e24] font-bold mt-1">{{ uploadErrors[i] }}</p>
        </div>
        <button type="button" @click="images.splice(i, 1)" class="px-2 text-gray-400 hover:text-[#e31e24]">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
      <p v-if="images.length === 0" class="text-xs text-gray-400 font-bold">No images yet.</p>
    </section>

    <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl space-y-4">
      <h2 class="text-sm font-black uppercase tracking-tight text-gray-900">Fitment (Vehicle Compatibility)</h2>

      <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 space-y-3">
        <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Add a vehicle this product fits</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
          <select v-model="picker.year" class="min-w-0 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]">
            <option value="" disabled>Year</option>
            <option v-for="y in picker.years" :key="y.slug" :value="y.slug">{{ y.name }}</option>
          </select>
          <select v-model="picker.make" :disabled="!picker.year || picker.loadingMakes" class="min-w-0 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24] disabled:opacity-50">
            <option value="" disabled>Make</option>
            <option v-for="m in picker.makes" :key="m.slug" :value="m.slug">{{ m.name }}</option>
          </select>
          <select v-model="picker.model" :disabled="!picker.make || picker.loadingModels" class="min-w-0 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24] disabled:opacity-50">
            <option value="" disabled>Model</option>
            <option v-for="mo in picker.models" :key="mo.slug" :value="mo.slug">{{ mo.name }}</option>
          </select>
          <select v-model="picker.submodel" :disabled="!picker.model || picker.loadingSubmodels" class="min-w-0 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24] disabled:opacity-50">
            <option value="" disabled>Submodel</option>
            <option v-for="s in picker.submodels" :key="s.slug" :value="s.slug">{{ s.name }}</option>
          </select>
          <select v-model="picker.engine" :disabled="!picker.submodel || picker.loadingEngines" class="min-w-0 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24] disabled:opacity-50">
            <option value="" disabled>Engine</option>
            <option v-for="e in picker.engines" :key="e.slug" :value="e.slug">{{ e.name }}</option>
          </select>
        </div>
        <button type="button" @click="addFitmentFromPicker" :disabled="!picker.engine"
          class="text-[11px] font-bold text-white bg-black hover:bg-[#e31e24] disabled:opacity-40 disabled:hover:bg-black px-4 py-2 rounded-lg uppercase transition-colors">
          + Add This Fitment
        </button>
        <p class="text-[10px] text-gray-400 font-bold">Vehicle not listed? Use "+ Add Row (Manual)" below to type it in directly.</p>
      </div>

      <div class="flex items-center justify-between pt-2">
        <p class="text-[10px] font-black uppercase tracking-wider text-gray-500">Fitment rows on this product</p>
        <button type="button" @click="fitment.push({ year: '', make: '', model: '', submodel: '', engine: '' })" class="text-[11px] font-bold text-[#e31e24] uppercase">+ Add Row (Manual)</button>
      </div>
      <div v-for="(fit, i) in fitment" :key="i" class="grid grid-cols-2 md:grid-cols-[repeat(5,minmax(0,1fr))_2rem] gap-2 items-center border-b border-gray-50 pb-3 last:border-0">
        <input v-model="fit.year" placeholder="Year" class="min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.make" placeholder="Make" class="min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.model" placeholder="Model" class="min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.submodel" placeholder="Submodel" class="min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <input v-model="fit.engine" placeholder="Engine" class="min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold outline-none focus:border-[#e31e24]" />
        <button type="button" @click="fitment.splice(i, 1)" class="justify-self-center text-gray-400 hover:text-[#e31e24]">
          <i class="fa-solid fa-trash"></i>
        </button>
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
import { ref, reactive, watch, onMounted } from 'vue'
import { WP_URL, fetchVehicleAttribute } from '~/composables/useVehicleData'

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
  stock_status: props.initial.stock_status === 'out_of_stock' ? 'out_of_stock' : 'in_stock',
})

const images = ref(Array.isArray(props.initial.images) && props.initial.images.length > 0 ? [...props.initial.images] : ['', '', '', ''])

// Image upload - each row gets its own hidden <input type=file>, looked up by DOM id
// rather than a Vue template ref (a Vue ref written into a reactive array from inside
// a v-for's inline function-ref broke on SSR hydration - "Cannot set properties of
// undefined" - so plain getElementById side-steps that entirely).
const uploading = ref({})
const uploadErrors = ref({})
const compressing = ref({})

// Mirrors ADMIN_IMAGE_MAX_BYTES in wp-snippets/admin-upload-image.php - files above
// this are re-encoded/downsized server-side, which is why the request takes longer.
const ADMIN_IMAGE_MAX_BYTES = 500 * 1024

function triggerUpload(i) {
  document.getElementById(`admin-image-upload-${i}`)?.click()
}

async function onFileChosen(e, i) {
  const file = e.target.files[0]
  e.target.value = '' // allow re-selecting the same file later
  if (!file) return

  uploading.value[i] = true
  compressing.value[i] = file.size > ADMIN_IMAGE_MAX_BYTES
  uploadErrors.value[i] = ''
  try {
    const body = new FormData()
    body.append('file', file)
    const res = await fetch(`${WP_URL}/wp-json/custom/v1/admin-upload-image`, {
      method: 'POST',
      headers: { 'X-Import-Secret': config.public.importSecret },
      body,
    })
    const data = await res.json()
    if (!res.ok || data.success === false) {
      uploadErrors.value[i] = data.message || 'Upload failed.'
      return
    }
    images.value[i] = data.url
  } catch (e) {
    uploadErrors.value[i] = 'Upload failed: ' + e.message
  } finally {
    uploading.value[i] = false
    compressing.value[i] = false
  }
}
const fitment = ref(
  Array.isArray(props.initial.fitment)
    ? props.initial.fitment.map(f => ({ year: f.year || '', make: f.make || '', model: f.model || '', submodel: f.submodel || '', engine: f.engine || '' }))
    : []
)

// Fitment "vehicle picker" - mirrors the same cascading Year/Make/Model/Submodel/Engine
// dropdowns as the storefront's HeroSection search form (same custom/v2/vehicle API),
// so a fitment row can only be built from combinations that actually exist in the fitment
// table - no free-text typos that would silently stop this product matching a search.
const picker = reactive({
  year: '', make: '', model: '', submodel: '', engine: '',
  years: [], makes: [], models: [], submodels: [], engines: [],
  loadingMakes: false, loadingModels: false, loadingSubmodels: false, loadingEngines: false,
})

function resolveName(list, slug) {
  const match = list.find(o => o.slug === slug)
  return match ? match.name : slug
}

watch(() => picker.year, (newYear) => {
  picker.make = ''; picker.model = ''; picker.submodel = ''; picker.engine = ''
  picker.makes = []; picker.models = []; picker.submodels = []; picker.engines = []
  if (!newYear) return
  picker.loadingMakes = true
  fetchVehicleAttribute('pa_make', newYear).then(data => {
    picker.makes = data
    picker.loadingMakes = false
  })
})

watch(() => picker.make, (newMake) => {
  picker.model = ''; picker.submodel = ''; picker.engine = ''
  picker.models = []; picker.submodels = []; picker.engines = []
  if (!newMake || !picker.year) return
  picker.loadingModels = true
  fetchVehicleAttribute('pa_model', `${picker.year}|${newMake}`).then(data => {
    picker.models = data
    picker.loadingModels = false
  })
})

watch(() => picker.model, (newModel) => {
  picker.submodel = ''; picker.engine = ''
  picker.submodels = []; picker.engines = []
  if (!newModel || !picker.make || !picker.year) return
  const modelName = resolveName(picker.models, newModel)
  picker.loadingSubmodels = true
  fetchVehicleAttribute('pa_submodel', `${picker.year}|${picker.make}|${modelName}`).then(data => {
    picker.submodels = data
    picker.loadingSubmodels = false
  })
})

watch(() => picker.submodel, (newSubmodel) => {
  picker.engine = ''
  picker.engines = []
  if (!newSubmodel) return
  const modelName = resolveName(picker.models, picker.model)
  const subName = resolveName(picker.submodels, newSubmodel)
  picker.loadingEngines = true
  fetchVehicleAttribute('pa_engine', `${picker.year}|${picker.make}|${modelName}|${subName}`).then(data => {
    picker.engines = data
    picker.loadingEngines = false
  })
})

function addFitmentFromPicker() {
  if (!picker.engine) return
  fitment.value.push({
    year: picker.year,
    make: resolveName(picker.makes, picker.make),
    model: resolveName(picker.models, picker.model),
    submodel: resolveName(picker.submodels, picker.submodel),
    engine: resolveName(picker.engines, picker.engine),
  })
  picker.year = ''
}

onMounted(async () => {
  picker.years = await fetchVehicleAttribute('pa_year')
})

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
