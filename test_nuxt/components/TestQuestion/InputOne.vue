<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shad">
      <div class="title"
      >{{ index+1 }}. {{ question.title }}</div>
      <img :src="question.image" 
        v-if="question.image"
        class="title-img"
      />  
      <hr>
      <div class="custom-control custom-radio">
        <label 
          class="custom-control-label f_sm" 
          :for="'a_' +  question.id"
        >Введите ответ:
        </label>
        <hr>
        <input type="text" 
        :id="'a_' +  question.id"
        :name="question.id" 
        :value="answer">
      
      </div>
      <br>
      
    </div>       
  </div>
</template>
<script>
// v-model="answer"
export default {
  props: ['question', 'index' ],
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
      this.answer = event.target.value
    }
  } 
}

</script>

<style lang="scss" scoped>

.shad{
    margin-top: 30px;
    padding: 20px 43px 18px 43px;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    background-color: var(--color-white);
    @media (max-width: 480px){
      padding: 20px 10px 18px 10px;
    }
  }
  .title{
    margin-top: 0;
    font-weight: 700;
    font-size: 18px;
    line-height: 24px;
    color: var(--color-Black_blue);
    &-img{
      width: 306px;
      margin-top: 15px;
      @media (max-width: 480px){
        width: 180px;
        margin: 15px auto 0;
      }
    }
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    flex-wrap: wrap;
    @media (max-width: 480px){
      padding-left: 0;
    }
  }
  .custom-control-label {
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
  }
  @media (max-width: 576px) {
    
}
</style>
