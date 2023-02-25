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
          :name="`question[subTitle][${richtVariant + ind}]`"
          v-model= "subTitle.title"
          class="sub-item-input" 
        >
        <i class="bi bi-x-lg sub-close" title="Удалить"
          @click="subTitleDelete(ind)"
        ></i>
      </div>
    </div>
  </div>
</template>

<script>


export default {
  props: ['richtVariant',  ],
  data() {
    return {
      subTitles:[],
      numberSubTitles: 0,
    }
  },
  methods: {
    changeNumberSubTitles(){
      if ( this.subTitles.length < this.numberSubTitles) {
        this.subTitles.push({title:""})
      } else {
        this.subTitles.pop()
      }
    },
    subTitleDelete(ind){
      if (this.subTitles.length > 0){
        this.subTitles.splice(ind, 1);
        this.numberSubTitles = this.subTitles.length
      }
    },
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