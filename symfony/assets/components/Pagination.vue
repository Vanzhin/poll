<template>
  <div class="block" 
    v-if="getPagination.length > 1"
  >
    <div class="block-pagination">
      <div class="block-pagination-element"
        @click = "paginate('-1')"
        v-if="parseInt(getPagination[0].label) > 1"
      >
        <span>
          &laquo; Назад
        </span>
      </div>

      <div 
        v-for="(item, key) in getPagination"
        :key = "item.label" 
        class="block-pagination-element" 
        :class="{active:item.active}"
        @click =" paginate(parseInt(item.label))"
      >
        <span>
          {{item.label}}
        </span>
      </div>
      
      <div class="block-pagination-element"
        @click = "paginate('+1')"
        v-if="nextTotalPriznak"
      >
        <span>
          Вперед &raquo;
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['type', 'admin'],
  data() {
    return {
      isActive: false,
      currentPage: 1,
    }
  },
  computed:{
    ...mapGetters(["getPagination", "getTotalPage"]),
    nextTotalPriznak(){
      return (this.getTotalPage > 10) ? 
      (parseInt( this.getPagination[9].label) < this.getTotalPage) ? true : false
      : false
    }
  },
  methods: {
    ...mapActions(["getCategorysDB", "getTestsDB", "getQuestionsTestIdDb","setIsLoaderStatus"]),
    async paginate (page){
      switch (page) {
        case '-1' : {
          if (this.currentPage > 1) {
            console.log(this.getPagination[0].label)
            this.currentPage = parseInt(this.getPagination[0].label) - 1
            console.log(this.currentPage)
          } else { return }
        break;
        }
        case '+1' : {
          console.log(this.getTotalPage)
          if  (this.currentPage = parseInt(this.getPagination[9].label) < this.getTotalPage) {
            this.currentPage = this.currentPage = parseInt(this.getPagination[9].label) + 1
          } else { return }
        break;
        }
        default:{
          this.currentPage = page 
        }
      }
      this.setIsLoaderStatus({status:true})
      console.log(this.type)
     
      const data = {
        page: this.currentPage, 
        parentId: this.$route.params.id||null,
        id: this.$route.params.id||null,
        admin: this.admin
      }
      console.log(data)
      await this[this.type](data)
      this.setIsLoaderStatus({status:false})
    }
  }, 
} 
</script>
<style lang="scss" scoped>
.block{
  margin-top: 20px;
 
  &-pagination{
      display: flex;
      justify-content: center;
      align-items: center;
      &-element{
         padding: 5px 10px;
         border: 1px solid rgb(20, 126, 112);
         margin: 0 5px;
         min-width: 10px;
         &.active {
            border-color: rgb(45, 56, 207);
            background-color: rgb(134 161 152);
         }
         &:hover{
            cursor: pointer;
            border-color: rgb(45, 207, 186);
            background-color: #d7e9e9;
         }
      }
   }}
</style>