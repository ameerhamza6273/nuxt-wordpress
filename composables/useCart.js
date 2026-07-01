// composables/useCart.js
import { useState } from '#app'

export const useCart = () => {
  // Global cart array to maintain items
  const cart = useState('global_cart', () => [])

  // Action to add product with quantity
  const addToCart = (product, quantity) => {
    const qty = parseInt(quantity) || 1
    const existingItem = cart.value.find(item => item.id === product.id)

    if (existingItem) {
      existingItem.quantity += qty
    } else {
      cart.value.push({
        id: product.id,
        title: product.title,
        price: product.price,
        image: product.image,
        quantity: qty
      })
    }
  }

  // Total items dynamic computed target count
  const totalCartItems = computed(() => {
    return cart.value.reduce((total, item) => total + item.quantity, 0)
  })

  return {
    cart,
    addToCart,
    totalCartItems
  }
}