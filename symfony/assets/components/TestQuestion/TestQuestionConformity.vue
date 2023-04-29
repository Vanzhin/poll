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
      <i class="i">Выберите ответы из выпадающего списка.</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.subtitles" 
        :key="answer"
      >
        <img :src="answer.image" width="200" 
          v-if="answer.image"
        />  
        <label 
          v-if="answer!==''"
          class="custom-control-label f_sm" 
          :for="'q' + (index + 1) + (ind + 1)"
        >{{ answer.title }}
        </label>
        <select 
          v-model="answerSelect[ind]"
          >
          <option disabled value="">Выберите один из вариантов</option>
          <option 
            v-for="(variant, vInd ) in question.variant"
            :value="vInd" 
          >
            {{ variant.title }}
          </option>
        </select>
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
       answerSelect:[] 
    }
  },
  computed:{
    
  },
  methods: {
   
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
    align-items:flex-start;
    justify-content: space-between;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
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
