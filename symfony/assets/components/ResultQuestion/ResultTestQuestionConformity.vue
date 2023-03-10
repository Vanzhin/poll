<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i class="result"
        :class="{resultTrue: qestion.result.score }"
      ><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      <hr>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in qestion.subTitle" 
        :key="answer"
      >
        <label v-if="answer!==''"
          class="custom-control-label f_sm " 
        >{{ answer }}
        </label>
        <div v-if="getUserAnswer"  class="custom-control-label d-flex">
          <label 
            v-if="qestion.result.user_answer[ind] && qestion.result.user_answer[ind]!==''"
            class="custom-control-label f_sm answer " 
            :class="classAnswer(ind)"
          >{{ qestion.variant[qestion.result.user_answer[ind]] }}
          </label>
        
          <label 
            v-if="
                +qestion.result.user_answer[ind] !== qestion.result.true_answer[ind]"
            class="custom-control-label f_sm " 
          >{{ qestion.variant[qestion.result.true_answer[ind]] }}
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

export default {
  props: ['qestion', 'index' ],
  data() {
    return {
      answerSelect:[]
    }
  },
  computed:{
    getUserAnswer(){
      return   this.qestion.result.user_answer ? this.qestion.result.user_answer.length > 0 
        ? this.qestion.result.user_answer[0]!=='' : false
        : false
    },
  },
  methods: {
    classAnswer(ind){
      return  {
        "answer-true": this.getUserAnswer && (this.qestion.result.true_answer[ind] === +this.qestion.result.user_answer[ind]) ,
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
