<template>
  <section>
    <article>
      <PageNavbar />
      <div class="py-12 bg-[#f8f9fa] min-h-screen">
        <div class="container max-w-[1300px] mx-auto px-4">

          <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

            <aside class="space-y-6 lg:sticky lg:top-6">
              <div v-if="isSearchMode" class="bg-gray-900 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl">
                <span class="text-[10px] font-black tracking-widest text-[#f2a900] uppercase block mb-1">
                  Part / SKU Search
                </span>
                <h4 class="text-xl font-black uppercase tracking-tight break-words">
                  "{{ route.query.q }}"
                </h4>
                <button @click="router.push('/')"
                  class="mt-4 text-xs font-black text-[#e31e24] uppercase tracking-wider flex items-center gap-2 hover:text-white transition-colors">
                  <i class="fa-solid fa-rotate"></i> Start A New Search
                </button>
              </div>

              <div v-else-if="isBrandMode" class="bg-gray-900 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl">
                <span class="text-[10px] font-black tracking-widest text-[#f2a900] uppercase block mb-1">
                  Shopping By Brand
                </span>
                <h4 class="text-xl font-black uppercase tracking-tight">
                  {{ route.query.brand }}
                </h4>
                <button @click="router.push('/')"
                  class="mt-4 text-xs font-black text-[#e31e24] uppercase tracking-wider flex items-center gap-2 hover:text-white transition-colors">
                  <i class="fa-solid fa-rotate"></i> Search By Vehicle Instead
                </button>
              </div>

              <div v-else class="bg-gray-900 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl">
                <span class="text-[10px] font-black tracking-widest text-[#f2a900] uppercase block mb-1">
                  My Garage
                </span>
                <h4 class="text-xl font-black uppercase tracking-tight">
                  {{ route.query.year || '' }} {{ route.query.make || 'Select Vehicle' }}
                </h4>
                <p class="text-xs text-gray-300 mt-1 font-bold">
                  {{ route.query.model || 'All Models' }}
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

              <div v-if="!isBrandMode && !isSearchMode" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
  <h5 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-6 border-b pb-4 border-gray-100 flex items-center gap-2">
    <i class="fa-solid fa-car text-[#e31e24]"></i> Filter By Vehicle
  </h5>

  <div v-if="loadingCategories" class="space-y-3 py-4">
    <div class="h-4 bg-gray-100 rounded-md animate-pulse w-3/4"></div>
    <div class="h-4 bg-gray-100 rounded-md animate-pulse w-5/6"></div>
    <div class="h-4 bg-gray-100 rounded-md animate-pulse w-2/3"></div>
  </div>

  <div v-else class="space-y-4">
    
    <div v-for="model in sidebarVehicles" :key="model.id" class="space-y-2">
  
  <button @click="toggleCategory(model.id)"
    :class="['w-full flex items-center justify-between text-left font-black text-sm uppercase transition-colors py-1', (route.query.model === model.slug || route.query.model === model.name) ? 'text-[#e31e24]' : 'text-gray-800 hover:text-[#e31e24]']">
    <span>{{ model.name }}</span>
    <i :class="['fa-solid text-[10px] transition-transform ml-2', activeCatGroup === model.id ? 'fa-chevron-down rotate-180' : 'fa-chevron-right']"></i>
  </button>

  <div v-if="activeCatGroup === model.id" class="pl-3 space-y-3 border-l-2 border-gray-100 mt-1">
    
    <div v-for="sub in model.submodels" :key="sub.id" class="space-y-1">
      <button @click="filterBySidebarSubmodel(model.slug, sub.slug)"
        :class="['w-full text-left text-xs font-black transition-colors block py-0.5', (route.query.submodel === sub.slug || route.query.submodel === sub.name) ? 'text-[#e31e24]' : 'text-gray-700 hover:text-[#e31e24]']">
        ▸ {{ sub.name }}
      </button>

      <div v-if="(route.query.submodel === sub.slug || route.query.submodel === sub.name) && sub.engines && sub.engines.length > 0" class="pl-4 pt-0.5 space-y-1">
        <button v-for="engine in sub.engines" :key="engine" 
          @click="filterBySidebarEngine(model.slug, sub.slug, engine)"
          :class="['w-full text-left text-[11px] font-medium block py-0.5 pl-2 border-l border-gray-200 transition-colors', route.query.engine === engine ? 'text-[#e31e24] font-bold' : 'text-gray-500 hover:text-gray-900']">
          • {{ engine }}
        </button>
      </div>
    </div>

  </div>
</div>

    <div v-if="sidebarVehicles.length === 0" class="text-xs text-gray-400 font-bold py-2 text-center">
      No vehicle configurations found.
    </div>

  </div>
</div>
            </aside>

            <main class="lg:col-span-3 space-y-6">
              <div
                class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                  <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Home / Parts Search</span>
                  <h2 v-if="isSearchMode"
                    class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter mt-1 leading-tight">
                    Results For <span class="text-[#e31e24]">"{{ route.query.q }}"</span>
                  </h2>
                  <h2 v-else-if="isBrandMode"
                    class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter mt-1 leading-tight">
                    {{ route.query.brand }} <span class="text-[#e31e24]">Parts</span>
                  </h2>
                  <h2 v-else
                    class="text-2xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter mt-1 leading-tight">
                    {{ route.query.year || '' }} {{ route.query.make || '' }} {{ route.query.model || 'All Available' }}
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

                  <NuxtLink :to="`/product/${product.id}`"
                    class="w-full md:w-44 h-32 flex-shrink-0 bg-gray-50 rounded-2xl p-4 flex items-center justify-center border border-gray-50">
                    <img :src="product.image || 'https://via.placeholder.com/150'"
                      class="max-h-full max-w-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform" />
                  </NuxtLink>

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

                    <NuxtLink :to="`/product/${product.id}`" class="block">
                      <h3
                        class="text-lg md:text-xl font-black text-gray-900 tracking-tight hover:text-[#e31e24] transition-colors leading-tight mb-2">
                        {{ product.title }}
                      </h3>
                    </NuxtLink>
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

                    <div class="w-full mt-4">
                      <NuxtLink :to="`/product/${product.id}`"
                        class="w-full bg-[#e31e24] hover:bg-black text-white font-black py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition-all duration-300 flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-circle-info"></i> View Details
                      </NuxtLink>
                    </div>
                  </div>
                </div>

                <div v-if="totalPages > 1"
                  class="flex flex-wrap items-center justify-center gap-2 pt-10 border-t border-gray-100 mt-8">

                  <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="px-4 h-10 rounded-xl border border-gray-200 text-xs font-black uppercase text-gray-700 bg-white transition-all duration-300
           hover:bg-gray-950 hover:text-white hover:border-gray-950
           disabled:opacity-40 disabled:hover:bg-white disabled:hover:text-gray-400 disabled:hover:border-gray-200 disabled:cursor-not-allowed flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> Prev
                  </button>

                  <div class="flex items-center gap-1.5">
                    <template v-for="(page, idx) in paginationRange" :key="`${page}-${idx}`">
                      <span v-if="page === '...'"
                        class="w-10 h-10 flex items-center justify-center text-xs font-black text-gray-400 select-none">
                        &hellip;
                      </span>
                      <button v-else @click="changePage(page)" :class="[
                        'w-10 h-10 rounded-xl text-xs font-black transition-all duration-300 shadow-sm flex items-center justify-center',
                        currentPage === page
                          ? 'bg-[#e31e24] text-white border border-[#e31e24] shadow-md shadow-[#e31e24]/20'
                          : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-900 hover:text-gray-900'
                      ]">
                        {{ page }}
                      </button>
                    </template>
                  </div>

                  <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                    class="px-4 h-10 rounded-xl border border-gray-200 text-xs font-black uppercase text-gray-700 bg-white transition-all duration-300
           hover:bg-gray-950 hover:text-white hover:border-gray-950
           disabled:opacity-40 disabled:hover:bg-white disabled:hover:text-gray-400 disabled:hover:border-gray-200 disabled:cursor-not-allowed flex items-center gap-1.5 shadow-sm">
                    Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
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

const route = useRoute()
const router = useRouter()
const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

const products = ref([])
const dynamicCategories = ref([])
const loading = ref(false)
const loadingCategories = ref(false)
const activeCatGroup = ref(null)
const totalPages = ref(1)

const sortBy = ref(route?.query?.order_by || 'relevance')

const currentPage = computed(() => {
  if (!route || !route.query) return 1
  return parseInt(route.query.page) || 1
})

// 🟢 Professional pagination: sirf current page ke aas-paas + start/end numbers dikhayein, baaki '...' se compress karein
const paginationRange = computed(() => {
  const total = totalPages.value
  const current = currentPage.value
  const siblingCount = 1

  const range = []
  for (let i = 1; i <= total; i++) {
    if (i === 1 || i === total || (i >= current - siblingCount && i <= current + siblingCount)) {
      range.push(i)
    }
  }

  const withDots = []
  let prev = 0
  for (const page of range) {
    if (prev && page - prev === 2) {
      withDots.push(prev + 1)
    } else if (prev && page - prev > 2) {
      withDots.push('...')
    }
    withDots.push(page)
    prev = page
  }

  return withDots
})

const selectedCategorySlug = computed(() => {
  if (!route || !route.query) return ''
  return route.query.category || ''
})

const toggleCategory = (groupId) => {
  activeCatGroup.value = activeCatGroup.value === groupId ? null : groupId
}

// 🟢 MODIFIED: Ab check safe hai, kam se kam 'make' (vehicle) YA 'brand' (manufacturer) YA 'q' (SKU/part search) mojood hona chahiye home page par wapas phenkne se pehle
const checkRouteValidity = () => {
  if (!route?.query || (!route.query.make && !route.query.brand && !route.query.q)) {
    router.replace('/')
  }
}

// Manufacturer-brand mode (Brembo/DFC/Pagid/etc, footer logo links) is a
// separate lookup from the Year/Make/Model vehicle flow below - no vehicle
// context required, so the sidebar tree and products-filter call are skipped.
const isBrandMode = computed(() => !!route.query.brand && !route.query.make)

// SKU/part-number search mode (navbar top search box) - the primary, fastest
// lookup path per the client. Independent of make/brand, so it also skips
// the vehicle sidebar tree.
const isSearchMode = computed(() => !!route.query.q && !route.query.make && !route.query.brand)

// Ek naya ref banayein jo vehicle hierarchy ko hold karega
const sidebarVehicles = ref([])

const fetchVehicleFacetedCategories = async () => {
  if (!route.query.make || isBrandMode.value || isSearchMode.value) return
  loadingCategories.value = true
  try {
    const currentMake = String(route.query.make).trim();

    // 🟢 SOLID FIX: Sidebar tree humesha direct pure make ka mangwayenge bina year ke condition ke, 
    // taaki form se aane par bhi saare models-submodels humesha load hon!
    const data = await $fetch(`${WP_URL}/wp-json/custom/v2/vehicle`, {
      method: 'GET',
      params: {
        slug: 'pa_sidebar_tree',
        make: currentMake
      }
    })

    if (Array.isArray(data) && data.length > 0) {
      sidebarVehicles.value = data

      // 🟢 AUTO EXPAND: Agar URL mein model pehle se mojood hai (jaise Form se aane par),
      // toh us model ka dropdown automatic open kar do!
      const urlModel = route.query.model ? String(route.query.model).toLowerCase().trim() : '';
      if (urlModel) {
        const matchedModel = data.find(m => 
          m.slug === urlModel || 
          m.name.toLowerCase().trim() === urlModel
        );
        if (matchedModel) {
          activeCatGroup.value = matchedModel.id;
        }
      }
    } else {
      sidebarVehicles.value = []
    }
  } catch (err) {
    console.error("Failed to load backend vehicle tree:", err)
    sidebarVehicles.value = []
  } finally {
    loadingCategories.value = false
  }
}


const triggerFetch = async () => {
  if (!route.query.make && !route.query.brand && !route.query.q) return
  loading.value = true
  try {
    const response = isSearchMode.value
      ? await $fetch(`${WP_URL}/wp-json/custom/v1/product-search`, {
          method: 'GET',
          params: {
            q: route.query.q,
            page: currentPage.value,
            sort: sortBy.value
          }
        })
      : isBrandMode.value
      ? await $fetch(`${WP_URL}/wp-json/custom/v1/products-by-brand`, {
          method: 'GET',
          params: {
            brand: route.query.brand,
            page: currentPage.value,
            sort: sortBy.value
          }
        })
      : await $fetch(`${WP_URL}/wp-json/custom/v1/products-filter`, {
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

    // Backend ab { data, total_items, total_pages, current_page } shape return karta hai.
    if (response && Array.isArray(response.data)) {
      products.value = response.data
      totalPages.value = response.total_pages ? parseInt(response.total_pages) : 1
    }
    // Backward-compat: agar backend abhi purana bare-array format bhej raha hai
    else if (Array.isArray(response)) {
      products.value = response
      totalPages.value = 1
    }
    else {
      products.value = []
      totalPages.value = 1
    }

    if (isNaN(totalPages.value) || totalPages.value < 1) {
      totalPages.value = 1
    }

  } catch (err) {
    console.error("Fitment collection processing failure:", err)
    products.value = []
    totalPages.value = 1
  } finally {
    loading.value = false
  }
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

// 1. Model Click (Sirf dropdown toggle ke liye - Safe and Clean)
const filterBySidebarModel = (modelSlug) => {
  // Isko khali chodh dein taaki URL refresh na ho, sirf template se category toggle ho
}

// 🟢 1. FIXED SUBMODEL CLICK: Hamesha accurate string name bhejega
const filterBySidebarSubmodel = (modelSlug, submodelSlug) => {
  // Pura target model dhoondein
  const targetModel = sidebarVehicles.value.find(m => m.slug === modelSlug || m.id === modelSlug)
  const modelName = targetModel ? targetModel.name : modelSlug

  let submodelValue = submodelSlug
  if (targetModel && targetModel.submodels) {
    // Slug aur name dono check karein taaki galat value na jaye
    const targetSub = targetModel.submodels.find(s => s.slug === submodelSlug || s.name === submodelSlug)
    if (targetSub) {
      submodelValue = targetSub.name
    }
  }

  // Naya query object - Engine ko har haal mein clean (delete) karna hai naye submodel par
  const newQuery = {
    ...route.query, // Purane baki params (jaise page, year, make) barkarar rakhein
    model: String(modelName).trim(),
    submodel: String(submodelValue).trim(),
    page: 1
  }
  
  // URL se purana engine nikalne ke liye use delete kar dein
  delete newQuery.engine 

  router.push({ query: newQuery })
}

// 🟢 2. FIXED ENGINE CLICK: Exact accurate name pass karega bina crash hue
const filterBySidebarEngine = (modelSlug, submodelSlug, engineName) => {
  const targetModel = sidebarVehicles.value.find(m => m.slug === modelSlug || m.id === modelSlug)
  const modelName = targetModel ? targetModel.name : modelSlug

  let submodelValue = submodelSlug
  if (targetModel && targetModel.submodels) {
    const targetSub = targetModel.submodels.find(s => s.slug === submodelSlug || s.name === submodelSlug)
    if (targetSub) {
      submodelValue = targetSub.name
    }
  }

  const newQuery = {
    ...route.query,
    model: String(modelName).trim(),
    submodel: String(submodelValue).trim(),
    engine: String(engineName).trim(), // Direct target raw database name
    page: 1
  }

  router.push({ query: newQuery })
}

watch(() => route.query, (newQuery, oldQuery) => {
  if (!newQuery.make && !newQuery.brand && !newQuery.q) {
    router.replace('/')
    return
  }

  // Sidebar tree sirf make/year badalne par fetch ho (brand-mode/search-mode ismein shamil nahi)
  if (oldQuery && (newQuery.make !== oldQuery.make || newQuery.year !== oldQuery.year)) {
    fetchVehicleFacetedCategories()
  }

  // Kisi bhi filter/sort/page parameter ke badalne par products dobara fetch hon
  if (oldQuery && (
    newQuery.model !== oldQuery.model ||
    newQuery.submodel !== oldQuery.submodel ||
    newQuery.engine !== oldQuery.engine ||
    newQuery.brand !== oldQuery.brand ||
    newQuery.q !== oldQuery.q ||
    newQuery.page !== oldQuery.page ||
    newQuery.category !== oldQuery.category ||
    newQuery.order_by !== oldQuery.order_by
  )) {
    triggerFetch()
  }
}, { deep: true })

onMounted(() => {
  checkRouteValidity()

  if (isBrandMode.value || isSearchMode.value) {
    triggerFetch()
  } else if (route.query.make) {
    // Pehle sidebar tree mangwayenge, uske baad parameters automatic auto-expand ho jayenge
    fetchVehicleFacetedCategories()
    triggerFetch()
  }
})
</script>