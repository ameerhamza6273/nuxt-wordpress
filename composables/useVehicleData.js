// composables/useVehicleData.js
// Single source of truth for all vehicle make/model data pulled from the
// WordPress fitment API. Previously HeroSection, PageNavbar and
// SpecialistMakes each had their own copy of this fetch logic - that meant
// three separate network calls doing the same job, and the navbar menu only
// ever populated on pages where HeroSection also happened to mount (i.e.
// only the home page). Now every consumer shares the same cached state and
// fetch-once guards below.
import { ref } from 'vue'

export const WP_URL = 'https://qsz.zoy.temporary.site/website_11f3c7a8'

// Brands featured in the navbar mega-menu / mobile menu
const NAV_MENU_BRANDS = ['BMW', 'Mercedes-Benz', 'Land Rover']

// [{ name, slug }] - every distinct make in the fitment table (SpecialistMakes cards, Hero make dropdown)
export const allMakes = ref([])
// Tracks completion (success OR failure) separately from an empty result, so consumers
// can stop showing a loading state even if the fetch came back empty/errored.
export const allMakesLoaded = ref(false)

// [{ brand, categories: [modelName, ...] }] - navbar mega-menu structure
export const navBrandsData = ref([])

let makesPromise = null
let navMenuPromise = null

/**
 * Generic cascading-attribute fetch (year/make/model/submodel/engine) used by
 * the Hero search form's dependent dropdowns.
 */
export async function fetchVehicleAttribute(slugType, parentSlug = '') {
  try {
    const cleanParent = parentSlug ? encodeURIComponent(String(parentSlug).trim()) : ''
    const url = `${WP_URL}/wp-json/custom/v2/vehicle?slug=${slugType}&parent=${cleanParent}`
    const data = await $fetch(url, { method: 'GET' })
    return Array.isArray(data) ? data : []
  } catch (err) {
    console.error(`Vehicle attribute fetch failed for ${slugType}:`, err)
    return []
  }
}

/**
 * Full list of makes, fetched once and shared. Safe to call from multiple
 * components mounting at the same time - they'll all await the same request.
 */
export function ensureAllMakes() {
  if (!makesPromise) {
    makesPromise = fetchVehicleAttribute('pa_make').then((data) => {
      allMakes.value = data
      allMakesLoaded.value = true
      return data
    })
  }
  return makesPromise
}

/**
 * Navbar mega-menu brand -> model list, fetched once and shared.
 */
export function ensureNavMenu() {
  if (!navMenuPromise) {
    navMenuPromise = Promise.all(
      NAV_MENU_BRANDS.map((brand) =>
        fetchVehicleAttribute('pa_model', brand).then((models) => ({
          brand,
          categories: models.map((m) => m.name).filter(Boolean)
        }))
      )
    ).then((structure) => {
      navBrandsData.value = structure
      return structure
    })
  }
  return navMenuPromise
}
