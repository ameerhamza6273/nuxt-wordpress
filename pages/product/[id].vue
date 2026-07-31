<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-12 bg-[#f8f9fa] min-h-screen">
        <div class="container max-w-[1300px] mx-auto px-4">

          <div v-if="loading" class="text-center py-24">
            <div class="w-10 h-10 border-4 border-[#e31e24] border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="font-black text-gray-900 uppercase text-xs tracking-widest">Loading Product...</p>
          </div>

          <div v-else-if="!product" class="text-center py-24 bg-white rounded-3xl border border-gray-100">
            <p class="text-gray-400 font-bold text-sm uppercase tracking-wider mb-4">Product not found</p>
            <NuxtLink to="/"
              class="inline-block bg-[#e31e24] text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-black transition-colors">
              Back To Home
            </NuxtLink>
          </div>

          <template v-else>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

              <div class="space-y-4">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 h-[400px] flex items-center justify-center shadow-sm">
                  <img :src="activeImage" class="max-h-full max-w-full object-contain mix-blend-multiply" />
                </div>
                <div v-if="product.images.length > 1" class="flex gap-3 overflow-x-auto pb-2">
                  <button v-for="(img, idx) in product.images" :key="idx" type="button" @click="activeImage = img"
                    :class="['w-20 h-20 shrink-0 bg-white rounded-xl border-2 p-2 flex items-center justify-center transition-colors', activeImage === img ? 'border-[#e31e24]' : 'border-gray-100']">
                    <img :src="img" class="max-h-full max-w-full object-contain mix-blend-multiply" />
                  </button>
                </div>
              </div>

              <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm space-y-6">
                <div>
                  <span class="text-[10px] bg-gray-100 font-black px-2.5 py-1 rounded text-gray-500 uppercase tracking-wider">
                    SKU: {{ product.sku || 'N/A' }}
                  </span>
                  <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight mt-3 leading-tight">
                    {{ product.title }}
                  </h1>
                  <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-2">
                    Brand: <span class="text-gray-800">{{ product.brand || 'Premium OE' }}</span>
                  </p>
                </div>

                <div class="text-3xl font-black text-gray-900 tracking-tighter border-y border-gray-100 py-4">
                  {{ product.price }}
                  <span :class="['block text-[11px] font-bold uppercase mt-1', product.stock_status === 'out_of_stock' ? 'text-gray-400' : 'text-emerald-500']">
                    {{ product.stock_status === 'out_of_stock' ? 'Out Of Stock' : 'Available: In Stock' }}
                  </span>
                </div>

                <div v-if="product.placement_on_vehicle || product.manufacturer_part_number || product.interchange_part_number"
                  class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                  <div v-if="product.placement_on_vehicle">
                    <span class="text-gray-400 font-bold uppercase block mb-1">Placement</span>
                    <span class="font-black text-gray-800">{{ product.placement_on_vehicle }}</span>
                  </div>
                  <div v-if="product.manufacturer_part_number">
                    <span class="text-gray-400 font-bold uppercase block mb-1">Manufacturer Part #</span>
                    <div class="flex flex-wrap gap-1.5">
                      <span v-for="(num, idx) in splitPartNumbers(product.manufacturer_part_number)" :key="idx"
                        class="font-black text-gray-800 bg-gray-100 px-2 py-0.5 rounded text-[11px]">{{ num }}</span>
                    </div>
                  </div>
                  <div v-if="product.interchange_part_number">
                    <span class="text-gray-400 font-bold uppercase block mb-1">Interchange Part #</span>
                    <div class="flex flex-wrap gap-1.5">
                      <span v-for="(num, idx) in splitPartNumbers(product.interchange_part_number)" :key="idx"
                        class="font-black text-gray-800 bg-gray-100 px-2 py-0.5 rounded text-[11px]">{{ num }}</span>
                    </div>
                  </div>
                </div>

                <div v-if="product.vin_required_message" class="bg-red-50 border-2 border-[#e31e24]/20 rounded-2xl p-5 space-y-3">
                  <p class="text-xs font-black text-[#e31e24] uppercase tracking-wide flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span>{{ product.vin_required_message }}</span>
                  </p>
                  <input type="text" v-model="vinNumber" placeholder="Enter your 17-digit VIN"
                    class="w-full px-4 py-3 bg-white border border-red-200 rounded-xl text-xs font-bold outline-none focus:border-[#e31e24] transition-colors" />
                </div>

                <div class="flex items-center gap-4">
                  <div class="flex items-center bg-gray-50 border border-gray-100 rounded-xl overflow-hidden justify-between h-12 px-3">
                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider pr-2">Qty</span>
                    <input type="number" v-model.number="quantity" min="1"
                      class="bg-transparent text-sm font-black text-gray-800 outline-none text-right w-14" />
                  </div>
                  <button @click="handleAddToCart"
                    class="flex-1 bg-[#e31e24] hover:bg-black text-white font-black py-3.5 px-6 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-plus"></i> Add To Cart
                  </button>
                </div>

                <div v-if="product.fitment && product.fitment.length > 0" class="border-t border-gray-100 pt-6">
                  <button type="button" @click="showFitment = !showFitment"
                    class="w-full flex items-center justify-between text-left">
                    <h4 class="font-black text-gray-900 uppercase text-xs tracking-widest">
                      <i class="fa-solid fa-car text-[#e31e24] mr-2"></i>View Fitment Details
                    </h4>
                    <i :class="['fa-solid fa-chevron-down text-[10px] transition-transform duration-300', showFitment ? 'rotate-180 text-[#e31e24]' : 'text-gray-400']"></i>
                  </button>
                  <div v-show="showFitment" class="max-h-40 overflow-y-auto space-y-2 pr-2 mt-4">
                    <div v-for="(fit, idx) in product.fitment" :key="idx"
                      class="text-xs font-bold text-gray-600 flex flex-wrap gap-x-2 bg-gray-50 rounded-lg px-3 py-2">
                      <span>{{ fit.year }}</span>
                      <span class="text-gray-300">|</span>
                      <span>{{ fit.make }}</span>
                      <span>{{ fit.model }}</span>
                      <span v-if="fit.submodel" class="text-gray-400">({{ fit.submodel }})</span>
                      <span v-if="fit.engine" class="text-[#e31e24]">{{ fit.engine }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="fitmentNotesList.length > 0" class="bg-amber-50 border border-amber-200 rounded-3xl p-6 md:p-8 mt-8">
              <h4 class="font-black text-amber-800 uppercase text-xs tracking-widest mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Important Fitment Notes
              </h4>
              <ul class="space-y-2">
                <li v-for="(note, idx) in fitmentNotesList" :key="idx" class="text-sm text-amber-900 font-medium flex gap-2">
                  <i class="fa-solid fa-circle-exclamation text-amber-500 text-[10px] mt-1.5"></i>
                  <span>{{ note }}</span>
                </li>
              </ul>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mt-8">
              <h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-4 border-b border-gray-100 pb-4">
                Product Description
              </h4>
              <div class="prose max-w-none text-sm text-gray-600 leading-relaxed" v-html="product.description"></div>
            </div>
          </template>

        </div>
      </div>
      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { addToCart } from '~/composables/useCart.js'
import { showToast } from '~/composables/useToast.js'

const route = useRoute()
const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const product = ref(null)
const loading = ref(true)
const showFitment = ref(false)
const quantity = ref(1)
const activeImage = ref('')
const vinNumber = ref('')

// Space/comma-separated part number strings (e.g. eBay's "Manufacturer Part Number")
// rendered as individual badges instead of one long string.
const splitPartNumbers = (str) => {
  if (!str) return []
  return String(str).split(/[\s,]+/).map(s => s.trim()).filter(Boolean)
}

// fitment_notes holds multiple eBay "Fitment Note" entries joined by whoever populates the
// row (newline or " | " have both been used) - split on either so formatting doesn't matter.
const fitmentNotesList = computed(() => {
  if (!product.value?.fitment_notes) return []
  return String(product.value.fitment_notes)
    .split(/\n|\|/)
    .map(s => s.trim())
    .filter(Boolean)
})

const fetchProduct = async () => {
  loading.value = true
  try {
    const response = await $fetch(`${WP_URL}/wp-json/custom/v1/product-detail`, {
      method: 'GET',
      params: { id: route.params.id }
    })

    if (response && response.success) {
      product.value = response
      activeImage.value = response.images && response.images.length > 0 ? response.images[0] : 'https://via.placeholder.com/600'
    } else {
      product.value = null
    }
  } catch (err) {
    console.error('Failed to load product detail:', err)
    product.value = null
  } finally {
    loading.value = false
  }
}

const handleAddToCart = () => {
  if (!product.value) return

  if (product.value.vin_required_message && !vinNumber.value.trim()) {
    showToast('Please enter your VIN before adding this part to cart.', 'error')
    return
  }

  addToCart({
    id: product.value.id,
    title: product.value.title,
    price: product.value.price,
    sku: product.value.sku,
    image: activeImage.value,
    brand: product.value.brand,
    vin: vinNumber.value.trim() || undefined
  }, quantity.value || 1)
  showToast('Added to cart!', 'success')
}

onMounted(() => {
  fetchProduct()
})
</script>
