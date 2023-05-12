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
      ...mapMutations([]),
    },
    async mounted(){}
   
 } 
 
</script>
<style lang="scss" scoped>

</style>