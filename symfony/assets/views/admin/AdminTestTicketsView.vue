<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      
      <h2>Билеты: </h2>
        
      
      <div class="button-group " >
        <div class="button-item"
          title="Добавить билет"
          @click.stop="addTicket"
        >
          <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
            <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
          </svg>
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
    ...mapActions(["getTicketsTestIdDb", "getTestIdDb"]),
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
    await this.getTestIdDb({id: +this.$route.params.id})
    await this.getTicketsTestIdDb({id: this.testId})
    this.isLoader = false
  },
 
} 

</script>
<style lang="scss" scoped>
  
  .title{
    display: flex;
    justify-content: space-between;
    margin: 10px;
    & h2{
      font-family: "Lato";
      font-style: normal;
      font-weight: 700;
      font-size: 26px;
      line-height: 40px;
      color: var(--color-blue);
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
