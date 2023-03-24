<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      <img :src="question.image" width="200" 
        v-if="question.image"
      />  
      <hr>
      <i class="i">Варианты ответов:</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.variant" 
        :key="answer.id"
      >
        <input type="radio" 
          :value= "answer.id"
          v-if="answer!==''"
          v-model="answerSelect"
          disabled
          class="custom-control-input"
        >
        <div class="">
          <img :src="answer.image"  
            v-if="answer.image"
            class="img"
          /> 
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
          >{{ answer.title }}
          </label> 
        </div>
      </div>
      <br>
    </div>       
  </div>
</template>
<script>

export default {
  props: ['question', 'index' ],
  data() {
    return {
      count: 0,
      answerSelect: this.question.answer[0] 
    }
  },
  computed:{
    
  },
  methods: {
    setChangeAnswer(event){
      console.log(event.target.value)
      this.answer = event.target.value
    }
  } 
}

</script>

<style lang="scss" scoped>
  .img{
    height: 130px;
    margin-left: 10px;
    max-width: 170px;
  }
  .radio-block{
    height: 100%;
    display: flex;
    align-items: center;
  }
  .shadow{
    padding: 5px;
  }
  .custom{
    &-control {
      position: relative;
      display: flex;
      align-items:flex-start;
      min-height: 1.5rem;
      padding-left: 1.5rem;
      margin-top: 5px;

      align-items: center;
    }
  }
  .f_sm {
    font-size: 0.9rem;
  }
  .custom-control-label {
    position: relative;
    margin-bottom: 0;
    margin-left: 10px;
    word-wrap: break-word;
  }
</style>
