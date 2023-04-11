<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <QuestionHeaderQuestion
        :question='question'
        :index='index'
      />
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.subtitles" 
        :key="answer"
      >
        <img :src="answer.image" width="200" 
          v-if="answer.image"
        />  
        <label v-if="answer!==''"
          class="custom-control-label f_sm " 
        >{{ answer.title }}
        </label>
        <div v-if="getUserAnswer(ind)"  class="custom-control-label d-flex">
          <label 
            v-if="question.result.user_answer[ind]!==''"
            class="custom-control-label f_sm answer " 
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
      <br>
      
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
          ? this.question.result.user_answer[ind]!=='' 
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
  .answer{
    color:rgb(196, 44, 17);
    &-true{
      color:rgb(17, 196, 47)
    }
  }
  .shadow{
    padding: 5px;
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    justify-content: space-between;
    min-height: 1.5rem;
    padding-left: 1.5rem;
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
      flex:1;
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
  }
  .result::before{
    content: "X  ";
    font-family: Geneva, Arial;
    color:rgb(235, 25, 25);
    font-weight: 900;
    font-style:  oblique ;
  }
  .resultTrue::before{
    content: "V  ";
    color:rgb(22, 204, 104);
    font-style:  normal ;
  }
</style>
