<template>
  <div
    class="fon"
  >
    <div class="container">
     
      <AdminTestsView/>
      
    </div>
  </div>
</template>
 
<script>
  import AdminTestsView from "./AdminTestsView.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  
  export default {
    components: {
      AdminTestsView,
      
    },
    data() {
      return {
      }
    },
    computed:{ 
      ...mapGetters(["getAutchUserToken", "getMessage", "getPagination"]),
    },
    methods: { 
      ...mapActions(["saveQuestionDb", "setMessage"]),
      ...mapMutations(["SET_SEARCH_SING_FALSE"]),
    },

    async mounted(){},
    unmounted(){
      this["SET_SEARCH_SING_FALSE"]()
    }
   
 } 
 
</script>
<style lang="scss" scoped>

</style>