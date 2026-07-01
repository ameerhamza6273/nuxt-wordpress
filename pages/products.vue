<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-12 bg-[#f8f9fa] min-h-screen">
        <div class="container max-w-[1300px] mx-auto px-4">

          <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

            <aside class="space-y-6 lg:sticky lg:top-6">
              <div class="bg-gray-900 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl">
                <span class="text-[10px] font-black tracking-widest text-[#f2a900] uppercase block mb-1">
                  My Garage
                </span>
                <h4 class="text-xl font-black uppercase tracking-tight">
                  {{ route.query.year || 'N/A' }} {{ route.query.make || 'Select Vehicle' }}
                </h4>
                <p class="text-xs text-gray-300 mt-1 font-bold">
                  {{ route.query.model || 'No Model' }}
                  <span v-if="route.query.submodel" class="text-gray-400"> | {{ route.query.submodel }}</span>
                </p>
                <p v-if="route.query.engine" class="text-[11px] text-[#f2a900] mt-1 font-black uppercase tracking-wide">
                  Engine: {{ route.query.engine }}
                </p>
                <button @click="router.push('/')"
                  class="mt-4 text-xs font-black text-[#e31e24] uppercase tracking-wider flex items-center gap-2 hover:text-white transition-colors">
                  <i class="fa-solid fa-rotate"></i> Change Vehicle
                </button>
              </div>

              <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h5
                  class="font-black text-gray-900 uppercase text-xs tracking-widest mb-6 border-b pb-4 border-gray-100 flex items-center gap-2">
                  <i class="fa-solid fa-layer-group text-[#e31e24]"></i> Browse Categories
                </h5>

                <div v-if="loadingCategories" class="space-y-3 py-4">
                  <div class="h-4 bg-gray-100 rounded-md animate-pulse w-3/4"></div>
                  <div class="h-4 bg-gray-100 rounded-md animate-pulse w-5/6"></div>
                  <div class="h-4 bg-gray-100 rounded-md animate-pulse w-2/3"></div>
                </div>

                <div v-else class="space-y-4">
                  <div v-if="dynamicCategories.length === 0" class="text-xs text-gray-400 font-bold py-2 text-center">
                    No active categories found for this vehicle.
                  </div>

                  <div v-for="cat in dynamicCategories" :key="cat.id" class="space-y-2">
                    <button @click="toggleCategory(cat.id)"
                      class="w-full flex items-center justify-between text-left font-black text-sm uppercase text-gray-800 hover:text-[#e31e24] transition-colors">
                      {{ cat.name }}
                      <i
                        :class="['fa-solid text-xs transition-transform', activeCatGroup === cat.id ? 'fa-chevron-down rotate-180' : 'fa-chevron-right']"></i>
                    </button>

                    <div v-if="activeCatGroup === cat.id" class="pl-4 space-y-2 border-l border-gray-100 mt-2">
                      <button @click="filterBySubCategory(cat.slug)"
                        :class="['w-full text-left text-xs font-bold block py-1', selectedCategorySlug === cat.slug ? 'text-[#e31e24]' : 'text-gray-500 hover:text-gray-900']">
                        • All {{ cat.name }}
                      </button>
                      <button v-for="sub in cat.children" :key="sub.id" @click="filterBySubCategory(sub.slug)"
                        :class="['w-full text-left text-xs font-bold block py-1 transition-colors', selectedCategorySlug === sub.slug ? 'text-[#e31e24]' : 'text-gray-500 hover:text-gray-900']">
                        • {{ sub.name }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </aside>

            <main class="lg:col-span-3 space-y-6">
              <div
                class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                  <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Home / Parts Search</span>
                  <h2
                    class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter mt-1 leading-tight">
                    {{ route.query.year || '' }} {{ route.query.make || '' }} {{ route.query.model || 'All' }}
                    <span v-if="route.query.submodel"
                      class="text-gray-500 font-bold text-xl block md:inline md:text-2xl"> ({{ route.query.submodel
                      }})</span>
                    <span class="text-[#e31e24]"> Parts</span>
                  </h2>
                </div>

                <select v-model="sortBy" @change="changeSort"
                  class="p-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-700 outline-none cursor-pointer">
                  <option value="relevance">Sort By: Relevance</option>
                  <option value="price_low">Price: Low to High</option>
                  <option value="price_high">Price: High to Low</option>
                </select>
              </div>

              <div v-if="loading" class="text-center py-24 bg-white rounded-3xl border">
                <div
                  class="w-10 h-10 border-4 border-[#e31e24] border-t-transparent rounded-full animate-spin mx-auto mb-4">
                </div>
                <p class="font-black text-gray-900 uppercase text-xs tracking-widest">Analyzing Fitment Matrix...</p>
              </div>

              <div v-else class="space-y-4">
                <div v-if="products.length === 0"
                  class="text-center py-16 bg-white rounded-3xl border border-gray-100 text-gray-400 font-bold text-sm">
                  No compatible parts found for this selection.
                </div>

                <div v-for="product in products" :key="product.id"
                  class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 hover:border-[#e31e24]/30 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden group">

                  <div
                    class="absolute top-0 left-0 bg-emerald-500 text-white font-black text-[9px] uppercase tracking-widest px-4 py-1.5 rounded-br-2xl flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i> Guaranteed To Fit
                  </div>

                  <div
                    class="w-full md:w-44 h-32 flex-shrink-0 bg-gray-50 rounded-2xl p-4 flex items-center justify-center border border-gray-50">
                    <img :src="product.image || 'https://via.placeholder.com/150'"
                      class="max-h-full max-w-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform" />
                  </div>

                  <div class="flex-grow text-center md:text-left">
                    <div class="flex flex-wrap gap-2 items-center mb-2 justify-center md:justify-start">
                      <span
                        class="text-[10px] bg-gray-100 font-black px-2.5 py-1 rounded text-gray-500 uppercase tracking-wider">
                        SKU: {{ product.sku || 'N/A' }}
                      </span>
                      <span v-if="product.category_name"
                        class="text-[10px] bg-red-50 text-[#e31e24] font-black px-2.5 py-1 rounded uppercase tracking-wider border border-red-100/50">
                        <i class="fa-solid fa-tags text-[9px] mr-1"></i> {{ product.category_name }}
                      </span>
                    </div>

                    <a :href="product.permalink" class="block">
                      <h3
                        class="text-lg md:text-xl font-black text-gray-900 tracking-tight hover:text-[#e31e24] transition-colors leading-tight mb-2">
                        {{ product.title }}
                      </h3>
                    </a>
                    <p class="text-xs text-gray-400 font-medium line-clamp-2 max-w-xl"
                      v-html="product.short_description"></p>

                    <div class="mt-4 flex items-center gap-4 justify-center md:justify-start">
                      <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Brand:</span>
                        <span class="text-xs font-black text-gray-800 uppercase tracking-tight">
                          {{ product.brand || 'Premium OE' }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <div
                    class="w-full md:w-48 flex-shrink-0 flex flex-col items-center md:items-end justify-between border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 h-full min-h-[140px]">
                    <div class="text-center md:text-right w-full">
                      <span class="text-2xl font-black text-gray-900 tracking-tighter" v-html="product.price"></span>
                      <span class="block text-[10px] font-bold text-emerald-500 uppercase mt-0.5">In Stock / Ready To
                        Ship</span>
                    </div>

                    <div class="w-full mt-4 space-y-2">
                      <div
                        class="flex items-center bg-gray-50 border border-gray-100 rounded-xl overflow-hidden justify-between h-10 px-2 relative group/qty w-full">
                        <span
                          class="text-[10px] font-black uppercase text-gray-400 tracking-wider pl-1 select-none">Qty</span>

                        <div class="flex items-center justify-end w-full gap-1">
                          <input type="number" v-model.number="quantities[product.id]" min="1"
                            class="bg-transparent text-xs font-black text-gray-800 outline-none text-right w-12 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            @blur="validateQuantity(product.id)" />

                          <div
                            class="relative flex items-center justify-center cursor-pointer text-gray-400 hover:text-gray-800 transition-colors w-4 h-full">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            <select v-model="quantities[product.id]"
                              class="absolute inset-0 opacity-0 cursor-pointer w-full h-full text-xs">
                              <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                              <option value="10">10+</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <button @click="handleAddToCart(product)"
                        class="w-full bg-[#e31e24] hover:bg-black text-white font-black py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-cart-plus"></i> Add To Cart
                      </button>
                    </div>
                  </div>
                </div>

                <div v-if="totalPages > 1" class="flex items-center justify-center gap-2 pt-6">
                  <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-black uppercase text-gray-700 hover:bg-gray-900 hover:text-white disabled:opacity-40 disabled:hover:bg-transparent disabled:hover:text-gray-700 transition-all">
                    Prev
                  </button>
                  <button v-for="page in totalPages" :key="page" @click="changePage(page)"
                    :class="['w-9 h-9 rounded-xl text-xs font-black transition-all', currentPage === page ? 'bg-[#e31e24] text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-50']">
                    {{ page }}
                  </button>
                  <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-black uppercase text-gray-700 hover:bg-gray-900 hover:text-white disabled:opacity-40 disabled:hover:bg-transparent disabled:hover:text-gray-700 transition-all">
                    Next
                  </button>
                </div>

              </div>
            </main>

          </div>
        </div>
      </div>
      <PageFooter />
    </article>
  </section>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

// Global Shared State Import
const { addToCart } = useCart()

const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const route = useRoute()
const router = useRouter()

const products = ref([])
const dynamicCategories = ref([])
const loading = ref(false)
const loadingCategories = ref(false)
const activeCatGroup = ref(null)

// Local state tracking reactive loops for product quantites counters
const quantities = ref({})

const sortBy = ref(route.query.order_by || 'relevance')
const currentPage = computed(() => parseInt(route.query.page) || 1)
const selectedCategorySlug = computed(() => route.query.category || '')
const totalPages = ref(1)

const toggleCategory = (groupId) => {
  activeCatGroup.value = activeCatGroup.value === groupId ? null : groupId
}

const checkRouteValidity = () => {
  if (!route.query.year || !route.query.make || !route.query.model || !route.query.submodel || !route.query.engine) {
    router.replace('/')
  }
}
// 🟢 NEW: Input validation for manual entries
const validateQuantity = (productId) => {
  const qty = quantities.value[productId]
  if (!qty || parseInt(qty) < 1 || isNaN(qty)) {
    quantities.value[productId] = 1
  }
}
const fetchVehicleFacetedCategories = async () => {
  if (!route.query.year) return
  loadingCategories.value = true
  try {
    const data = await $fetch(`${WP_URL}/wp-json/custom/v1/vehicle-categories`, {
      method: 'GET',
      params: {
        year: route.query.year || '',
        make: route.query.make || '',
        model: route.query.model || '',
        submodel: route.query.submodel || '',
        engine: route.query.engine || ''
      }
    })

    if (Array.isArray(data)) {
      dynamicCategories.value = data.filter(c => c.parent_id === 0).map(parentCat => ({
        id: parentCat.id,
        name: parentCat.name,
        slug: parentCat.slug,
        children: data.filter(child => child.parent_id === parentCat.id)
      }))
    } else {
      dynamicCategories.value = []
    }
  } catch (err) {
    console.error("Failed to load targeted active vehicle categories:", err)
    dynamicCategories.value = []
  } finally {
    loadingCategories.value = false
  }
}

const triggerFetch = async () => {
  loading.value = true
  try {
    const response = await $fetch(`${WP_URL}/wp-json/custom/v1/products-filter`, {
      method: 'GET',
      params: {
        year: route.query.year || '',
        make: route.query.make || '',
        model: route.query.model || '',
        submodel: route.query.submodel || '',
        engine: route.query.engine || '',
        page: currentPage.value,
        category: selectedCategorySlug.value || undefined,
        sort: sortBy.value
      }
    })

    const items = response?.data || response || []
    products.value = items
    totalPages.value = response?.total_pages || 1

    // Initialize quantity model targets for fetched variables 
    items.forEach(p => {
      if (!quantities.value[p.id]) {
        quantities.value[p.id] = 1
      }
    })
  } catch (err) {
    console.error("Fitment collection processing failure:", err)
    products.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
}

// Action Trigger Handler for Cart State Update
const handleAddToCart = (product) => {
  const selectedQty = quantities.value[product.id] || 1
  addToCart(product, selectedQty)

  // Custom temporary alert visual cue
  alert(`${product.title} (${selectedQty} Qty) added to shopping cart!`)
}

const changePage = (newPage) => {
  if (newPage < 1 || newPage > totalPages.value) return
  router.push({ query: { ...route.query, page: newPage } })
}

const changeSort = () => {
  router.push({ query: { ...route.query, page: 1, order_by: sortBy.value } })
}

const filterBySubCategory = (subSlug) => {
  router.push({ query: { ...route.query, page: 1, category: subSlug || undefined } })
}

watch(() => route.query, (newQuery, oldQuery) => {
  if (!newQuery.year || !newQuery.make || !newQuery.model || !newQuery.submodel || !newQuery.engine) {
    router.replace('/')
    return
  }

  if (oldQuery && (
    newQuery.year !== oldQuery.year ||
    newQuery.make !== oldQuery.make ||
    newQuery.model !== oldQuery.model ||
    newQuery.submodel !== oldQuery.submodel ||
    newQuery.engine !== oldQuery.engine
  )) {
    fetchVehicleFacetedCategories()
  }
  triggerFetch()
}, { deep: true })

onMounted(() => {
  checkRouteValidity()

  if (route.query.year && route.query.make && route.query.model && route.query.submodel && route.query.engine) {
    fetchVehicleFacetedCategories()
    triggerFetch()
  }
})
</script>