// composables/useProducts.js
export const useProducts = () => {
  const products = ref([])
  const loading = ref(false)
  const page = ref(1)
  const search = ref('')
  const selectedCategory = ref('')

  // Main fetch function jo hamare server route ko hit karega
  const loadProducts = async () => {
    loading.value = true
    try {
      const data = await $fetch('/api/products', {
        query: {
          page: page.value,
          search: search.value || undefined,
          category: selectedCategory.value || undefined
        }
      })
      
      // Agar pagination 'Load More' wali hai to data push karein, agar simple hai to replace karein
      if (page.value === 1) {
        products.value = data
      } else {
        products.value = [...products.value, ...data]
      }
    } catch (err) {
      console.error('Error loading products:', err)
    } finally {
      loading.value = false
    }
  }

  // Jab bhi filters badlein to reset kar ke fetch karein
  const applyFilters = () => {
    page.value = 1
    loadProducts()
  }

  return {
    products,
    loading,
    page,
    search,
    selectedCategory,
    loadProducts,
    applyFilters
  }
}