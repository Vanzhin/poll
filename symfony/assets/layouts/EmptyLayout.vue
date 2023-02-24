<template>
  <div class="backgraund">
    <RouterView />
  </div>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
export default {
  components: {
  },
} 
</script>
<style lang="scss" scoped>
.backgraund{
  width: 100%;
  height: 100%;
  background-color: rgb(163 156 156);
  padding-top: 1px;
  
}
</style>