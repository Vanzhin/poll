<template>
  <div >
    <TheHomePage />
  </div>
</template>
<script setup>
  import { usePaginationStore } from '../stores/PaginationStore'
  
  const pagination = usePaginationStore()
  const route = useRoute()
  pagination.urlToChange(route.path)
  
  onMounted(() => {
    // console.dir( window)
    // window.scrollTo(pageXOffset,0)
    // window.scrollY = 0
    // window.addEventListener('scroll', function() {
    //   console.log(window.scrollY )
    //   // // window.scrollY = 0
      setTimeout(()=>{
        window.scrollTo(pageXOffset, 0)
      },2000)
      
    // });
  })


  
</script>
