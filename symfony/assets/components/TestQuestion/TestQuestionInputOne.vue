<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      
      <div class="custom-control custom-radio"
        
      >
        
        <label 
          class="custom-control-label f_sm" 
          :for="'a_' +  qestion.id"
        >Введите ответ:
        </label>
        <hr>
        <input type="text" 
        :id="'a_' +  qestion.id"
        :name="qestion.id" 
        :value="answer">
      
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
      answer:''
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
    flex-wrap: wrap;
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
  }
  @media (max-width: 576px) {
    
}
</style>
