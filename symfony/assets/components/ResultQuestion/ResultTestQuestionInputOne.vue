<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i class="result"
        :class="{resultTrue: qestion.result.score }"
      ><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      <hr>
      <div class="custom-control custom-radio">
        <label v-if="getUserAnswer"
          class="custom-control-label f_sm " 
          :class="classAnswer()"
        >{{ qestion.result.user_answer[0] }}
        </label>
        <label v-if="getUserAnswer && !qestion.result.score"
          class="custom-control-label f_sm answer-true" 
        > - {{ qestion.result.true_answer[0] }}
        </label>
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
     
    }
  },
  computed:{
    getUserAnswer(){
      return  this.qestion.result.user_answer.length > 0 && this.qestion.result.user_answer[0] !==''
    },
  },
  methods: {
    classAnswer(){
      return  {
        "answer-true": this.getUserAnswer && this.qestion.result.score,
        "answer-user": this.getUserAnswer && !this.qestion.result.score,
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
