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
          v-for="(answer, ind ) in question.variant" 
          :key="answer"
        >
          <div class="answer-cont-img"> 
            <img :src="answer.image" 
              v-if="answer.image"
              class="img"
            />   
          </div>
          <label v-if="answer!==''"
            class="custom-control-label f_sm " 
            :class="classAnswer(ind)"
          >{{ answer.title ? answer.title : answer }}
          </label>
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
      count: 0,
      answerSelect:[]
    }
  },
  computed:{
    getUserAnswer(){
      return  this.question.result.user_answer ? 
        this.question.result.user_answer.length > 0 && this.question.result.user_answer[0] !==''
        : false
    },
  },
  methods: {
    classAnswer(ind){
      return  {
        "answer-true": this.getUserAnswer && this.question.result.true_answer.indexOf(ind) > -1 && (this.question.result.user_answer.indexOf(`${ind}`) > -1 || this.question.result.user_answer.indexOf(ind) > -1),
        "answer-user": this.getUserAnswer && (this.question.result.user_answer.indexOf(`${ind}`) > -1 || this.question.result.user_answer.indexOf(ind) > -1),
        "answer": this.getUserAnswer && this.question.result.true_answer.indexOf(ind) > -1,
      }
    },
    getAnswer(ind){

    }
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
    @media (max-width: 350px){
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
      @media (max-width: 350px){
        padding: 0 ;
        &-img{
          width: 100%;
          display: flex;
          justify-content: center;
        }
      }

    }
  }
  .img{
    height: 130px;
    margin: 3px 10px 3px 0;
    max-width: 170px;
    @media (max-width: 350px){
      margin: 3px auto;
      width: 180px;
    }
  }
  
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    min-height: 1.5rem;
    flex-wrap: wrap;
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
    @media (max-width: 350px){
      margin-left: 0px;
      word-wrap: break-word;
    }
  }
  .resultTrue{
    border: 1px solid #56D062;
  }
  
</style>
