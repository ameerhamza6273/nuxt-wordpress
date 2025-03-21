// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules:[
    '@nuxt/devtools',
    '@nuxtjs/tailwindcss',
  ],
  compatibilityDate: '2024-11-01',
  css: ['~/assets/main.css'],
  runtimeConfig: {
    public: {
      wordpressUrl: 'https://www.x-trekkers.com/graphql'
    },
  },
  app: {
    // This should handle trailing slashes in routes properly
    baseURL: '/',
  },
  build: {
    transpile: ['swiper'],
  },
  devtools: { enabled: true },
})
