<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shad">
      <div class="title"
      >{{ index+1 }}. {{ question.title }}</div>
      <img :src="question.image" 
        v-if="question.image"
        class="title-img"
      />  
      <input type="hidden" 
        :id="'a_' +  question.id"
        :name="question.id" 
        :value="answerSelect">
      <hr>
      
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.variant" 
        :key="answer"
      >
        <input type="radio" 
          :name="'q' + (index + 1) + (ind + 1)"  
          :id="'q' + (index + 1) + (ind + 1) "
          :value= "ind "
          v-model="answerSelect"
          v-if="answer!==''"
          class="custom-control-input"
        >
        <label :for="'q' + (index + 1) + (ind + 1)"> 
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            
            <path fill-rule="evenodd" clip-rule="evenodd" d="M14 19C8.13401 19 5 15.866 5 12C5 8.13401 8.13401 5 12 5C15.866 5 19 8.13401 19 12C19 15.866 15.866 19 12 19Z" fill="#269EB7"/>
          </svg>

        </label>

        <div class="">
          <img :src="answer.image"  
            v-if="answer.image"
            class="img"
          /> 
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
            :for="'q' + (index + 1) + (ind + 1)"
          >{{ answer.title ? answer.title : answer}}
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
      answerSelect:null
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
  .img{
    height: 130px;
    margin: 3px 10px;
    max-width: 170px;
  }
  .shad{
    margin-top: 30px;
    padding: 20px 43px 18px 43px;
    box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
    border-radius: 6px;
    background-color: var(--color-white);

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
    }
  }
  .custom-control {
    position: relative;
    display: flex;
    align-items: center;
    min-height: 2.5rem;
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
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: var(--color-Black_blue);
  }
  .custom-control-input {
    position: absolute;
    z-index: -1;
    opacity: 0;
    &+label{
      display: inline-flex;
      align-items: center;
      user-select: none;
      width: 24px;
      height: 24px;
      outline: 1px solid var(--color-blue);
      border: 1px solid var(--color-blue);
      border-radius: 50%;
      background-repeat: no-repeat;
      background-position: center center;
      background-size: 50% 50%;
      & svg{
        opacity: 0;
      }
    }
    &:checked+label svg{
      opacity: 1;
      
    }
  }
</style>
