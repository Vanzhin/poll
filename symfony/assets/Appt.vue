<template>
  <component :is="layout" >
    <RouterView />
  </component>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import EmptyLayout from './layouts/EmptyLayout.vue'
import PageLayout from './layouts/PageLayout.vue'
import AdminLayout from './layouts/AdminLayout.vue'
export default {
  data() {
    return {
      
    }
  },
  computed:{
    layout(){
      return (this.$route.meta.loyout || 'page') + '-layout'
    }
  },
  components: {
    EmptyLayout, PageLayout, AdminLayout
  },
  
} 
</script>

