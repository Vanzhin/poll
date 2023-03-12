<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      
      <i class="result"
        :class="{resultTrue: question.result.score }"
      ><b>{{ index+1 }})</b> {{ question.title }}</i>
      
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
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
                :class="classAnswer(ind)"
              >{{ question.variant[answer] }}
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
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
                
              >{{ question.variant[answer] }}
              </label>
              
            </div>
          </div>
        </div>
      </div>


      
      <br>
      
    </div>       
  </div>
</template>
<script>
// v-model="answer" question.variant
export default {
  props: ['question', 'index' ],
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
        "answer-true":  +this.question.result.user_answer[ind] === this.question.result.true_answer[ind]
,
        "answer-user":  !this.question.result.score,
      }
    },
    getAnswer(ind){

    }
  },
  
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
    background-color: rgb(245 245 242);
    border: 1px solid rgb(167, 167, 163);
    border-radius: 10px;
    margin-top: 2px;
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
      
      text-align: left;
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
