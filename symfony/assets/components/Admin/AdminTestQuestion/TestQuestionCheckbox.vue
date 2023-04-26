<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="">
      <div>
        <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      </div>
      <img :src="question.image" width="306" 
        v-if="question.image"
      />  
      <hr>
      <i class="i">Варианты ответов:</i> 
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.variant" 
        :key="answer"
      >
        <input type="checkbox" 
          :value= "answer.id"
          v-model="answerSelect"
          v-if="answer!==''"
          disabled
          class="custom-control-input"  >
        <div class="">
          <img :src="answer.image" 
            v-if="answer.image"
            class="img"
          />  
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm " 
          
          >{{ answer.title }}
          </label>
        </div>
      </div>
      <br>
      
    </div>       
  </div>
</template>
<script>
// v-model="answer"
export default {
  props: ['question', 'index', 'admin' ],
  data() {
    return {
      count: 0,
      answerSelect:this.question.answer
    }
  },
  computed:{
    
  },
  methods: {
    setChangeAnswer(event){
      console.log(event.target.value)
      this.answer = event.target.value
    }
  }, 
  created(){
    if (this.admin) {
      this.answerSelect = []
    }
  }
}

</script>

<style lang="scss" scoped>
  .img{
    margin-left: 10px;
    max-width: 170px;
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
    margin-top: 5px;
    align-items: center;
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
