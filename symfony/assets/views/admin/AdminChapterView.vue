<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Раздел</h2>
    </div>
    <div class="container">
        <div class="row">
          <div class="tests__block">
            <ItemChapter
              v-for="(item, index) in getCategorys" 
              :key="item.id"
              :item="item"
              :index="index"
            />
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
      }
    },
    computed:{ 
      ...mapGetters(["getCategorys","getAutchUserToken", "getMessage"]),
    },
   
    methods: { 
      ...mapActions(["getCategorysDB","saveQuestionDb", "setMessage"]),
      ...mapMutations([]),
    },
    async created(){
      await this.getCategorysDB({})
      this.isLoader = false
    }
   
 } 
 
</script>
<style lang="scss" scoped>
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