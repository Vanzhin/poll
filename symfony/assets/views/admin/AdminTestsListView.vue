<template>
  <div
    class="fon"
  >
    <div class="container">
     
      <AdminTestsView/>
      <Pagination
        type="getTestsDB"
      />
    </div>
  </div>
</template>
 
<script>
  import AdminTestsView from "./AdminTestsView.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Pagination from "../../components/Pagination.vue"
  export default {
    components: {
      AdminTestsView,
      Pagination
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