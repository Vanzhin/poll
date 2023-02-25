<template>
  <Headers/>
  <div class="container">
    <div class="wrapper">
      <div class="head"></div>
    </div>
    <div class="wrapper">
      <RouterView />
    </div>
  </div>
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import Headers from '../components/Headers.vue'
export default {
  components: {
    Headers,
  },
} 
</script>
<style lang="scss">
.head{
  height: 80px;
}

</style>