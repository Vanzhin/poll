<template>
    <Loader 
      v-if="getIsLoaderQuestions"
    />
  <div class="app-main-layout" >
    <Headers/>
    <Sidebar v-model="isOpen" />
    <main class="app-content" :class="{full: !isOpen}">
      <div class="app-page">
        <RouterView/>
      </div>
    </main>
  </div>
</template>
<script>
  import { RouterView } from 'vue-router'
  import Headers from '../components/Headers.vue'
  import Loader from '../components/ui/Loader.vue';
  import Sidebar from '../components/Admin/Sidebar.vue';
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    data: () => ({
      isOpen: true,
      loading: false
    }),
    components: {
      Headers, Loader, Sidebar
    },
    computed:{ 
      ...mapGetters(["getIsLoaderQuestions"]),
    },
  } 
</script>
<style lang="scss">
.app{
  &-page{
    overflow: hidden ;
    padding: 0 ;
    background-color: rgb(237 237 237);
    min-height: 92%;
    z-index: 15;
  };
  &-content{
    overflow: hidden ;
    padding-top:80px;
    padding-left:250px;
    transition:padding-left .3s;
    position:relative;
    z-index: 15;
    @media (max-width: 768px) {
      padding-left:60px;
    }
  };
  &-main-layout{
    position:relative;
    height:100vh;
    overflow:hidden;
  };

}

</style>