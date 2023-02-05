<template>
  <div class="block">
    <div class="title">
      <h2> {{ testName.title }}</h2>
      <div class="test">
        <p>Билет №: {{  }}</p>  
      </div>
    </div>
    <div class="container">
      <div class="row">
       
          <div v-for="(qestion, index ) in resultQuestions" 
            :key="qestion.id"
          >
            <ResultTestQuestionCheckbox
              :qestion="qestion"
              :index="index"
              v-if="qestion.type.title==='radio' || qestion.type.title==='checkbox'"
            />
            <ResultTestQuestionOrdered
              :qestion="qestion"
              :index="index"
              v-else-if="qestion.type.title==='order'"
            />
            <ResultTestQuestionInputOne
              :qestion="qestion"
              :index="index"
              v-else-if="qestion.type.title==='input_one'"
            />
            <ResultTestQuestionConformity
              :qestion="qestion"
              :index="index"
              v-else-if="qestion.type.title==='conformity'"
            />
          </div>
          
       
      </div>
  </div>
</div>
</template>

<script>

import ResultTestQuestionCheckbox from '../components/ResultTestQuestionCheckbox.vue'
import ResultTestQuestionInputOne from '../components/ResultTestQuestionInputOne.vue'
import ResultTestQuestionOrdered from '../components/ResultTestQuestionOrdered.vue'
import ResultTestQuestionConformity from '../components/ResultTestQuestionConformity.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultTestQuestionCheckbox,
    ResultTestQuestionInputOne,
    ResultTestQuestionOrdered,
    ResultTestQuestionConformity
  },
  data() {
    return {
       
    }
  },
  computed:{
    testName () {
      return this.$store.getters.getTestTitleActive
    },
    resultQuestions () {
      return this.$store.getters.getResultQuestions
    },
  },
  methods: {
  
  },
  mounted(){
    
  }
  
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
    
  }
  .title{
    margin: 10px;
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
    }
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
  .button{
    border: 1px solid rgb(171 171 171);
    padding: 5px 10px;
    border-radius: 5px;
    &:hover{
      background-color: rgb(225 225 221);
    }
  }
@media (min-width: 1024px) {
 
}
</style>
