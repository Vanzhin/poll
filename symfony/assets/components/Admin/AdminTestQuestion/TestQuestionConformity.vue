<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ question.title }}</i>
      <img :src="question.image" width="200" 
        v-if="question.image"
      />  
      <hr>
      <i class="i">Варианты ответов:</i>
      <div class="custom-control custom-radio"
        v-for="(answer, ind ) in question.subTitle" 
        :key="answer"
      >
        <div>
          <img :src="variantConformity(ind).image"  width="200" height="135" 
            v-if="variantConformity(ind).image !== ''"
          /> 
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
          >{{ answer }}
          </label>
        </div>
          <select>
            disabled
            <option>
              {{ variantConformity(ind).title }}
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
      return this.question.variant.find(elem => elem.id === this.question.answer[ind])
    }
  },
  mounted(){
    if (this.question.answer){
      this.additionalVariants = this.question.variant
      this.question.answer.forEach((item, index ) =>{ 
        let arr = [...this.additionalVariants]
        this.additionalVariants = arr.filter(elem => elem.id !== item)
      }
     
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
    align-items:center;
    justify-content: space-between;
    flex-wrap: wrap;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    margin-top: 3px;
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
