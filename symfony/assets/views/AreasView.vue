<template>
   <Loader
    v-if="isLoader"
  />
  <div class="" v-else>
    <div class="tests__block">
      <h4>{{getCategoryTitle }}</h4>
      <h5>{{activity[iter]}}</h5>
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
    > В данная категория в разработке.</div> 
    <Pagination/>  
     
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
        iter:1,
        activity: [
          "",
          "Выберите область аттестации:",
          "Выберите тест:",
          "Выбери уже что-то:",
          "Ткни куда-нибудь:",
          "Еще не насчелкался?"
        ]
      }
    },
    computed:{
      ...mapGetters(["getIsAutchUser", "getCategoryTitle", "getTest", "getCategorys"]),
      sectionTitle () {
        console.log(this.$route.params.id)
        console.log(this.$store.getters.getSectionTitle(this.$route.params.id))
        return this.$store.getters.getSectionTitle(this.$route.params.id)
      },
      areas () {
        return this.$store.getters.getCategorys || []
      },
    },
    methods: {
      ...mapActions(["getCategorysDB", "setCategoryTitle"]),
      async categoryUpdate({id, title}){
        this.isLoader = true
        this.setCategoryTitle(title)
        this.iter = this.$route.query.iter
        console.log(this.iter)
        
        await this.getCategorysDB({page:null, parentId: id})
        console.log('this.getTest - ', this.getTest)
        if (this.getTest) {
          this.$router.push({name: 'test', params: {id: id } })
        } else if (this.areas.length > 0 ) {
          ++this.iter 
          this.$router.push({name: 'chapter', query: { iter: this.iter, group: id } })
        }
        
        this.isLoader = false
      }
    },
    async mounted(){
      // this.$store.dispatch('setTestTitle',{id : this.$route.params.id})
      await this.getCategorysDB({page:null, parentId: this.$route.query.group})
      this.isLoader = false
    }
   
  } 
 
</script>
<style lang="scss" scoped>
 @media (min-width: 1024px) {
  
 }
</style>