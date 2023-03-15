<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      <img :src="question.image" width="200" 
        v-if="question.image"
      />  
      <input type="hidden" 
        :id="'a_' +  question.id"
        :name="question.id" 
        :value="answerSelect">
      <hr>
      <i class="i">Выберите правильные ответы.</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.variant" 
        :key="answer"
      >
        <input type="checkbox" 
          :name="'q' + (index + 1) + (ind + 1)"  
          :id="'q' + (index + 1) + (ind + 1) "
          :value= "ind "
          v-model="answerSelect"
          v-if="answer!==''"
          class="custom-control-input">
        <div class=""> 
          <img :src="answer.image" 
            v-if="answer.image"
            class="img"
          />   
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
            :for="'q' + (index + 1) + (ind + 1)"
          >{{ answer.title ? answer.title : answer }}
          </label>
        </div>
      </div>
      <br>
    </div>       
  </div>
</template>
<script>

export default {
  props: ['question', 'index', 'admin' ],
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
    height: 130px;
    margin: 3px 10px;
    max-width: 170px;
  }
  .shadow{
    padding: 5px;
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items: center;
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
      word-wrap: break-word;
  }
</style>
