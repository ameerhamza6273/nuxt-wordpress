<template>
  <div class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 w-[92vw] max-w-[380px] pointer-events-none">
    <TransitionGroup name="toast">
      <div v-for="toast in toasts" :key="toast.id"
        class="pointer-events-auto flex items-start gap-3 bg-white border rounded-2xl shadow-2xl p-4 pr-3"
        :class="borderClass(toast.type)">
        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" :class="iconBgClass(toast.type)">
          <i :class="[iconClass(toast.type), 'text-sm']"></i>
        </div>

        <p class="text-xs font-bold text-gray-800 leading-relaxed flex-grow pt-1.5">
          {{ toast.message }}
        </p>

        <button @click="dismissToast(toast.id)"
          class="text-gray-300 hover:text-gray-600 transition-colors flex-shrink-0 p-1.5 -mr-1">
          <i class="fa-solid fa-xmark text-xs"></i>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { toasts, dismissToast } from '~/composables/useToast.js'

const borderClass = (type) => ({
  success: 'border-emerald-200',
  error: 'border-[#e31e24]/20',
  info: 'border-gray-100'
}[type] || 'border-gray-100')

const iconBgClass = (type) => ({
  success: 'bg-emerald-50 text-emerald-500',
  error: 'bg-red-50 text-[#e31e24]',
  info: 'bg-gray-50 text-gray-500'
}[type] || 'bg-gray-50 text-gray-500')

const iconClass = (type) => ({
  success: 'fa-solid fa-circle-check',
  error: 'fa-solid fa-circle-exclamation',
  info: 'fa-solid fa-circle-info'
}[type] || 'fa-solid fa-circle-info')
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(40px);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(40px) scale(0.95);
}
.toast-leave-active {
  position: absolute;
  width: 100%;
}
</style>
