<template>
  
      
  <div class=""  v-if="!getIsLoaderStatus">
    <HeadesPage
      :title="getCategoryTitle"
    />
    <div class="fon">
      <div  class="wrapper">
        
        <div class="tests__block"
          v-if="areas.length > 0"
        >
          <div
            v-for="(area, index) in areas" 
            :key="area.id"
            
          >
            <div class="test__card"
              v-if="getIsAutchUser || index < 3"
              @click="categoryUpdate({ area })"
            >
              <div>
                {{ area.id }} - {{ area.title }}
              </div>
            </div>
            <div class="test__card-limitation"
              v-else
              title="У Вас ограниченный доступ. Подпишитесь на группу."
            >
              {{ area.id }} - {{ area.title }}
            </div>
          </div>
          <div class="test__block-info">
            Дополнительная информация
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>

          </div>
        </div> 
        <div class="tests__block min-heig"
          v-else
        > 
          Данная категория в разработке.
        </div> 
        <Pagination
          type="getCategorysDB"
        />  
      </div>
    </div>
  </div>
</template>
 
<script>
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Loader from '../components/ui/LoaderView.vue'
  import Pagination from '../components/Pagination.vue'
  import HeadesPage from '../components/HeadersPage.vue'
  export default {
    components: {
      Loader,
      Pagination,
      HeadesPage
    },
    data() {
      return {
        isLoader: false,
        iter: this.$route.params.num,
        parentId: this.$route.params.id,
      }
    },
    computed:{
      ...mapGetters([
        "getIsAutchUser", 
        "getCategoryTitle", 
        "getTests", 
        "getCategorys", 
        "getCategoryDescription",
        "getIsLoaderStatus"
      ]),
      sectionTitle () {
       
        return this.$store.getters.getSectionTitle(this.$route.params.id)
      },
      areas () {
        return this.getCategorys || []
      },
    },
    // watch:{
    //   parentId(newParentId){
    //     console.log("newParentId -", newParentId)
    //     this.categoryUpdate({id:newParentId, title:''})
    //   }
    // },
    watch:{
      $route(newRout){
       
        this.categoryUpdateStory(newRout.params.id)
      }
    },
    methods: {
      ...mapActions([
        "getCategorysDB", 
        "setCategoryTitle",
        "setTests",
        "setPagination", 
        "setCategoryParent",
        "setCategorys",
        "setIsLoaderStatus",
        
      ]),
      async categoryUpdate({area}){
        this.getCategorysDB({page:null, parentId: area.id})
        this.setCategoryParent(area)
        this.setPagination(null)
        if (area.test.length > 0) {
          this.setTests(area.test)
          this.$router.push({name: 'area', params: {id: area.id } })
          return
        } 
        this.iter = this.$route.params.num
        this.setCategorys(area.children)
        this.$router.push({name: 'iter', params: { num: ++this.iter, id: area.id }})
      },
      async categoryUpdateStory(parentId) {
        this.isLoader = true
        console.log('categoryUpdateStory - ', parentId)
        await this.getCategorysDB({page: null, parentId})
        this.isLoader = false
      }
    },
    async mounted() {},
    async created() {
      if (!this.getCategorys) {this.setIsLoaderStatus({status: true})}
      await this.getCategorysDB({page: null, parentId: this.parentId})
      this.setIsLoaderStatus({status: false})
    }
  } 
 
</script>
<style lang="scss" scoped>
 .min-heig{
  min-height: 20vh;
}
  .test__block{
    margin-bottom: 15px;
    & a{
      color: var(--color-blue);
      text-decoration: none;
    }
    &-info{
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-blue);
      margin-top: 63px;
      padding-bottom: 63px;

      &:hover{
        cursor: pointer;
      }
    }
  }



 @media (min-width: 1024px) {
  
 }
</style>