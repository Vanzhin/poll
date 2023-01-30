<template>
  <div class="container">
    <QuestionNumbers/>
    
  </div>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router'

import QuestionNumbers from '../components/QuestionNumbers.vue'
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

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
