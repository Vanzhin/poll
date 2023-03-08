<template>
   <Loader
    v-if="isLoader"
  />
  <div class="" v-else>
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
          @click="categoryUpdate({id: area.id, title: area.title})"
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
</template>
 
<script>
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Loader from '../components/ui/Loader.vue'
  import Pagination from '../components/Pagination.vue'
  export default {
    components: {
      Loader,
      Pagination
    },
    data() {
      return {
        isLoader: true,
        iter: this.$route.params.num,
        parentId: this.$route.params.id,
      }
    },
    computed:{
      ...mapGetters(["getIsAutchUser", "getCategoryTitle", "getTests", "getCategorys", "getCategoryDescription"]),
      sectionTitle () {
        console.log(this.$route.params.id)
        console.log(this.$store.getters.getSectionTitle(this.$route.params.id))
        return this.$store.getters.getSectionTitle(this.$route.params.id)
      },
      areas () {
        return this.$store.getters.getCategorys || []
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
        console.log("newParentId -", newRout)
        this.categoryUpdateStory(newRout.params.id)
      }
    },
    methods: {
      ...mapActions(["getCategorysDB", "setCategoryTitle"]),
      async categoryUpdate({id}){
        this.isLoader = true
        
        this.iter = this.$route.params.num
        console.log(this.iter)
        await this.getCategorysDB({page:null, parentId: id})
        console.log('this.getTests - ', this.getTests)
        ++this.iter
        
        if (this.getTests) {
          this.$router.push({name: 'area', params: {id } })
          return
        } 
        this.$router.push({name: 'iter', params: { num: this.iter, id }})
        this.isLoader = false
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
      await this.getCategorysDB({page: null, parentId: this.parentId})
      this.isLoader = false
    }
  } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>