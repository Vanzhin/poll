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

  .shadow{
    padding: 5px;
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
