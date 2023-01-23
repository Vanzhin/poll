<template>
  <div class="container">
   
    <h1> {{ testName.title }}</h1>
    тест № : {{ $route.params.id }}
    </div>
 
</template>
<script>

export default {
   data() {
      return {
         count: 0,
        //  testName:''
        //  testNumm: $route.params.id
      }
   },
   computed:{
      testName () {
        console.log(this.$route.params.id)
        return this.$store.getters.setTestTitle(this.$route.params.id)
      },
      qestions () {
        return this.$store.getters.setQuestions
      },
    },
    // mounted(){
    //   console.log(this.$route.params.id)
    //   this.testName = this.$store.getters.setTestTitle(this.$route.params.id)
    
      
    // }
  
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
