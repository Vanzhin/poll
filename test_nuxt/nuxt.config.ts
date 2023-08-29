// https://nuxt.com/docs/api/configuration/nuxt-config
import { resolve } from "path"
export default defineNuxtConfig({
  // alias: {
  //   "@": resolve(__dirname, "/")
  // },
  axios: {
    withCredentials: true
  },
  devtools: { enabled: true },
  runtimeConfig: {
    // Public keys that are exposed to the client
    public: {
      urlApi: process.env.NUXT_PUBLIC_URL_API || '/api'
    }
  },
  modules: [
    // "@/node_modules/bootstrap/dist/js/bootstrap.min.js"
    // 'bootstrap-vue/nuxt'
    '@pinia/nuxt',
    '@artmizu/yandex-metrika-nuxt',
  ],
  yandexMetrika: {id:"93328607"},
  css: [
    "bootstrap/dist/css/bootstrap.min.css",
    '~/assets/styles/app.scss'
  ],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  
  
})
