<template>
  <Loader v-if="loading" />
  <div class="app-main-layout" v-else>
    <Headers/>
    <Sidebar v-model="isOpen" />
    <main class="app-content" :class="{full: !isOpen}">
      <div class="app-page">
        <router-view />
      </div>
    </main>
      
    
  </div>
</template>
<script>
  import { RouterView } from 'vue-router'
  import Headers from '../components/Headers.vue'
  import Loader from '../components/ui/Loader.vue';
  import Sidebar from '../components/Admin/Sidebar.vue';
  export default {
    data: () => ({
      isOpen: true,
      loading: false
    }),
    components: {
      Headers, Loader, Sidebar
    },
  } 
</script>
<style lang="scss">
.app{
  &-page{
    padding:2rem;
    background-color: rgb(237 237 237);
    min-height: 91%;
    z-index: 19;
  };
  &-content{
    padding-top:80px;
    padding-left:250px;
    transition:padding-left .3s;
    position:relative;
    @media (max-width: 768px) {
      padding-left:60px;
    }
  };
  &-main-layout{
    position:relative;
    min-height:100vh;
    overflow-y:auto;
  };

}

</style>