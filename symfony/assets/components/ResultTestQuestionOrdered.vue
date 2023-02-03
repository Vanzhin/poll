<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      
      <i class="result"
        :class="{resultTrue: qestion.result.score }"
      ><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      
      <hr>
      <div class="row text-center"
        v-if="getUserAnswer"
      >
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
          <div class=" flex-shrink-1 "
            :class="classAnswer()">
            <div class="custom-control custom-radio"
              v-for="(answer, ind ) in qestion.result.user_answer" 
              :key="answer"
              
            >
              
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
                
              >{{ qestion.variant[answer] }}
              </label>
              
            </div>
          </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"
          v-if="!qestion.result.score"
        > 
          <div class=" flex-shrink-1  answer-true">
            <div class="custom-control custom-radio"
              v-for="(answer, ind ) in qestion.result.true_ansver" 
              :key="answer"
              
            >
              
              <label v-if="answer!==''"
                class="custom-control-label f_sm " 
                
              >{{ qestion.variant[answer] }}
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
// v-model="answer" qestion.variant
export default {
  props: ['qestion', 'index' ],
  data() {
    return {
      count: 0,
      answerSelect:[],
      qestionVariant:[]
    }
  },
  computed:{
    getUserAnswer(){
      return  this.qestion.result.user_answer.length > 0
    },
  },
  methods: {
    classAnswer(){
      return  {
        "answer-true":  this.qestion.result.score,
        "answer-user":  !this.qestion.result.score,
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
