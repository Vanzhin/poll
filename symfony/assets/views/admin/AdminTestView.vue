<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <h2> {{ testName }}</h2>
      <div class="test">
        <p> {{ ticketTitle }}</p>  
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <div v-for="(qestion, index ) in qestions" 
          :key="qestion.id"
        >
          <ItemQuestion
            :qestion="qestion"
            :index="numQuestion(index)"
          />
        </div>
        <Pagination
          type="getQuestionsTestIdDb"
        />
      </div>
    </div>
  </div>
</template>

<script>

import ItemQuestion from '../../components/Admin/ItemQuestion.vue'
import Pagination from "../../components/Pagination.vue"
import MyConfirm from '../../components/ui/MyConfirm.vue'
import Loader from '../../components/ui/Loader.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  components: {
    ItemQuestion,
    Loader,
    Pagination,
    MyConfirm
  },
  data() {
    return {
      isLoader: true,
      ticketId: this.$route.params.id,
      ticketTitle:"",
      confirmMessage: '',
      confirmVisible: false,
      confirmYes: null
    }
  },
  computed:{
    ...mapGetters([
      "getSlug", 
      "getRandomTicket", 
      "getSelectTicket",
      "getTest",
      "getActivePage",
      "getTotalItemsPage"
    ]),
    testName () {
      return this.$store.getters.getTestTitleActive
    },
    qestions () {
      return this.$store.getters.getQuestions
    }
  },
   methods: {
    ...mapActions(["getQuestionsTestIdDb", ]),
    numQuestion(index){
      return index + (this.getActivePage - 1) * this.getTotalItemsPage
    },
  },
  async created(){
    await this.getQuestionsTestIdDb({id: this.ticketId})
    this.isLoader = false
  },
 
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
  }
  .title{
    margin: 10px;
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
    }
  }
  .test{
    display: flex;
  }
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  .button{
    border: 1px solid rgb(171 171 171);
    padding: 5px 10px;
    border-radius: 5px;
    &:hover{
      background-color: rgb(225 225 221);
    }
  }
  .btn-center{
    display: flex;
    align-items: center;
  }  
@media (min-width: 1024px) {
 
}
</style>
