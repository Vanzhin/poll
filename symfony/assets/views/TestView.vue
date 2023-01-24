<template>
  <div class="block">
    <div class="title">
      <h2> {{ testName.title }}</h2>
      <div class="test">
        <h4>Тест №: {{ $route.params.id }}</h4>  
      </div>
      
    </div>
    <div class="container">
      <div class="row">
        <form action="">
          <TestQuestion
            v-for="(qestion, index ) in qestions" 
            :key="qestion.id"
            :qestion="qestion"
            :index="index"
          />
        </form>
      </div>
  </div>
</div>
</template>
<script>
import TestQuestion from '../components/TestQuestion.vue'
export default {
  components: {
    TestQuestion,
  },
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
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
  }
  .title{
    margin: 10px;
  }
  .test{
    display: flex;
  }
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  
@media (min-width: 1024px) {
 
}
</style>
