<template>
  <div class="block-contener" 
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
        v-for="(item) in getPagination"
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
    ...mapActions([
      "getCategorysDB", 
      "getTestsDB", 
      "getQuestionsTestIdDb",
      "setIsLoaderStatus",
      "getTicketsTestIdDb",
      "getSearchDb",
      "getCompanyList"
    ]),
    async paginate (page){
      switch (page) {
        case '-1' : {
          if (this.currentPage > 1) {
            this.currentPage = parseInt(this.getPagination[0].label) - 1
          } else { return }
        break;
        }
        case '+1' : {
          if  (this.currentPage = parseInt(this.getPagination[9].label) < this.getTotalPage) {
            this.currentPage = parseInt(this.getPagination[9].label) + 1
          } else { return }
        break;
        }
        default:{
          this.currentPage = page 
        }
      }
      this.setIsLoaderStatus({status:true})
           
      let data = {
        page: this.currentPage, 
        parentId: this.$route.params.id||null,
        id: this.$route.params.id||null,
        admin: this.admin
      }
      
      await this[this.type](data)
      this.setIsLoaderStatus({status:false})
    }
  }, 
} 
</script>
<style lang="scss" scoped>
.block{
  &-contener{
    margin-top: 20px;
    padding-bottom: 30px;
  }
  &-pagination{
      display: flex;
      justify-content: center;
      align-items: center;
      &-element{
         padding: 5px 10px;
         
         margin: 0 5px;
         min-width: 10px;
         border-radius: 5px;
         color: var(--color-blue);
         &.active {
            background-color: var(--color-blue);
            color: var(--color-white)
         }
         &:hover{
            cursor: pointer;
            
            background-color: var(--color-hover-logo)
         }
      }
   }}
</style>