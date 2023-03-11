<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i class="result"
        :class="{resultTrue: question.result.score }"
      ><b>{{ index+1 }})</b> {{ question.title }}</i>
      
      <hr>
     
        <div class="custom-control custom-radio"
          
          v-for="(answer, ind ) in question.variant" 
          :key="answer"
        >
          
          <label v-if="answer!==''"
            class="custom-control-label f_sm " 
            :class="classAnswer(ind)"
          >{{ answer }}
          </label>
          
        </div>
      
      <br>
      
    </div>       
  </div>
</template>
<script>
// v-model="answer"
export default {
  props: ['question', 'index' ],
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
        "answer-true": this.getUserAnswer && this.question.result.true_answer.indexOf(ind) > -1 && this.question.result.user_answer.indexOf(`${ind}`) > -1,
        "answer-user": this.getUserAnswer && this.question.result.user_answer.indexOf(`${ind}`) > -1,
        "answer": this.getUserAnswer && this.question.result.true_answer.indexOf(ind) > -1,
      }
    },
    getAnswer(ind){

    }
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
