<template>
  <div
    class="item__block"
    @click.stop="childToggle"
  >
    <div class="">
      <div class="item__card">
        <div class="item__card-row" >
          <div class="item__card-header"> 
            <div class="item__card-block">
             
              <div class="item__card-title">
                {{ ticket.id }} - Билет №{{ ticket.title }}
              </div>
              <div class="item__card-title">
                Вопросов в билете - {{ ticket.question.length }}
              </div>
            </div>
           
          </div>
          <div class="btn-group" >
            <div class="btn btn-outline-primary btn-center"
              title="Редактировать"
              @click.stop="editTicket"
            >
              <i class="bi bi-pencil"></i>
            </div>
            <div class="btn btn-outline-primary btn-center" 
            title="Удалить"
              @click.stop="deleteVisibleConfirm"
            >
              <i class="bi bi-trash3"></i>
            </div>
            
            
          </div>
        </div>
       </div>
      <div class="item__card__child"
        v-if="childVisible"
        @click.stop="childToggle"
      >
        Включает:
      </div>
    </div>
  </div>
</template>
<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  props:['ticket', 'index'],
  components: {
   
  },
  data() {
    return {
      id: null,
      childVisible: false,
    }
  },
  computed:{
    ...mapGetters([
      "getIsAutchUser", 
      "getCategoryParendId", 
      "getMessage", 
      "getTests",
      "getActivePage",
      "getGonfimAction",
      "getGonfimMessage"
  ]),
  },
  methods:{
    ...mapActions([
      "deleteTicketIdDb", 
      "setConfirmMessage",
      "getTestIdDb",
      "saveSelectTicketStore"
    ]),
    
    childToggle(){
      this.childVisible = !this.childVisible
    },
    editTicket(){
      this.saveSelectTicketStore({ticket: this.ticket})
      this.$router.push({
        name: 'adminTicketCreate', 
        params: {
          operation:"edit" ,
          testId: this.$route.params.id , 
          ticketId: this.ticket.id 
        }
      })
    },
    async deleteTicket(){
    
      await this.deleteTicketIdDb({
        id: this.ticket.id, 
        testId: this.$route.params.id,
        activePage: this.getActivePage,
      })
      await this.getTestIdDb({id: this.$route.params.id})
    },
    deleteVisibleConfirm(){
      this.setConfirmMessage(`Вы, действительно хотите удалить Билет № ${this.ticket.title}?`)
      let timerId = setInterval(() => {
        if (this.getGonfimAction) {
          clearInterval(timerId)
          if (this.getGonfimAction === "yes" ){this.deleteTicket()}
        }{}
      }, 200);
    },
    
  }
} 

</script>
<style lang="scss" scoped>
  .item{
    &__block{
    margin: 10px 10px;
    position: relative;
  }
    
  }
  .item__card{
    background-color: #e2e5fc;
    padding: 5px;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    position: relative;
    &-header{
      flex: 10;
      
    }
    &-title{
      margin-left: 10px;
      min-height: 40px;
    }
    &-info{
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      &-item{
        flex: 1;
        min-width: 150px;
      }
    }
    &-row{
      display: flex;
      justify-content: space-between;
      align-items: center;
      min-width: 0;
      word-wrap: break-word;
      flex-wrap: wrap;
    }
    &__img{
      height: 100px;
      width: 150px;
      margin-right: 15px;
    }
    &__child{
      height: 100px;
      background-color: rgb(219 216 227);
    }
    &__parend{
      height: 50px;
      background-color: rgb(213, 204, 238);
    }
    &-block{
      display: flex;
      
      align-items: center;
      flex-wrap: wrap;
    }
  }
 .item__card:hover{
    background-color: aliceblue;
    cursor: pointer;
  }
  .btn-center{
    display: flex;
    align-items: center;
  }  

</style>
