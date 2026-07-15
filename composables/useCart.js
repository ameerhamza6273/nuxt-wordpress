// composables/useCart.js
import { ref, onMounted } from 'vue'

// Global shared state for cart items
export const cartItems = ref([])

/**
 * 1. Initialize & Hydrate Cart from LocalStorage
 * Page load hote hi browser memory se cart data reactive state mein load karega
 */
if (process.client) {
  const savedCart = localStorage.getItem('atms_cart')
  if (savedCart) {
    try {
      cartItems.value = JSON.parse(savedCart)
    } catch (e) {
      console.error("Cart hydration from localStorage failed:", e)
    }
  }
}

/**
 * 2. Add to Cart Function (With Multi-Quantity Support)
 * @param {Object} product - The product object fetched from WordPress API
 * @param {Number} selectedQty - Dynamic count passed from frontend input dropdown
 */
export const addToCart = (product, selectedQty = 1) => {
  // Check parsing to prevent string concatenation bugs (e.g. 1 + "1" = 11)
  const qtyToAdd = parseInt(selectedQty) || 1
  
  // Existing item reference matching check
  const exists = cartItems.value.find(item => item.id === product.id)
  
  if (!exists) {
    // Dynamic schema validation mapping values smoothly
    cartItems.value.push({
      id: product.id,
      title: product.title,
      price: product.price, // HTML format or plain string mapping
      sku: product.sku || 'N/A',
      image: product.image || 'https://via.placeholder.com/150',
      brand: product.brand || 'Premium OE',
      category_name: product.category_name || '',
      vin: product.vin || '',
      quantity: qtyToAdd
    })
  } else {
    // If item exists, sum up the selected quantities safely
    exists.quantity += qtyToAdd
  }
  
  // LocalStorage mapping persistence
  if (process.client) {
    localStorage.setItem('atms_cart', JSON.stringify(cartItems.value))
  }
}

/**
 * 3. Remove from Cart Function
 * @param {Number|String} productId - Target unique item identifier to drop
 */
export const removeFromCart = (productId) => {
  cartItems.value = cartItems.value.filter(item => item.id !== productId)
  
  if (process.client) {
    localStorage.setItem('atms_cart', JSON.stringify(cartItems.value))
  }
}