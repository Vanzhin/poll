<template>
  <Headers/>
  <div class="container">
    <div class="wrapper">
      <RouterView />
    </div>
    
  </div>
  

  
</template>
<script>
import { RouterLink, RouterView } from 'vue-router'
import Headers from './components/Headers.vue'
import QuestionNumbers from './components/QuestionNumbers.vue'
export default {
  data() {
    return {
      count: 0
    }
  },
  computed:{
    questions () {
      return this.$store.getters.setQuestions
    },
  },
  components: {
    Headers,
    QuestionNumbers,
    
  },
  
} 
</script>



<style lang="scss">


</style>
