<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <div>
        <h2>Билеты: </h2>
        
      </div>
      <div class="btn-group " >
        <div class="btn btn-outline-primary btn-center"
          title="Добавить билет"
          @click.stop="addTicket"
        >
          <i class="bi bi-plus create-plus"></i>
        </div>
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        <div
          v-if="getTickets"
        >
          <div v-for="(ticket, index ) in getTickets" 
            :key="ticket.id"
          >
            <ItemTicket
              :ticket="ticket"
              
            />
          </div>
          <Pagination
            type="getTicketsTestIdDb"
          />
        </div>
        <div
          v-else
        >
          <p>В тесте нет билетов. Вы можете их создать.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import ItemTicket from '../../components/Admin/ItemTicket.vue'
import Pagination from "../../components/Pagination.vue"
import Loader from '../../components/ui/LoaderView.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  components: {
    Loader,
    Pagination,
    ItemTicket
  },
  data() {
    return {
      isLoader: true,
      testId: this.$route.params.id,
      testTitle:"",
    }
  },
  computed:{
    ...mapGetters([
      "getTickets"
    ]),
  
  },
   methods: {
    ...mapActions(["getTicketsTestIdDb", ]),
    addTicket(){
      this.$router.push({
          name: 'adminTicketCreate', 
          params: { 
            operation: "create", 
            testId: this.testId, 
            ticketId: 0 
          }})
    }
  },
  async created(){
    await this.getTicketsTestIdDb({id: this.testId})
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
    display: flex;
    justify-content: space-between;
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
