<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    
    <div class="container">
      <div class="row">
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

        <div class="tests__block"
          v-if="tests.length > 0"
          >
            <ItemTest
              v-for="(item, index) in tests" 
              :key="item.id"
              :item="item"
              :index="index"
              @click.stop="testRoute(item)"
            />
          </div>
      </div>
    </div>
  </div>
</template>
 
<script>
 
  import Loader from '../../components/ui/LoaderView.vue'
  import ItemTest from "../../components/Admin/ItemTest.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
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
        "getTestsDB",
        "setTestItem"
        ]),
      ...mapMutations([]),
      async testRoute(item){
        await this.setTestItem(item)
        this.$router.push({name: 'adminTestQuestions', params: { id: item.id }})
      },
      createTest(){
        this.$router.push({
          name: 'adminTestCreate', 
          params: { 
            operation: "create", 
            id: this.parentId, 
            token: this.getAutchUserToken 
          }})
      },
    },
    async mounted(){},
    async created(){
      console.log(this.$route)
      console.log(this.parentId)
      if (this.parentId){
        await this.getCategorysDB({page: null, parentId: this.parentId, admin: true})
      } else { 
        await this.getTestsDB({}) 
      }
      this.isLoader = false
    }
 } 
 
</script>
<style lang="scss" scoped>
  .block{
    height: 80vh;
    overflow: auto;
  }
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