<template>
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
</template>

<script>

import ResultTestQuestionCheckbox from '../components/ResultQuestion/ResultTestQuestionCheckbox.vue'
import ResultTestQuestionInputOne from '../components/ResultQuestion/ResultTestQuestionInputOne.vue'
import ResultTestQuestionOrdered from '../components/ResultQuestion/ResultTestQuestionOrdered.vue'
import ResultTestQuestionConformity from '../components/ResultQuestion/ResultTestQuestionConformity.vue'

import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    ResultTestQuestionCheckbox,
    ResultTestQuestionInputOne,
    ResultTestQuestionOrdered,
    ResultTestQuestionConformity,
    
  },
  data() {
    return {
       
    }
  },
  computed:{
    ...mapGetters([
      "getResultQuestions" 
    ]),
    
  },
  methods: {
    questionType(question){
      return question.type.title ? question.type.title : question.type
    }
  },
  mounted(){
    window.scroll(0, 0);
    console.log("монтирую результат для авторизованного")
  }
} 

</script>
<style lang="scss" scoped>

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
