<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title"
    v-if="parentId"
    >
      <div class="tests__block">
        <h3>{{getCategoryTitle }}</h3>
        <h6>Тесты</h6>
      </div>
      <div class="btn-group" >
        <div class="btn btn-outline-primary btn-center"
            title="Добавить тест"
            @click.stop="createTest"
          >
            <i class="bi bi-archive"></i>
          </div>
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <div class="tests__block"
          v-if="tests.length > 0"
          >
            <ItemTest
              v-for="(item, index) in tests" 
              :key="item.id"
              :item="item"
              :index="index"
              @click.stop="testRoute({id: item.id})"
            />
          </div>
      </div>
    </div>
  </div>

  <!-- <Pagination
        type="getCategorysDB"
        /> -->
</template>
 
<script>
 
  import Loader from '../../components/ui/Loader.vue'
  import Pagination from "../../components/Pagination.vue"
  import ItemTest from "../../components/Admin/ItemTest.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      Pagination,
      ItemTest
    },
    data() {
      return {
        isLoader: true,
        parentId: this.$route.params.id,
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage",
        "getCategoryTitle",
        "getTests", 
      ]),
      tests(){
        if (!this.getTests) {this.$router.go(-1)}
        
        return this.getTests ? this.getTests : []
      }
    },
   
    methods: { 
      ...mapActions([
        "saveQuestionDb", 
        "setMessage",
        "getCategorysDB",
        "getTestsDB"
        ]),
      ...mapMutations([]),
      testRoute({id}){
        this.$router.push({name: 'adminsTest', params: { id  } })
      },
      createTest(){
        this.$router.push({name: 'adminsTestCreate', params: {operation:"create", id: this.parentId  } })
      },
    },
    async mounted(){},
    async created(){
      console.log(this.$route)
      console.log(this.parentId)
      if (this.parentId){
        await this.getCategorysDB({page: null, parentId: this.parentId})
      } else { 
        await this.getTestsDB({}) 
      }
      
      this.isLoader = false
    }
   
 } 
 
</script>
<style lang="scss" scoped>
.title{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .create{
    display: flex;
    align-items: center;
    &-plus{
      transform: scaleY(1.3);
    }
  }
  .btn-center{
    display: flex;
    align-items: center;
  }  
  .button{
    padding: 5px 10px;
    transition: all 0.1s ease-out;
    &:hover{
      background-color: rgb(156, 156, 154);
    }
  }
 @media (min-width: 1024px) {
  
 }
</style>