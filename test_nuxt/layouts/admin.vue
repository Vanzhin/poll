<template>
  <!-- <Loader/> -->
<div class="app-main-layout" >
  <TheHeader/>
  <TheSidebar v-model="isOpen" />
  <main class="app-content" :class="{full: !isOpen}">
    <div class="app-page">
      <slot />
    </div>
  </main>
</div>
</template>
<script setup>
  definePageMeta({
    
    middleware: 'authadmin'
  });
  import { useUserStore  } from '../stores/UserStore'
  const isOpen = ref(true)
  onMounted(() => {
    console.log('А')
    const user = useUserStore()
    user.setTokenIsLocalStorage()
    setTimeout(()=>{
      window.scrollTo(pageXOffset, 0)
    },2000)
    
  }) 
  
 
 
</script>
<style lang="scss">
.app{
&-page{
  overflow: auto ;
  padding: 0 ;
  background-color: var(--color-fon);
  height: 91vh;
  z-index: 15;
};
&-content{
  overflow: hidden ;
  
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