<template>
  
  <div class="sub-container" >
    <div class="sub-col ">
      <div class="sub-item-block_number">
        <label class="label"> Добавить дополнительные варианты в раскрывающийся список:</label>
        <input type="number"
          v-model="numberSubTitles"
          @change="changeNumberSubTitles"
        >
      </div>
    </div>
    <div class="sub-col">
      <div
        v-for="(subTitle, ind ) in subTitles" 
        :key="subTitle"
        v-if="subTitles.length>0"
        class="sub-item"
      >
        <input required
          :name="`variant[${subTitle.id}][title]`"
          v-model= "subTitle.title"
          class="sub-item-input" 
        >
        <div class="sub-block">
          <i class="bi bi-eraser sub-close" title="Очиститеть поле соответствия."
            @click="subTitle.title = ''"
            v-if="subTitle.title !== ''"
          ></i>
        </div> 
        <i class="bi bi-x-lg sub-close" title="Удалить"
          @click="subTitleDelete(ind)"
        ></i>
      </div>
    </div>
  </div>
</template>

<script>

import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['richtVariant',  ],
  data() {
    return {
      subTitles:[],
      numberSubTitles: 0,
      uniqueNumber: 100,
      operation: this.$route.params.operation,
      operationEdit: this.$route.params.operation === "edit"
    }
  },
  computed:{
    ...mapGetters([
      "getTest",
      "getQuestion"
    ]),
  },
  methods: {
    changeNumberSubTitles(){
      if (this.numberSubTitles < 0) {
        this.numberSubTitles = 0
        this.subTitles = []
        return
      }
      if ( this.subTitles.length < this.numberSubTitles) {
        ++this.uniqueNumber
        this.subTitles.push({
          id: 'a' + this.uniqueNumber,
          title:""
        })
      } else {
        this.numberSubTitles
        this.subTitles.pop()
      }
    },
    subTitleDelete(ind){
      if (this.subTitles.length > 0){
        this.subTitles.splice(ind, 1);
        this.numberSubTitles = this.subTitles.length
      }
    },
  },
  created(){
    
    if (this.operationEdit) {
      const sub = this.getQuestion.variant.slice(this.getQuestion.subTitle.length, this.getQuestion.variant.length)
      this.subTitles = sub.map((item, index) => 
        {
          return {
            id: item.id,
            title: item.title ? item.title: '',
          }
        })
      this.numberSubTitles = this.subTitles.length
      console.log("this.subTitles -",this.subTitles )
    }
  } 

}

</script>

<style lang="scss" scoped>
   .sub{
    &-container {
      position: relative;
      display: flex;
      align-items:flex-start;
      min-height: 1.5rem;
      padding-left: 1.5rem;
      padding-right: 10px;
      flex-wrap:wrap;
      margin: 2px;
    };
    &-col{
      margin-top: 10px;
      flex: 1;
      align-items:flex-start;
      min-width: 310px;
    }
    &-block{
      width: 26px;
      height: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    &-close{
      cursor: pointer;
      transition: all 0.5s ease-out;
      margin-left: 5px;
      transform: scale(1);
      &:hover{
        color: rgb(185, 48, 14);
        transform: scale(1.25);
      }
      
    }

    &-item{
      display: flex;
      align-items: center;
      width: 100%;
      &-input{
        width: 100%;
        margin: 3px;
      }
      &-block_number{
        input{
          width: 40px;
        }
      }
    }
  }
 
  
 
  
</style>