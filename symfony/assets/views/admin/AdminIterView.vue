<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <div class="tests__block">
        <h3>{{getCategoryTitle }}</h3>
        <h6>{{getCategoryDescription}}</h6>
      </div>
      <div class="btn-group" >
        <div class="btn btn-outline-primary create"
          title="Добавить новую категорию"
          @click.stop="createCategory"
        >
          <i class="bi bi-plus create-plus" ></i>
        </div>
        <div class="btn btn-outline-primary btn-center"
            title="Добавить тест"
            @click.stop="createTest"
            v-if="!(areas.length > 0)"
          >
            <i class="bi bi-archive"></i>
          </div>
      </div>
    </div>
    <MessageView/>
    <div class="container">
        <div class="row">
          <div class="tests__block"
          v-if="areas.length > 0"
          >
            <ItemChapter
              v-for="(item, index) in areas" 
              :key="item.id"
              :item="item"
              :index="index"
              @click.stop="categoryRoute({id:item.id})"
            />
          </div>
          <div class="tests__block"
            v-else
          > 
            В данной категории еще нет вложенных секций. Воспользуйтесь кнопкой 
            чтобы создать подкатегорию или прикрепить тест.
          </div> 
          <Pagination/>
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
        iter: this.$route.params.num,
        parentId: this.$route.params.id,
      }
    },
    computed:{ 
      ...mapGetters([
        "getAutchUserToken", 
        "getMessage", 
        "getCategoryTitle", 
        "getTests", 
        "getCategorys", 
        "getCategoryDescription"
      ]),
      areas() {
        console.log("ar -", this.getCategorys)
        const ar = this.getCategorys || []
        console.log("ar2 -", ar)
        return ar
      },
    },
    watch:{
      $route(newRout){
        console.log("newParentId -", newRout)
        this.categoryUpdateStory(newRout.params.id)
      }
    },
    methods: { 
      ...mapActions(["getCategorysDB", "saveQuestionDb", "setMessage"]),
      createCategory(){
        this.$router.push({name: 'adminsCategoryCreate', params: {operation:"create", id: this.parentId  } })
      },
      createTest(){
        this.$router.push({name: 'adminsTestCreate', params: {operation:"create", id: this.parentId  } })
      },
      async categoryRoute({id}){
        this.isLoader = true
        await this.getCategorysDB({parentId: id})
        if (this.getTests) {
          console.log('переход к списку тестов - ', this.getTests)
            //this.$router.push({name: 'area', params: {id } })
          this.isLoader = false
          return
        } 
        console.log('переход к списку категорий - ', this.getTests)
        this.$router.push({name: 'adminIter', params: { num: 1, id: id } })
        this.isLoader = false
        // this.$router.push({name: 'chapter', query: { iter: 1, group:id } })
      },
      async categoryUpdateStory(parentId) {
        this.isLoader = true
        console.log('categoryUpdateStory - ', parentId)
        await this.getCategorysDB({page: null, parentId})
        this.isLoader = false
      }
    },
    
    async created(){
      await this.getCategorysDB({page: null, parentId: this.parentId})
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
</style>