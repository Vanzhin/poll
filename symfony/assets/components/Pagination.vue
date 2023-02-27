<template>
   <div class="block">
      <div class="block-pagination">
        <div class="block-pagination-element"
          @click = "paginate('-1')"
        >
          <span>
            &laquo; Предыдущая
          </span>
        </div>
        
        <div 
          v-for="(item, key) in paginationArray"
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
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  data() {
    return {
      isActive: false,
      currentPage: 1,
      paginationArray: [
        {active: true,
          label: 1
        },
        {active: false,
          label: 2
        },
        {active: false,
          label: 3
        },
        {active: false,
          label: 4
        },
      ]
    }
  },
  components:{
   
  },
  computed:{
    // return this.$store.getters.getTestTitleActive
   
  },
  methods: {
    paginate (page){
      this.paginationArray[this.currentPage-1].active = false
      console.log(this.paginationArray[this.currentPage-1].active)
      switch (page) {
        case '-1' : {
          if (this.currentPage > 1) {
            
            --this.currentPage
          }
        break;
        }
        case '+1' : {
          if  (this.currentPage < this.paginationArray.length) {

            ++this.currentPage
          }
        break;
        }
        default:{
          this.currentPage = page 
        }
      }
      this.paginationArray[this.currentPage-1].active = true
      console.log(this.paginationArray[this.currentPage-1].active)
      //  dispatch(getDbArticlesPage({param, page: curent, token}))
    }
  }, 
  mounted() {
    
  }
} 

</script>


<style lang="scss" scoped>
.block{
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