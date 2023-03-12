<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      <hr>
      <i class="i">Варианты ответов:</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.subTitle" 
        :key="answer"
      >
        <label 
          v-if="answer!==''"
          class="custom-control-label f_sm" 
          
        >{{ answer }}
        </label>
        <select>
          disabled
          <option>
            {{ variantConformity(ind) }}
          </option>
        </select>
      </div>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in additionalVariants" 
        :key="answer"
      >
        <label 
          v-if="answer!==''"
          class="custom-control-label f_sm" 
          
        >
        </label>
        <select>
          disabled
          <option>
            {{ answer.title }}
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
      additionalVariants:[] 
    }
  },
  computed:{
    
  },
  methods: {
    variantConformity(ind){
      return this.question.variant.find(elem => elem.id === this.question.answer[ind]).title
    }
  },
  mounted(){
    if (this.question.answer){
      this.additionalVariants = this.question.variant
      this.question.answer.forEach((item, index ) =>{ 
      let arr = [...this.additionalVariants]
      
      this.additionalVariants = arr.filter(elem => elem.id !== item)}
     
    )} 
    
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
