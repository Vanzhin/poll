<template>
  <div>
    <TheHeader />
    
    <slot />
    <TheFooter />
  </div>
</template>
<script setup>
  const route = useRoute()
  const page = ref(route.params.page ? +route.params.page: 1)
  onMounted(() => {
    
      setTimeout(()=>{
        window.scrollTo(pageXOffset, 0)
      },2000)
      
    
  })


 
</script>