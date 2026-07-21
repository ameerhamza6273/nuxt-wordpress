<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

function onSaved() {
  navigateTo('/admin/products')
}
</script>

<template>
  <div class="max-w-3xl mx-auto space-y-6">
    <div class="text-center space-y-1">
      <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center mx-auto">
        <i class="fa-solid fa-plus"></i>
      </div>
      <h1 class="text-sm font-black uppercase tracking-wide text-gray-900">Add Product</h1>
      <p class="text-xs text-gray-400 font-bold">Fill in the details below to create a new product.</p>
    </div>
    <AdminProductForm :initial="{}" @saved="onSaved" />
  </div>
</template>
