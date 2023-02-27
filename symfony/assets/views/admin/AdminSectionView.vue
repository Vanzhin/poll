<template>
   <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2>Область аттестации</h2>
    </div>
   <div class="container">
      <div class="row">
        В настоящее время ведется разработка функционала.
      </div>
    </div>
  </div>

  
</template>
 
<script>
  import MessageView from "../../components/ui/MessageView.vue"
  import Loader from '../../components/ui/Loader.vue'
  import ItemEdit from "../../components/Admin/ItemEdit.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  export default {
    components: {
      Loader,
      MessageView,
      ItemEdit
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
      setTimeout(()=>this.isLoader = false, 1000 )
      
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