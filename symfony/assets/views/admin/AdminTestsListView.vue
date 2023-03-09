<template>
  <div class="block">
    <div class="title">
      <h2>Тесты</h2>
    </div>
    <AdminTestsViewVue/>
    <Pagination
      type="getTestsDB"
    />
  </div>

  
</template>
 
<script>
  import AdminTestsViewVue from "./AdminTestsView.vue"
  import { mapGetters, mapActions, mapMutations} from "vuex"
  import Pagination from "../../components/Pagination.vue"
  export default {
    components: {
      AdminTestsViewVue,
      Pagination
    },
    data() {
      return {
        isLoader: true,
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