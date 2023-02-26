<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <QuestionHeaderQuestion/>
      <input type="hidden" 
        id="answer_true"
        :value="answers"
      >
      <hr>
      <i class="i">Введите правильный ответ.</i>
      <div class="custom-control ">
        <div class="custom-radio img_block">
          <input  
            name="variant[0][title]"
            v-model="answers"
            class="custom-control-input" required  
          >
          <i class="bi bi-eraser custom-close" title="Очистить поле"
            @click="answers = ''"
            v-if="answers !== ''"
          ></i>
        </div>
      </div>
      <br>
      
    </div>       
  </div>
</template>
<script>
import  QuestionHeaderQuestion from './QuestionHeaderQuestion.vue';
export default {
  props: ['qestion', 'index' ],
  components: {
    QuestionHeaderQuestion
  },
  data() {
    return {
      answers: '',
    }
  },
  computed:{
  },
  methods: {
    
  } 
}
</script>

<style lang="scss" scoped>
  .block_number{
    display: flex;
  }
  .shadow{
    padding: 5px;
  }
  .custom{
    &-control {
      position: relative;
      display: flex;
      align-items:center;
      min-height: 1.5rem;
      padding-left: 1.5rem;
      flex-wrap:wrap;
      &-label {
        position: relative;
        margin-bottom: 0;
        margin-left: 10px;
      }
     
    };
    &-radio{
      display: flex;
      align-items:center;
      width: 100%;
    }
    &-close{
      cursor: pointer;
    }
}
  .f_sm {
      font-size: 0.9rem;
  }
  
  #number{
    width: 40px;
    margin-left: 20px;
    margin-right: 20px;
  }
  .label{
    display:block;
    margin: 0 10px;
  }
  .textarea_input{
    max-width: 50%;
    padding: 0;
    padding-left: 10px;
    margin: 5px;
  }
  .img_block{
    display: flex;
    align-items: flex-start;
    & input {
      margin-top: 10px;
      padding-left: 5px;
    }
    & i {
      margin: 10px;
      transition: all 0.5s ease-out;
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
      }
    }
  }
</style>
