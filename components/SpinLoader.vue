<template>
  <div
    class="h-screen w-screen overflow-hidden fixed top-0 left-0 bg-[#161616] z-[9999] flex justify-center items-center flex-col"
  >
    <NuxtImg
      src="/Logo.png"
      class="w-60 animate-bubble"
      alt="user"
    />
  </div>
</template>

<style scoped>
@keyframes bubble {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}

.animate-bubble {
  animation: bubble 1s infinite alternate ease-in-out;
}
</style>
