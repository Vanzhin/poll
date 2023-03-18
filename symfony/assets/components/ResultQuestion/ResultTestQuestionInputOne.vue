<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <QuestionHeaderQuestion
        :question='question'
        :index='index'
      />
      <div class="custom-control custom-radio">
        <label v-if="getUserAnswer"
          class="custom-control-label f_sm " 
          :class="classAnswer()"
        >{{  question.result.user_answer[0] }}
        </label>
        <label v-if="getUserAnswer && !question.result.score"
          class="custom-control-label f_sm answer-true" 
        > - {{ question.variant.length > 0 ? question.variant[0].title ? question.variant[0].title:"":""}}
        </label>
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
     
    }
  },
  computed:{
    getUserAnswer(){
      
      return this.question.result.user_answer ?  this.question.result.user_answer.length > 0 && this.question.result.user_answer[0] !=='' : false
    },
  },
  methods: {
    classAnswer(){
      return  {
        "answer-true": this.getUserAnswer && this.question.result.score,
        "answer-user": this.getUserAnswer && !this.question.result.score,
      }
    },
    
  } 
}

</script>

<style lang="scss" scoped>
  .answer{
    color:rgb(91, 206, 235);
    &-user{
      color:rgb(196, 44, 17)
    }
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
    min-height: 1.5rem;
    padding-left: 1.5rem;
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
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
