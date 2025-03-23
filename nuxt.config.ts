// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: true,
  app: {
    baseURL: '/',
    head: {
      title: 'x-trekkers',
      link: [
        { rel: 'icon', type: 'image/x-icon', href: 'Logo.png' },
      ],
    },
  },
  modules: [
    '@nuxt/devtools',
    '@nuxtjs/tailwindcss',
  ],
  compatibilityDate: '2024-11-01',
  css: [
    '~/assets/main.css',
    '@fortawesome/fontawesome-free/css/all.min.css', // Add Font Awesome CSS
  ],
  runtimeConfig: {
    public: {
      wordpressUrl: 'https://www.x-trekkers.com/graphql',
    },
  },
  build: {
    transpile: ['swiper'],
  },
  devtools: { enabled: true },
});
