// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
 
  modules: [
    // "@/node_modules/bootstrap/dist/js/bootstrap.min.js"
    // 'bootstrap-vue/nuxt'
  ],
  
  css: [
    "bootstrap/dist/css/bootstrap.min.css",
    '~/assets/styles/app.scss'
    
  ],
  
  
})
