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
      <div class="button-cont" >
        <div class="button"
          title="Добавить новую категорию"
          @click.stop="createCategory"
        >
          <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
            <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
          </svg>
        </div>
        <div class="button"
            title="Добавить тест"
            @click.stop="createTest"
            v-if="!(areas.length > 0)"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
              <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
            </svg>
          </div>
      </div>
    </div>
    
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
          <Pagination
            type="getCategorysDB"
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
        const ar = this.getCategorys || []
        return ar
      },
    },
    watch:{
      $route(newRout){
        console.log("newParentId -", newRout)
        this.categoryRoute({id: newRout.params.id})
      }
    },
    methods: { 
      ...mapActions(["getCategorysDB", "saveQuestionDb", "setMessage"]),
      createCategory(){
        this.$router.push({name: 'adminCategoryCreate', params: {operation:"create", id:  this.$route.params.id  } })
      },
      createTest(){
        this.$router.push({name: 'adminTestCreate', params: {operation:"create", id:  this.$route.params.id  } })
      },
      async categoryRoute({id}){
        this.isLoader = true
        await this.getCategorysDB({parentId: id, admin: true})
        if (this.getTests) {
          console.log('переход к списку тестов - ', this.getTests)
            this.$router.push({name: 'adminTests', params: {id } })
            setTimeout(() => this.isLoader = false, 200)
          return
        } 
        console.log('переход к списку категорий - ', this.getTests)
        this.$router.push({name: 'adminIter', params: { num: 1, id: id } })
        setTimeout(() => this.isLoader = false, 200)
        // this.$router.push({name: 'chapter', query: { iter: 1, group:id } })
      },
      // async categoryUpdateStory(parentId) {
      //   this.isLoader = true
      //   console.log('categoryUpdateStory - ', parentId)
      //   await this.getCategorysDB({page: null, parentId})
      //   this.isLoader = false
      // }
    },
    
    created(){
      this.categoryRoute({id: this.parentId})
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
  .button{
    margin-right: 20px;
    &-cont{
      display: flex;
      justify-content: end;
      align-items: center;
    }
    
    transition: all 0.1s ease-out;
    &:hover{
      cursor: pointer;
      transform: scale(1.15);
    }
  }
</style>