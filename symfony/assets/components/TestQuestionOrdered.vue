<template>
  <div class="col-sm-12 col-md-12 col-lg-12"> 
    <div class="card flex-shrink-1 shadow">
      <i><b>{{ index+1 }})</b> {{ qestion.title }}</i>
      <input type="hidden" 
        :id="'a_' +  qestion.id"
        :name="qestion.id" 
        :value="answerSelect">
      <hr> 
      <i class="i">Расположите ответы в правильном порядке.</i>
      <div  
        @dragover.prevent="onDragover"
        >
        <div class="custom-control custom-radio"
          v-for="(answer, ind ) in qestionVariantSort" 
          :key="answer"
          @dragstart="onDragStart($event, ind)"
          draggable="true"
          :name="answer.sort"
        >
        
        
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
  props: ['qestion', 'index' ],
  data() {
    return {
      count: 0,
      answerSelect:[],
      qestionVariant:[],
      blockY:0
    }
  },
  computed:{
    qestionVariantSort(){
      return this.qestionVariant.sort((a,b) => a.sort-b.sort)
    }
  },
  methods: {
    getOrdered(id){
      const ind = this.answerSelect.indexOf(id)
      return ind>-1 ? ind + 1 : ''
    },
    onDragStart(e , item) {
      e.dataTransver.dropEffect = 'copy'
      e.dataTransver.effectAllowed = 'move'
      e.dataTransver.setData()
    },
    onDragover(e){
      if (this.blockY !== e.y){ this.blockY = e.y
      console.log(e)
      console.log(e.y, ' - ', e.toElement.offsetTop
)
      }

      
    }
  },
  mounted(){
    this.qestion.variant.forEach((item, index ) => 
      this.qestionVariant.push({id:index, title:item, sort: index})
    )
    // console.log(this.qestionVariant)
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
