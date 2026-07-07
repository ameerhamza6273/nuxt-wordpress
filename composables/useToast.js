// composables/useToast.js
// Lightweight shared toast queue - replaces native alert() popups everywhere
// in the app with a consistent, dismissible, non-blocking notification.
import { ref } from 'vue'

export const toasts = ref([])
let idCounter = 0

/**
 * @param {string} message
 * @param {'success'|'error'|'info'} type
 * @param {number} duration ms before auto-dismiss
 */
export function showToast(message, type = 'info', duration = 4500) {
  const id = ++idCounter
  toasts.value.push({ id, message, type })
  setTimeout(() => dismissToast(id), duration)
  return id
}

export function dismissToast(id) {
  toasts.value = toasts.value.filter((t) => t.id !== id)
}
