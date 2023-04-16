<template>
  <HeadesPage
    title="Тестирование Базовый курс"
    :subTitle="testName"
  />
  
  <div class="fon">
    
    <div class="wrapper">
    <div class="tickets-block">
      <div class="tickets-block-coll-tickets">
        <div class="tickets-block-coll-tickets-title">
          Выберите тестирование
        </div>

        <div class="tickets-block-coll-tickets-block">
          <RouterLink :to="{ name: 'ticket', params: { id: 'rndmax' } }" 
            class="tickets-block-coll-tickets-item">
            Тестирование по всем вопросам
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>
          </RouterLink>
         
          <RouterLink :to="{ name: 'ticket', params: { id: 'rndb' } }"  
            class="tickets-block-coll-tickets-item"
            v-if="ticketsIs()"
          >
            Случайный билет
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>
          </RouterLink>
          
          <RouterLink :to="{ name: 'ticket', params: { id: 'rnd' } }" 
            class="tickets-block-coll-tickets-item">
            Случайный набор
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>
          </RouterLink>
           
          <RouterLink :to="{ name: 'ticket', params: { id: 'rnd20' } }" 
            class="tickets-block-coll-tickets-item">
            Случайный набор из 20 вопросов
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>
          </RouterLink>
           
          <RouterLink :to="{ name: 'ticket', params: { id: 'rnd20t' } }" 
            class="tickets-block-coll-tickets-item">
            Случайный набор из 20 вопросов (таймер)
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
            </svg>
          </RouterLink>
         
        </div>
        <div class="tickets-block-coll-tickets-block">
          <RouterLink :to="{ name: 'ticket', params: { id: ticket.id } }" 
            class="tickets-block-coll-tickets-itemticket"
            v-for="(ticket, ind) in tickets"
            :key="ticket.id"
            @click="saveSelectTicket({ticket})"
          >
            Билет № {{ ticket.title }}
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
              <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" 
                fill="#FFFFFF"/>
            </svg>
          </RouterLink>
        </div>
      </div>
      <div class="tickets-block-coll-info">
        <div class="tickets-block-coll-info-item">
          <div>
            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle opacity="0.7" cx="12" cy="12.473" r="10" fill="#F8B200"/>
              <path d="M11.2982 14.0355H12.6982L12.9222 11.075V7.67297H11.0742V11.075L11.2982 14.0355ZM11.0742 17.473H12.9222V15.541H11.0742V17.473Z" fill="white"/>
            </svg>
          </div>
          <div class="tickets-block-coll-info-item-cont">
            <div class="tickets-block-coll-info-item-title">
              Случайный билет
            </div>
            <div class="tickets-block-coll-info-item-text">
              При выборе случайного билета система сама сделает выбор. Случайный набор сгенерирует билет с произвольным составом вопросов. 
            </div>
          </div>
        </div>
        <div class="tickets-block-coll-info-item">
          <div>
            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 12.473C20.5 17.1674 16.6944 20.973 12 20.973C7.30558 20.973 3.5 17.1674 3.5 12.473C3.5 7.7786 7.30558 3.97302 12 3.97302C16.6944 3.97302 20.5 7.7786 20.5 12.473ZM22 12.473C22 17.9959 17.5228 22.473 12 22.473C6.47715 22.473 2 17.9959 2 12.473C2 6.95017 6.47715 2.47302 12 2.47302C17.5228 2.47302 22 6.95017 22 12.473ZM11.25 7.5036V12.1374C11.25 12.8726 11.5444 13.5772 12.0675 14.0938L13.9729 15.976L15.0271 14.9089L13.1216 13.0267C12.8838 12.7918 12.75 12.4716 12.75 12.1374V7.5036H11.25Z" fill="#269EB7"/>
            </svg>

          </div>
          <div class="tickets-block-coll-info-item-cont">
            <div class="tickets-block-coll-info-item-title">
              Таймер
            </div>
            <div class="tickets-block-coll-info-item-text">
              Выбор набора с пометкой "Таймер" ограничит время на прохождение теста.
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tickets-block-info">
      Дополнительная информация
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9.50356 8.51725C9.22523 8.23826 9.22515 7.78666 9.50337 7.50757C9.78263 7.22745 10.2363 7.22731 10.5157 7.50726L14.2949 11.2936C14.6845 11.6839 14.6845 12.3161 14.2949 12.7064L10.5157 16.4927C10.2363 16.7727 9.78263 16.7725 9.50337 16.4924C9.22515 16.2133 9.22523 15.7617 9.50356 15.4828L12.9781 12L9.50356 8.51725Z" fill="#269EB7"/>
      </svg>

    </div>
  </div>
  </div>
</template>

<script>
import { RouterLink } from 'vue-router'
import { mapGetters, mapActions, mapMutations} from "vuex"
import HeadesPage from '../components/HeadersPage.vue'
export default {
  components: {
    RouterLink,
    HeadesPage
  },
  data() {
    return {
     
    }
  },
   computed:{
    ...mapGetters(["getTickets","getTests","getTest"]),
    
      testName () {
        return this.$store.getters.getTestTitleActive
      },
      tickets () {
        return this.$store.getters.getTickets
      },
      
    },
    methods: {
     ...mapActions([
      "selectTestId",
      "getTicketsTestIdNoAuthDb",
      "saveSelectTicketStore",
      "getTestIdDb"
    ]),
     saveSelectTicket({ticket}){
      this.saveSelectTicketStore({ticket})
     },
     ticketsIs() {
      console.log(this.getTest)
        return this.getTest.ticketCount > 0
      }
    },
    mounted(){
      // this.$store.dispatch('setTestTitle',{id : this.$route.params.id})
    },
    async created() { 
      if (this.getTests) {
        console.log('есть тесты')
        await this.selectTestId({id : this.$route.params.id})
        await this.getTicketsTestIdNoAuthDb({id : this.$route.params.id})
      } else {
        console.log('нет тестов')
        await this.getTestIdDb({id : this.$route.params.id})
        // await this.selectTestId({id : this.$route.params.id})
        await this.getTicketsTestIdNoAuthDb({id : this.$route.params.id})
      }
      
     
      this.isLoader = false
    }
  
} 

</script>
<style lang="scss" scoped>
  .fon{
    background-color: var(--color-fon);
    
  }
   .tickets{
    
    &-block{
      width: 100%;
      font-family: 'Lato';
      font-style: normal;
      display: flex;
      justify-content: space-between;
      padding-top: 28px;
      padding-bottom: 94px;
      &-coll{
        &-info{
          padding: 24px;
          width: 285px;
          height: 376px;
          background-color: #ffffff;
          filter: drop-shadow(0px 1px 4px rgba(92, 101, 119, 0.26)) drop-shadow(0px 24px 48px rgba(109, 120, 147, 0.1));
          &-item{
            display: flex;
            margin-bottom: 24px;
            &-title{
              font-weight: 700;
              font-size: 16px;
              line-height: 24px;
            }
            &-text{
              font-weight: 400;
              font-size: 16px;
              line-height: 24px;
              color: #0B1F33;
              margin-top: 8px;
            }
            &-cont{
              padding-left: 16px;
            }

          }
        }
        &-tickets{
          
          &-block{
            
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            max-width: 782px;
            margin-right: 44px;}
          &-item{
            text-decoration: none;
            width: 48%;
            height: 52px;
            padding: 14px 10px;
            display: flex;
            justify-content: space-between;
            background: #FFFFFF;
            border: 1px solid var(--color-blue);
            box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
            border-radius: 6px;
            color: var(--color-blue);
            margin-bottom: 15px;
            
            &:nth-child(odd) {
              margin-right: 24px;
            }
            &:hover{
              background-color: var(--color-blue);
              color: #FFFFFF;
              path {
                fill:#FFFFFF;
              }
            }
          }
          &-itemticket{
            text-decoration: none;
            width: 173px;
            height: 52px;
            padding: 14px 10px;
            display: flex;
            justify-content: space-between;
            background: var(--color-blue);
            border: 1px solid var(--color-blue);
            box-shadow: 0px 1px 4px #E3EBFC, 0px 24px 48px rgba(230, 235, 245, 0.4);
            border-radius: 6px;
            color: #FFFFFF;
            margin-bottom: 15px;
            margin-right: 26px;
            &:nth-child(4n) {
              margin-right: 0px;
            }
            &:hover{
              background-color: #FFFFFF;
              color: var(--color-blue);
              path {
                fill:var(--color-blue);
              }
            }
          }

          &-title{
            font-weight: 700;
            font-size: 20px;
            line-height: 40px;
            margin-bottom: 25px;
          }
        }
      }
      &-info{
      display: flex;
      align-items: center;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: var(--color-blue);
      margin-top: 63px;
      padding-bottom: 63px;

      &:hover{
        cursor: pointer;
      }
    }
    }
    
  }
@media (min-width: 1024px) {
 
}
</style>
