// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: true,  // Agar aapko Server Side Rendering chahiye 
  app:{
    
    baseURL: '/',
    head: {
      title:"x-trekkers",
      link: [
        { rel: 'icon', type: 'image/x-icon', href: 'Logo.png' },
        // Add other head meta tags or links if needed
      ],
    },
  },
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
 
  build: {
    transpile: ['swiper'],
  },
  devtools: { enabled: true },
})
