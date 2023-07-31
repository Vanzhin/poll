<template>
  <div class="flex-shrink-1 shad"
    :class="{resultTrue: question.result.correct }"
  >
    <QuestionHeaderQuestion
      :question='question'
      :index='index'
    />
    <div class="answer-cont">
      <hr>
      <div class="custom-control custom-radio">
        <label v-if="getUserAnswer"
          class="custom-control-label f_sm " 
          :class="classAnswer()"
        >{{  question.result.user_answer[0] }}
        </label>
        <label v-if="getUserAnswer && !question.result.correct"
          class="custom-control-label f_sm answer-true" 
        > - {{ question.variant.length > 0 ? question.variant[0].title ? question.variant[0].title:"":""}}
        </label>
      </div>
    </div>
   </div>       
</template>
<script>
import  QuestionHeaderQuestion from './HeaderQuestion.vue';
export default {
  props: ['question', 'index' ],
  components: {
    QuestionHeaderQuestion
  },
  data() {
    return {
     
    }
  },
  computed:{
    getUserAnswer(){
      return this.question.result.user_answer 
        ?  this.question.result.user_answer.length > 0 && this.question.result.user_answer[0] !=='' 
        : false
    },
  },
  methods: {
    classAnswer(){
      return  {
        "answer-true": this.getUserAnswer && this.question.result.correct,
        "answer-user": this.getUserAnswer && !this.question.result.correct,
      }
    },
  } 
}

</script>

<style lang="scss" scoped>
   .shad{
    margin-top: 14px;
    padding: 30px 14px 30px 14px;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    background-color: var(--color-white);
    border: 1px solid var(--color-red);
    @media (max-width: 480px){
      padding: 26px 12px 26px 12px;
    }
  }


  .answer{
    color:var(--color-blue);
    &-user{
      color:var(--color-red)
    }
    &-true{
      color:var(--color-green)
    }
    &-cont{
      padding: 0 29px;
      @media (max-width: 480px){
        padding: 0 ;
      }
    }
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    min-height: 1.5rem;
    
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
    position: relative;
    margin-bottom: 0;
    margin-left: 10px;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    @media (max-width: 480px){
      margin-left: 0;
    }
  }
  .resultTrue{
    border: 1px solid #56D062;
  }
</style>