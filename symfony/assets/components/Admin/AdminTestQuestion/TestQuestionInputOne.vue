<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      <img :src="question.image" width="200" 
        v-if="question.image"
      />  
      <div class="custom-control custom-radio">
        <label 
          class="custom-control-label f_sm" 
          :for="'a_' +  question.id"
        >Правильный ответ:
        </label>
        <hr>
        <input type="text" class="input"
          :value="question.variant[0].title"
        >
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
    }
  },
  computed:{},
  methods: {} 
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
  .input{
    padding-left: 10px;
    line-height:20px;
  }
  @media (max-width: 576px) {
}
</style>
