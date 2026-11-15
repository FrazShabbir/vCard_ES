// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  // DevTools off by default — older versions had RCE advisories; enable only when needed
  devtools: { enabled: false },
  css: ['~/assets/css/tokens.css', '~/assets/css/base.css'],
  typescript: {
    strict: true,
    typeCheck: false,
  },
  runtimeConfig: {
    apiInternal: process.env.NUXT_API_INTERNAL || 'http://vcards.lan',
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://vcards.lan',
      appName: process.env.NUXT_PUBLIC_APP_NAME || 'vCard',
    },
  },
  nitro: {
    routeRules: {
      '/api/**': {
        proxy: (process.env.NUXT_API_INTERNAL || 'http://vcards.lan') + '/api/**',
      },
      '/sanctum/**': {
        proxy: (process.env.NUXT_API_INTERNAL || 'http://vcards.lan') + '/sanctum/**',
      },
    },
  },
  app: {
    head: {
      title: 'vCard',
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      ],
      link: [
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,700&display=swap',
        },
      ],
    },
  },
})
