<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      <input type="hidden" 
        :id="'a_' +  qestion.id"
        :name="qestion.id" 
        :value="answerSelect">
      <hr>
      <i class="i">Варианты ответов:</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in qestion.variant" 
        :key="answer.id"
      >
        <input type="radio" 
          :value= "answer.id"
          v-if="answer!==''"
          v-model="answerSelect"
          disabled
          class="custom-control-input"  >
        <label 
          v-if="answer!==''"
          class="custom-control-label f_sm" 
        >{{ answer.title }}
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
      count: 0,
      answerSelect: this.qestion.answer[0]
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
</style>
