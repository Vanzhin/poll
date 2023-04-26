<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="">
      <div>
        <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      </div>
      <img :src="question.image"  width="306"
        v-if="question.image"
      />  
      <hr> 
      <i class="i">Ответы в правильном порядке.</i>
      <div>
        <div class="custom-control custom-radio"
          v-for="(answer, ind ) in questionVariant" 
          :key="answer"
        >
        <div class="block_drop" ></div>
          <img :src="answer.image" 
            v-if="answer.image"
            class="img"
          />  
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
          >
            {{ answer.title }}
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
      answerSelect:[],
      questionVariant:[],
      blockY:0
    }
  },
  computed:{
   
  },
  methods: {
  },
  mounted(){
    if (this.question.answer){
    this.question.answer.forEach((item, index ) => 
      this.questionVariant.push(
        this.question.variant.find(elem => elem.id === item)
      )
    )} 
    
  }
}

</script>

<style lang="scss" scoped>
  .img{
    
    margin: 5px;
    max-width: 170px;
  }
  .shadow{
    padding: 5px;
  }
 
  .custom-control {
    position: relative;
    display: flex;
    align-items:flex-start;
    justify-content:flex-start;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    background-color: rgb(245 245 242);
    border: 1px solid rgb(167, 167, 163);
    border-radius: 10px;
    margin-top: 2px;
    &-input{
      margin-left: 5px;
    }
    &-number{
      width: 30px;
      height: 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }
  }
  .block_drop{
    position: absolute;
    z-index: 10;
    height: 100%;
    width: 100%;
    left: 0;
  }
  .f_sm {
      font-size: 0.9rem;
  }
  .custom-control-label {
      max-width: 90%;
      position: relative;
      margin-bottom: 0;
      margin-left: 10px;
  }
</style>
