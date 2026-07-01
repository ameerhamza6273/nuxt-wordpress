<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'

// 🔴 APNA WORDPRESS URL YAHAN LIKHEIN (Bina aakhri slash / ke)
const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8' 

const router = useRouter()

const years = ref([])
const makes = ref([])
const models = ref([])
const submodels = ref([]) // 🟢 Naya state
const engines = ref([])   // 🟢 Naya state

const selectedYear = ref('')
const selectedMake = ref('')
const selectedModel = ref('')
const selectedSubmodel = ref('') // 🟢 Naya selection
const selectedEngine = ref('')   // 🟢 Naya selection

const loadingYears = ref(false)
const loadingMakes = ref(false)
const loadingModels = ref(false)
const loadingSubmodels = ref(false) // 🟢 Naya loader
const loadingEngines = ref(false)   // 🟢 Naya loader

// Direct WordPress Fetcher (Updated to v2/vehicle)
const fetchAttributes = async (slugType, targetRef, loadingRef, parentSlug = '') => {
  loadingRef.value = true
  try {
    const cleanParent = parentSlug ? encodeURIComponent(String(parentSlug).trim()) : ''
    
    // Naya Full-Proof route v2/vehicle jise humne test kiya tha
    const apiUrl = `${WP_URL}/wp-json/custom/v2/vehicle?slug=${slugType}&parent=${cleanParent}`
    
    const data = await $fetch(apiUrl, { method: 'GET' })
    targetRef.value = data || []
  } catch (err) {
    console.error(`Direct connection crash on ${slugType}:`, err)
    targetRef.value = []
  } finally {
    loadingRef.value = false
  }
}

// Watcher 1: Year badalne par Makes load karein
watch(selectedYear, (newYear) => {
  selectedMake.value = ''
  selectedModel.value = ''
  selectedSubmodel.value = ''
  selectedEngine.value = ''
  makes.value = []
  models.value = []
  submodels.value = []
  engines.value = []

  if (newYear && String(newYear).trim() !== '') {
    fetchAttributes('pa_make', makes, loadingMakes, newYear)
  }
})

// Watcher 2: Make badalne par Models load karein
watch(selectedMake, (newMake) => {
  selectedModel.value = ''
  selectedSubmodel.value = ''
  selectedEngine.value = ''
  models.value = []
  submodels.value = []
  engines.value = []

  if (newMake && String(newMake).trim() !== '' && selectedYear.value) {
    const comboParam = `${String(selectedYear.value).trim()}|${String(newMake).trim()}`
    fetchAttributes('pa_model', models, loadingModels, comboParam)
  }
})

// Watcher 3: Model badalne par Submodels load karein (🟢 NEW)
watch(selectedModel, (newModel) => {
  selectedSubmodel.value = ''
  selectedEngine.value = ''
  submodels.value = []
  engines.value = []

  if (newModel && String(newModel).trim() !== '' && selectedMake.value && selectedYear.value) {
    // Unique slug se database ka original case-sensitive Name nikalna
    const activeModelObj = models.value.find(mod => mod.slug === newModel)
    const modelName = activeModelObj ? activeModelObj.name : newModel

    // Original accurate names ka combo string parameter
    const comboParam = `${String(selectedYear.value).trim()}|${String(selectedMake.value).trim()}|${String(modelName).trim()}`
    fetchAttributes('pa_submodel', submodels, loadingSubmodels, comboParam)
  }
})

// Watcher 4: Submodel badalne par Engines load karein (PERFECT CLEAN STRING)
watch(selectedSubmodel, (newSubmodel) => {
  selectedEngine.value = ''
  engines.value = []

  if (newSubmodel && String(newSubmodel).trim() !== '') {
    const activeModelObj = models.value.find(mod => mod.slug === selectedModel.value)
    const modelName = activeModelObj ? activeModelObj.name : selectedModel.value

    const activeSubObj = submodels.value.find(sub => sub.slug === newSubmodel)
    const subName = activeSubObj ? activeSubObj.name : newSubmodel

    const comboParam = `${String(selectedYear.value).trim()}|${String(selectedMake.value).trim()}|${String(modelName).trim()}|${String(subName).trim()}`
    fetchAttributes('pa_engine', engines, loadingEngines, comboParam)
  }
})

const handleSearch = () => {
  if (!selectedYear.value || !selectedMake.value || !selectedModel.value || !selectedSubmodel.value || !selectedEngine.value) return

  // Real data arrays se objects dhoondhein taake direct case-sensitive standard titles bhej sakein
  const activeMakeObj = makes.value.find(m => m.slug === selectedMake.value)
  const activeModelObj = models.value.find(mod => mod.slug === selectedModel.value)
  const activeSubmodelObj = submodels.value.find(sub => sub.slug === selectedSubmodel.value)
  const activeEngineObj = engines.value.find(eng => eng.slug === selectedEngine.value)

  // URL query payload with all 5 parameters
  router.push({
    path: '/products',
    query: {
      year: selectedYear.value, 
      make: activeMakeObj ? activeMakeObj.name : selectedMake.value, 
      model: activeModelObj ? activeModelObj.name : selectedModel.value, 
      submodel: activeSubmodelObj ? activeSubmodelObj.name : selectedSubmodel.value, // 🟢 NEW
      engine: activeEngineObj ? activeEngineObj.name : selectedEngine.value,         // 🟢 NEW
      page: 1
    }
  })
}

onMounted(() => {
  fetchAttributes('pa_year', years, loadingYears)
})
</script>

<template>
  <section class="relative min-h-[650px] flex items-center py-16 md:py-24 overflow-hidden bg-gray-900">
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent z-10"></div>
      <NuxtImg src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1920" alt="Hero Background"
        class="w-full h-full object-cover" loading="lazy" />
    </div>

    <div class="container max-w-[1300px] mx-auto px-4 md:px-6 relative z-20">
      <div class="flex flex-col lg:flex-row items-center gap-12">

        <div class="w-full lg:w-7/12 text-center lg:text-left">
          <h1 class="text-4xl md:text-6xl font-black text-white leading-[1.1] mb-6 tracking-tighter">
            DRIVEN BY <br>
            <span class="text-[#f2a900]">QUALITY</span> EXPERTISE
          </h1>
          <p class="text-lg md:text-xl text-gray-300 max-w-[550px] mb-8 leading-relaxed mx-auto lg:mx-0">
            Your trusted source for Genuine, OE, & OEM Online Car Parts.
            Specializing in high-performance European vehicles with precision and care.
          </p>

          <div class="hidden lg:flex gap-3">
            <span class="bg-[#e31e24] text-white text-[11px] font-black px-4 py-2 rounded uppercase tracking-wider">
              Lifetime Warranty
            </span>
            <span class="bg-[#f2a900] text-black text-[11px] font-black px-4 py-2 rounded uppercase tracking-wider">
              Fast Shipping
            </span>
          </div>
        </div>

        <div class="w-full lg:w-5/12 max-w-[500px]">
          <div class="bg-white rounded-xl p-8 shadow-2xl border-t-[6px] border-[#e31e24]">
            <h4 class="text-2xl font-black text-gray-900 text-center mb-8 uppercase tracking-wide">
              Select Your Vehicle
            </h4>

            <form @submit.prevent="handleSearch" class="space-y-5">
              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                  Select Year
                </label>
                <select v-model="selectedYear"
                  class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-[#f2a900] outline-none transition-all cursor-pointer">
                  <option value="" disabled>Select Year</option>
                  <option v-for="y in years" :key="y.slug" :value="y.slug">{{ y.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                  Select Make <span v-if="loadingMakes"
                    class="text-xs text-[#e31e24] lowercase animate-pulse">(fetching...)</span>
                </label>
                <select v-model="selectedMake" :disabled="!selectedYear || loadingMakes"
                  class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-bold text-gray-800 disabled:opacity-50 focus:bg-white focus:ring-2 focus:ring-[#f2a900] outline-none transition-all cursor-pointer">
                  <option value="" disabled>Select Make</option>
                  <option v-for="m in makes" :key="m.slug" :value="m.slug">{{ m.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                  Select Model <span v-if="loadingModels"
                    class="text-xs text-[#e31e24] lowercase animate-pulse">(fetching...)</span>
                </label>
                <select v-model="selectedModel" :disabled="!selectedMake || loadingModels"
                  class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-bold text-gray-800 disabled:opacity-50 focus:bg-white focus:ring-2 focus:ring-[#f2a900] outline-none transition-all cursor-pointer">
                  <option value="" disabled>Select Model</option>
                  <option v-for="mod in models" :key="mod.slug" :value="mod.slug">
                    {{ mod.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                  Select Submodel <span v-if="loadingSubmodels"
                    class="text-xs text-[#e31e24] lowercase animate-pulse">(fetching...)</span>
                </label>
                <select v-model="selectedSubmodel" :disabled="!selectedModel || loadingSubmodels"
                  class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-bold text-gray-800 disabled:opacity-50 focus:bg-white focus:ring-2 focus:ring-[#f2a900] outline-none transition-all cursor-pointer">
                  <option value="" disabled>Select Submodel</option>
                  <option v-for="sub in submodels" :key="sub.slug" :value="sub.slug">
                    {{ sub.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">
                  Select Engine Size <span v-if="loadingEngines"
                    class="text-xs text-[#e31e24] lowercase animate-pulse">(fetching...)</span>
                </label>
                <select v-model="selectedEngine" :disabled="!selectedSubmodel || loadingEngines"
                  class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-bold text-gray-800 disabled:opacity-50 focus:bg-white focus:ring-2 focus:ring-[#f2a900] outline-none transition-all cursor-pointer">
                  <option value="" disabled>Select Engine Size</option>
                  <option v-for="eng in engines" :key="eng.slug" :value="eng.slug">
                    {{ eng.name }}
                  </option>
                </select>
              </div>

              <button type="submit" :disabled="!selectedEngine"
                class="w-full bg-[#e31e24] hover:bg-black disabled:bg-gray-200 text-white disabled:text-gray-400 font-black py-5 rounded-xl flex items-center justify-center gap-3 transition-all duration-300 shadow-xl uppercase tracking-widest text-xs mt-4">
                <i class="fa-solid fa-car-side"></i> Search Catalog
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<style scoped>
h1 {
  text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
section {
  background-attachment: fixed;
}
select:focus {
  background-color: white;
}
</style>