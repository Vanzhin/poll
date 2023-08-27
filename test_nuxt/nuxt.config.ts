// https://nuxt.com/docs/api/configuration/nuxt-config
import { resolve } from "path"
export default defineNuxtConfig({
  // alias: {
  //   "@": resolve(__dirname, "/")
  // },
  devtools: { enabled: true },
 
  modules: [
    // "@/node_modules/bootstrap/dist/js/bootstrap.min.js"
    // 'bootstrap-vue/nuxt'
    '@pinia/nuxt',
    // '@artmizu/yandex-metrika-nuxt',
    // [
    //   '@rkaliev/nuxtjs-yandex-metrika',
    //   {
    //     id: '93328607',
    //     webvisor: true,
    //     clickmap: true,
    //     debug: false,
    //     noJS: false,
    //     disabled: false,
    //   },
    // ],
  ],
  // yandexMetrika: {id:"93328607"},
 
  
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
