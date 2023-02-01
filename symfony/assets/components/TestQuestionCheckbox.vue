<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      <input type="hidden" 
        :id="'a_' +  qestion.id"
        :name="qestion.id" 
        :value="answerSelect">
      <hr>
      <i class="i">Выберите правильные ответы.</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in qestion.variant" 
        :key="answer"
      >
        <input type="checkbox" 
          :name="'q' + (index + 1) + (ind + 1)"  
          :id="'q' + (index + 1) + (ind + 1) "
          :value= "ind "
          v-model="answerSelect"
          v-if="answer!==''"
          class="custom-control-input"  >
        <label 
          v-if="answer!==''"
          class="custom-control-label f_sm" 
          :for="'q' + (index + 1) + (ind + 1)"
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
