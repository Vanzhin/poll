<template>
  <div v-for="(question, index ) in result.results" 
    :key="questionItem(question).id"
  >
    <ResultTestQuestionCheckbox
      :question="questionItem(question)"
      :index="index"
      v-if="questionType(questionItem(question))==='radio' || questionType(questionItem(question))==='checkbox'"
    />
    <ResultTestQuestionOrdered
      :question="questionItem(question)"
      :index="index"
      v-else-if="questionType(questionItem(question))==='order'"
    />
    <ResultTestQuestionInputOne
      :question="questionItem(question)"
      :index="index"
      v-else-if="questionType(questionItem(question))==='input_one'"
    />
    <ResultTestQuestionConformity
      :question="questionItem(question)"
      :index="index"
      v-else-if="questionType(questionItem(question))==='conformity'"
    />
  </div> 
</template>

<script setup>

import { useResultStore  } from '../../stores/ResultStore'
const result = useResultStore()

function questionType(question){
  return question.type.title ? question.type.title : question.type
}

function questionItem(question){
  return question.question ? question.question : question
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
 