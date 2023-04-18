<template>
  
  <Loader
    v-if="isLoader"
  />
    
  <div class="" v-else>
    <div  class="wrapper">
      <div class="tests__block">
        <h4>{{getCategoryTitle }}</h4>
        <h5>{{getCategoryDescription}}</h5>
      </div>
      <div class="tests__block"
        v-if="areas.length > 0"
      >
        <div
          v-for="(area, index) in areas" 
          :key="area.id"
          class="test__block"
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
      </div> 
      <div class="tests__block"
        v-else
      > 
        Данная категория в разработке.
      </div> 
      <Pagination
        type="getCategorysDB"
      />  
      
    </div>
  </div>
</template>
 
<script>
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Loader from '../components/ui/LoaderView.vue'
  import Pagination from '../components/Pagination.vue'
  export default {
    components: {
      Loader,
      Pagination
    },
    data() {
      return {
        isLoader: false,
        iter: this.$route.params.num,
        parentId: this.$route.params.id,
      }
    },
    computed:{
      ...mapGetters(["getIsAutchUser", "getCategoryTitle", "getTests", "getCategorys", "getCategoryDescription"]),
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
        "setCategorys"
        
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
      if (!this.getCategorys) {this.isLoader = true}
      await this.getCategorysDB({page: null, parentId: this.parentId})
      this.isLoader = false
    }
  } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>