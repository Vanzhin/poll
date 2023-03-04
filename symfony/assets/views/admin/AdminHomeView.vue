<template>
   
  <div class="block"
   
  >
    <div class="title">
      <h2>Вы находитесь на странице администратора.</h2>
    </div>
   <div class="container">
      <div class="row">
        
      </div>
    </div>
    <PaginationComponent/> 
  </div>

  
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import PaginationComponent from "../../components/Pagination.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView,
      PaginationComponent
    },
    data() {
      return {
        isLoader: true,
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage"]),
    },
   
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage"]),
      ...mapMutations([]),
    },
    async mounted(){
      setTimeout(()=>this.isLoader = false, 2000 )
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