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
    'nuxt-resend',
    '@nuxt/image'
  ],
  compatibilityDate: '2024-11-01',
  css: [
    '~/assets/main.css',
    '@fortawesome/fontawesome-free/css/all.min.css', // Add Font Awesome CSS
  ],
  runtimeConfig: {
    public: {
      wordpressUrl: 'https://www.x-trekkers.com/graphql',
      BASE_URL: 'https://www.x-trekkers.com/wp-json/wp/v2',
      RESEND_API_KEY: process.env.RESEND_API_KEY,
      FROM_EMAIL: process.env.FROM_EMAIL,
      TO_EMAIL: process.env.TO_EMAIL,
      SUBJECT: process.env.SUBJECT,
      CLIENT_EMAIL: process.env.CLIENT_EMAIL,
      REPLY_TO_EMAIL: process.env.REPLY_TO_EMAIL,
      GOOGLE_SHEETS_HOOK_URL: 'https://script.google.com/macros/s/AKfycbwBBuLMJxDVqpyD-q7pKAlRrADDS5JjjdYtBJp3ZVschc_ClC32kHZqAzN-tZuBiP0UPQ/exec?gid=0',
    },
  },
  build: {
    transpile: ['swiper'],
  },
  devtools: { enabled: true },
});

