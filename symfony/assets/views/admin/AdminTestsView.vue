<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div
      class="fon"
    >
      <div class="container">
        <div class="row">
          <div class="title">
            <div class="title-block">
              <div
                v-if="parentId"
              >{{getCategoryTitle }}</div>
              <div 
              v-if="getSearchSign"
              > Результаты поиска
              </div>
              <div>Тесты</div>
            </div>
            <div class="title-create"
              title="Добавить тест"
              @click.stop="createTest"
              v-if="parentId"
            >
              <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
                <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
              </svg>
            </div>
           
          </div>
         
            <Button/>
        
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
  </div>
  <Pagination
    :type = "typePagin"
    admin = true
  />
</template>
 
<script>
 
  import Loader from '../../components/ui/LoaderView.vue'
  import ItemTest from "../../components/Admin/ItemTest.vue"
  import Button from "../../components/ui/ButtonBack.vue"
  import Pagination from "../../components/Pagination.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      ItemTest,
      Button,
      Pagination
    },
    data() {
      return {
        isLoader: true,
        parentId: this.$route.params.id,
        typePagin: ''
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage",
        "getCategoryTitle",
        "getTests", 
        "getSearchSign"
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
    mounted(){
      
    },
    updated(){
      if (this.getSearchSign){
        this.typePagin='getSearchDb'
      }
      this.isLoader = false
    },
    async created(){
      
      if (!this.getSearchSign){
        if (this.parentId){
          await this.getCategorysDB({page: null, parentId: this.parentId, admin: true})
          this.typePagin='getCategorysDB'
        } else { 
          await this.getTestsDB({}) 
          this.typePagin='getTestsDB'
        }
      } else {
        this.typePagin='getSearchDb'
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
    padding-top: 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    &-block{
      font-family: 'Lato';
      font-style: normal;
      font-weight: 700;
      font-size: 30px;
      line-height: 40px;
      color: var(--color-blue);
    }
    &-create{
      cursor: pointer;
    }
  }
  
  
 @media (min-width: 1024px) {
  
 }
</style>