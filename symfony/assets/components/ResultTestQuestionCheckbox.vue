<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i class="result"
        :class="{resultTrue: qestion.result.score }"
      ><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      
      <hr>
      
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in qestion.variant" 
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
  props: ['qestion', 'index' ],
  data() {
    return {
      count: 0,
      answerSelect:[]
    }
  },
  computed:{
    getUserAnswer(){
      return  this.qestion.result.user_answer.length > 0
    },
  },
  methods: {
    classAnswer(ind){
      return  {
        "answer-true": this.getUserAnswer && this.qestion.result.true_ansver.indexOf(ind) > -1 && this.qestion.result.user_answer.indexOf(ind) > -1,
        "answer-user":  this.getUserAnswer && this.qestion.result.user_answer.indexOf(ind) > -1,
        "answer":  this.getUserAnswer && this.qestion.result.true_ansver.indexOf(ind) > -1,
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
