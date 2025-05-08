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
  nitro: {
    devProxy: {
      '/wp-json': {
        target: 'https://cms.x-trekkers.com/wp-json',  // ← replace with your WPX subdomain
        changeOrigin: true,
      },
      '/graphql': {
        target: 'https://cms.x-trekkers.com/graphql',  // ← replace with your WPX subdomain
        changeOrigin: true,
      },
    }
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
      wordpressUrl: 'https://cms.x-trekkers.com/graphql',
      BASE_URL: 'https://cms.x-trekkers.com/wp-json/wp/v2',
      GOOGLE_SHEETS_HOOK_URL: process.env.GOOGLE_SHEETS_HOOK_URL,
      RESEND_API_KEY: process.env.RESEND_API_KEY,
      FROM_EMAIL: process.env.FROM_EMAIL,
      TO_EMAIL: process.env.TO_EMAIL,
      INFO_XT_EMAIL: process.env.INFO_XT_EMAIL,
      TEST_EMAIL: process.env.TEST_EMAIL,
      TEST_EMAIL_2: process.env.TEST_EMAIL_2,
    },
  },
  build: {
    transpile: ['swiper'],
  },
  devtools: { enabled: true },
});

