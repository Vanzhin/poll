<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Разделы</h2>
      <div class="btn-group " >
        <div class="btn btn-outline-primary create"
          title="Добавить новую категорию"
          @click.stop="createCategory"
        >
          <i class="bi bi-plus create-plus"></i>
        </div>
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <div class="tests__block"
          v-if="getCategorys">
          <ItemChapter
            v-for="(item, index) in getCategorys" 
            :key="item.id"
            :item="item"
            :index="index"
            @click.stop="categoryRoute({id:item.id})"
          />
        </div>
        <div class="tests__block"
          v-else>
        <h4>Создайте раздел.</h4>
        </div>

        <Pagination
          type="getCategorysDB"
          admin= true
        />
      </div>
    </div>
  </div>
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import ItemChapter from "../../components/Admin/ItemChapter.vue"
  import Loader from '../../components/ui/Loader.vue'
  import Pagination from "../../components/Pagination.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView,
      ItemChapter,
      Pagination
    },
    data() {
      return {
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getCategorys","getAutchUserToken", "getMessage", "getTests"]),
    },
   
    methods: { 
      ...mapActions(["getCategorysDB", "setMessage"]),
      ...mapMutations([]),
      createCategory(){
        this.$router.push({name: 'adminsCategoryCreate', params: {operation:"create", id: 0  } })
      },
      async categoryRoute({id}){
        this.isLoader = true
        await this.getCategorysDB({parentId: id, admin: true})
        if (this.getTests) {
          console.log('переход к списку тестов - ', this.getTests)
            this.$router.push({name: 'adminsTests', params: {id } })
            setTimeout(() => this.isLoader = false, 200)
          return
        } 
        console.log('переход к списку категорий - ', this.getTests)
        this.$router.push({name: 'adminIter', params: { num: 1, id: id } })
        setTimeout(() => this.isLoader = false, 200)
        // this.$router.push({name: 'chapter', query: { iter: 1, group:id } })
      },
    },
    async created(){
      await this.getCategorysDB({admin: true})
      this.isLoader = false
    },
    
   
 } 
 
</script>
<style lang="scss" scoped>
  .title{
    display: flex;
    justify-content: space-between;
   
  }
  .create{
    display: flex;
    align-items: center;
    &-plus{
      transform: scaleY(1.3);
    }
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