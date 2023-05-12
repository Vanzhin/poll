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
      <i class="i">Расположите ответы в правильном порядке.</i>
      <div  
        @drop="onDrop($event)"
        @dragover.prevent
        @dragenter.prevent
        v-if="this.question.variant"
      >
        <div class="custom-control custom-radio"
          v-for="(answer, ind ) in questionVariantSort" 
          :key="answer"
          @dragstart="onDragStart($event, ind)"
          draggable="true"
          :dataname="answer.sort"
        >
        <div class="block_drop"  :dataname="answer.sort"></div>
          <img :src="answer.image" 
            v-if="answer.image"
            class="img"
          />  
          <label 
            v-if="answer!==''"
            class="custom-control-label f_sm" 
            :for="'q' + (index + 1) + (ind + 1)"
          >{{ answer.title }}
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
    questionVariantSort(){
      this.answerSelect = []
      this.questionVariant.sort((a,b) => a.sort-b.sort)
      this.questionVariant.forEach((item, index ) => {
        item.sort = index
        this.answerSelect.push(item.id)
      })
      return this.questionVariant
    }
  },
  methods: {
    getOrdered(id){
      const ind = this.answerSelect.indexOf(id)
      return ind>-1 ? ind + 1 : ''
    },

    onDragStart(e , item) {
      e.dataTransfer.dropEffect = 'copy'
      e.dataTransfer.effectAllowed = 'move'
      e.dataTransfer.setData('item', item.toString())
    },
    
    onDrop(e){
      const item = parseInt(e.dataTransfer.getData('item'))
      const yEl = e.toElement.offsetParent.offsetTop + e.toElement.offsetParent.offsetParent.offsetTop+ e.toElement.clientHeight/2
      if (e.pageY > yEl  ) {
        this.questionVariant[item].sort = parseInt(e.toElement.attributes.dataname.value) + 0.5
      } else this.questionVariant[item].sort = parseInt(e.toElement.attributes.dataname.value) - 0.5
      
    }
  },
  mounted(){
    if (this.question.variant){
    this.question.variant.forEach((item, index ) => 
      this.questionVariant.push({
        id:index, 
        title:item.title ? item.title : item, 
        image: item.image ? item.image : '',
        sort: index})
    )} 
    // console.log(this.questionVariant)
  }
}

</script>

<style lang="scss" scoped>
  .img{
    
    margin: 5px;
    max-width: 170px;
    @media (max-width: 480px){
      max-width: 110px;
      
      
     
    }
  }
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
    justify-content:flex-start;
    min-height: 1.5rem;
    padding-left: 1.5rem;
    background-color: rgb(245 245 242);
    border: 1px solid rgb(167, 167, 163);
    border-radius: 10px;
    margin-top: 2px;
    @media (max-width: 480px){
      padding-left: 0;
    }
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
      word-wrap: break-word;
      @media (max-width: 480px){
        max-width: 55%;
        margin-left: 0px;
      }
      @media (max-width: 480px){
        max-width: 43%;
      }
  }
</style>
