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
    
  ],
  // pinia: {
  //   autoImports: [
  //     // automatically imports `defineStore`
  //     'defineStore', // import { defineStore } from 'pinia'
  //     ['defineStore', 'definePiniaStore'], // import { defineStore as definePiniaStore } from 'pinia'
  //   ],
  // },
  
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
