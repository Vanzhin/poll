<template>
  <div class="card flex-shrink-1 shad"
    :class="{resultTrue: question.result.correct }"
  >
    <QuestionHeaderQuestion
      :question='question'
      :index='index'

    />
    <div class="answer-cont">
      <hr>
      <div class="row text-center"
        v-if="getUserAnswer"
      >
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
          <div class=" flex-shrink-1 "
            :class="classAnswer()">
            <div class="custom-control custom-radio"
              v-for="(answer, ind ) in question.result.user_answer" 
              :key="answer"
            >
              <div class="answer-img-block">
                <img :src="question.variant[answer].image" 
                  v-if="question.variant[answer] && question.variant[answer].image"
                  class="answer-img"
                />  
              </div>
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
                :class="classAnswer(ind)"
              >{{ question.variant[+answer] ? question.variant[+answer].title: ''}}
              </label>
            </div>
          </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"
          v-if="!question.result.score"
        > 
          <div class=" flex-shrink-1  answer-true">
            <div class="custom-control custom-radio"
              v-for="(answer) in question.result.true_answer" 
              :key="answer"
            >
              <div class="answer-img-block">
                <img :src="question.variant[answer].image" 
                  v-if="question.variant[answer] && question.variant[answer].image"
                  class="answer-img"
                />  
              </div>
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
              >{{ question.variant[+answer] ? question.variant[+answer].title: '' }}
              </label>
            </div>
          </div>
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
      count: 0,
      answerSelect:[],
      questionVariant:[]
    }
  },
  computed:{
    getUserAnswer(){
      return  this.question.result.user_answer ? this.question.result.user_answer.length > 0 : false
    },
  },
  methods: {
    classAnswer(ind){
      return  {
        "answer-true":  +this.question.result.user_answer[ind] === this.question.result.true_answer[ind],
        "answer-user":  !this.question.result.score,
      }
    },
    getAnswer(ind){

    }
  },
  
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
  .answer-img{
    margin: 5px;
    width: 200px;
    @media (max-width: 350px){
      width: 110px;
    }
    &-block{
      @media (max-width: 350px){
        width: 110px;
        height: 140px;
        overflow: hidden;
      }
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
      }
    }
  }
  .shadow{
    padding: 5px;
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    min-height: 1.5rem;
    background: #F1F7FF;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    margin-top: 10px;
    @media (max-width: 350px){
      min-height:52px;
      display: flex;
      align-items: center;
      flex-direction: column;
      padding: 0 10px;
    }
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
    position: relative;
    margin-bottom: 0;
    margin-left: 10px;
    text-align: left;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    word-wrap:break-word;
    width: 100%;
    @media (max-width: 350px){
      margin: 0;
    }
    
  }
  .resultTrue{
    border: 1px solid #56D062;
  }
</style>
