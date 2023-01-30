<template>
  <div class="block">
    <div class="title">
      <h2> {{ testName.title }}</h2>
      <div class="test">
        <p>При выборе случайного билета система сама сделает выбор. Случайный набор сгенерирует билет с произвольным составом вопросов. Выбор набора с пометкой "Таймер" ограничит время на прохождение теста.</p>  
      </div>
      
    </div>
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
          <div class="card flex-shrink-1 shadow">
            <RouterLink :to="{ name: 'ticket', params: { id: 2003 } }" class="py-2 link">
              Случайный билет
            </RouterLink>
            
          </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6"> 
          <div class="card flex-shrink-1 shadow">
            <RouterLink :to="{ name: 'ticket', params: { id: 2002 } }" class="py-2 link">
              Случайный набор
            </RouterLink>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6"> 
          <div class="card flex-shrink-1 shadow">
            <RouterLink :to="{ name: 'ticket', params: { id: 2001 } }" class="py-2 link">
              Случайный набор из 20 вопросов
            </RouterLink>
          </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6"> 
          <div class="card flex-shrink-1 shadow">
            <RouterLink :to="{ name: 'ticket', params: { id: 2000 } }" class="py-2 link">
              Случайный набор из 20 вопросов (таймер)
            </RouterLink>
          </div>
        </div>
        <div class="container">
          <div id="br"></div>
          <div id="yandex_ad118" style="margin:0 auto;"></div>
          <div id="br2"></div>
        </div>

        <div class="col-6 col-sm-6 col-md-3 col-lg-2"
          v-for="(ticket, ind) in tickets"
          :key="ticket.id"
        > 
          <div class="card flex-shrink-1 shadow">
            <RouterLink :to="{ name: 'ticket', params: { id: ind } }" class="py-2 link">
              Билет №{{ ind + 1 }}
            </RouterLink>
          </div>
        </div>
       
         
      </div>
    </div>
  </div>
</template>

<script>
import TestQuestion from '../components/TestQuestionRadio.vue'
import { RouterLink } from 'vue-router'
export default {
  components: {
    TestQuestion,
  },
  data() {
    return {
      count: 0,
        //  testName:''
        //  testNumm: $route.params.id
    }
  },
   computed:{
      testName () {
        return this.$store.getters.getTestTitleActive
      },
      tickets () {
        return this.$store.getters.getTickets
      },
    },
    methods: {
      onSubmit(e){
        console.dir(e.target[0])
        console.log(e.target[0].value)
      },
    },
    mounted(){
      console.log(this.$route.params.id)
      this.$store.dispatch('setTestTitle',{id : this.$route.params.id})
    }
  
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(231 231 225);
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
  .link{
    text-decoration: none;
    color: #204242;
    &:hover {
      text-decoration: underline;
      color: cadetblue;
     
        background-color: #e0e4e7;
      
    }
  }
@media (min-width: 1024px) {
 
}
</style>
