<template>
  <div class="block" 
    v-if="getPagination.length > 1"
  >
    <div class="block-pagination">
      <div class="block-pagination-element"
        @click = "paginate('-1')"
      >
        <span>
          &laquo; Предыдущая
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
      >
        <span>
          Следующая &raquo;
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props: ['type'],
  data() {
    return {
      isActive: false,
      currentPage: 1,
    }
  },
  computed:{
    ...mapGetters(["getPagination",]),
  },
  methods: {
    ...mapActions(["getCategorysDB", "getTestsDB", "getQuestionsTestIdDb"]),
    async paginate (page){
      switch (page) {
        case '-1' : {
          if (this.currentPage > 1) {
            --this.currentPage
          }
        break;
        }
        case '+1' : {
          if  (this.currentPage < this.getPagination.length) {
            ++this.currentPage
          }
        break;
        }
        default:{
          this.currentPage = page 
        }
      }
      console.log(this.type)
     
      await this[this.type]({
        page: this.currentPage, 
        parentId: this.$route.params.id||null,
        id: this.$route.params.id||null
      })
    }
  }, 
} 
</script>
<style lang="scss" scoped>
.block{
  margin-top: 20px;
  margin-bottom: 20px;
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
         }
         &:hover{
            cursor: pointer;
            border-color: rgb(45, 207, 186);
            background-color: #d7e9e9;
         }
      }
   }}
</style>