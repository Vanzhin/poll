<template>
  <Loader
    v-if="getIsLoaderQuestions"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h1>Результат:</h1>  
      <h2> {{ getTestTitleActive.title }}</h2>
      <div class="test">
        <p>Билет №: {{  }}</p>  
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div v-for="(question, index ) in getResultQuestions" 
          :key="question.id"
        >
          <ResultTestQuestionCheckbox
            :question="question"
            :index="index"
            v-if="questionType(question)==='radio' || questionType(question)==='checkbox'"
          />
          <ResultTestQuestionOrdered
            :question="question"
            :index="index"
            v-else-if="questionType(question)==='order'"
          />
          <ResultTestQuestionInputOne
            :question="question"
            :index="index"
            v-else-if="questionType(question)==='input_one'"
          />
          <ResultTestQuestionConformity
            :question="question"
            :index="index"
            v-else-if="questionType(question)==='conformity'"
          />
        </div>
      </div>
  </div>
</div>
</template>

<script>

import ResultTestQuestionCheckbox from '../components/ResultQuestion/ResultTestQuestionCheckbox.vue'
import ResultTestQuestionInputOne from '../components/ResultQuestion/ResultTestQuestionInputOne.vue'
import ResultTestQuestionOrdered from '../components/ResultQuestion/ResultTestQuestionOrdered.vue'
import ResultTestQuestionConformity from '../components/ResultQuestion/ResultTestQuestionConformity.vue'
import Loader from '../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultTestQuestionCheckbox,
    ResultTestQuestionInputOne,
    ResultTestQuestionOrdered,
    ResultTestQuestionConformity,
    Loader
  },
  data() {
    return {
       
    }
  },
  computed:{
    ...mapGetters(["getIsLoaderQuestions", "getTestTitleActive","getResultQuestions" ]),
    
  },
  methods: {
    questionType(question){
      return question.type.title? question.type.title : question.type
    }
  },
  mounted(){
    window.scroll(0, 0);
    console.log("монтирую результат для авторизованного")

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
