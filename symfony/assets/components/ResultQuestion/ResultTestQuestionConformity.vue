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
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.subtitles" 
        :key="answer"
      >
        <img :src="answer.image"  
          v-if="answer.image"
          class="answer-img"
        />  
        <label v-if="answer!==''"
          class="custom-control-label f_sm " 
        >{{ answer.title }}
        </label>
        <div v-if="getUserAnswer(ind)"  class="custom-control-label d-flex">
          <label 
            v-if="question.result.user_answer[ind]!==''"
            class="custom-control-label f_sm answer-user " 
            :class="classAnswer(ind)"
          >{{ question.variant[question.result.user_answer[ind]].title }}
          </label>
        
          <label 
            v-if="+question.result.user_answer[ind] !== +question.result.true_answer[ind]"
            class="custom-control-label f_sm " 
          >{{ question.variant[question.result.true_answer[ind]].title }}
          </label>
          <label 
            v-else
            class="custom-control-label f_sm " 
          >
          </label>
        </div>
      </div>
      
    </div>
  </div>       
  
</template>
<script>
import  QuestionHeaderQuestion from './QuestionHeaderQuestion.vue';
export default {
  props: ['question', 'index' ],
  components: {
    QuestionHeaderQuestion
  },
  data() {
    return {
      answerSelect:[]
    }
  },
  computed:{
    
  },
  methods: {
    getUserAnswer(ind){
      return   this.question.result.user_answer 
        ? this.question.result.user_answer.length > 0 && this.question.result.user_answer.length > ind
          ? this.question.result.user_answer[ind]!== -1 && this.question.result.user_answer[ind]!== ''
          : false
        : false
    },
    classAnswer(ind){
      return  {
        "answer-true": this.getUserAnswer && (+this.question.result.true_answer[ind] === +this.question.result.user_answer[ind]) ,
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
      @media (max-width: 350px){
        padding: 0;
      }
    }
    &-img{
      width: 200px;
      @media (max-width: 350px){
       width: 180px;
       margin: 10px auto;
      }
    }
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    justify-content: space-between;
    min-height: 1.5rem;
    @media (max-width: 350px){
      flex-direction: column;
      margin-top: 10px;
    }

  }
  .f_sm {
      font-size: 0.9rem;
      @media (max-width: 350px){
        width: 100%;
      }
  }
  .custom-control-label {
      flex:1;
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
      @media (max-width: 350px){
        margin-left: 0px;
        flex-wrap: wrap;
        
      }
  }
  .resultTrue{
    border: 1px solid #56D062;
  }
</style>
