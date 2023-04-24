<template>
  <div class="cont">
    
    <div 
    v-if="!getIsLoaderStatus"
    >
      <HeadersPage
        title="Тестирование Базовый курс"
        :subTitle="testName"
      />
      
      
      
      <div class="fon">
        <div class="container">
          <div class="wrapper">
            <Timer
              v-if="timeTicket"
              :time="2" 
              @time-end="timerEnd"
            />
            <div class="ticket-title">
              {{ info.ticketModeTitle }}
            </div>
            <div class="ticket-number"
              v-if="info.ticketTitle !== ''"
            >
              {{ info.ticketTitle }}
            </div>
            <form @submit.prevent="onSubmit">
                <div v-for="(question, index ) in questions" 
                  :key="question.id"
                >
                  <TestQuestionRadio
                    v-if="type(question) === 'radio'"
                    :question="question"
                    :index="index"
                  />
                  <TestQuestionCheckbox
                    v-else-if="type(question) === 'checkbox'"
                    :question="question"
                    :index="index"
                  />
                  <TestQuestionInputOne
                    v-else-if="type(question) === 'input_one'"
                    :question="question"
                    :index="index"
                  />
                  <TestQuestionOrdered
                    v-else-if="type(question) === 'order'"
                    :question="question"
                    :index="index"
                  />
                  <TestQuestionConformity
                    v-else-if="type(question) === 'conformity'"
                    :question="question"
                    :index="index"
                  />
                </div>
                <div class="button-cont">
                  <button type="submit" class="button">Проверить</button>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TestQuestionRadio from '../components/TestQuestion/TestQuestionRadio.vue'
import TestQuestionCheckbox from '../components/TestQuestion/TestQuestionCheckbox.vue'
import TestQuestionOrdered from '../components/TestQuestion/TestQuestionOrdered.vue'
import TestQuestionInputOne from '../components/TestQuestion/TestQuestionInputOne.vue'
import TestQuestionConformity from '../components/TestQuestion/TestQuestionConformity.vue'
import Timer from '../components/ui/Timer.vue'
import Loader from '../components/ui/LoaderView.vue'
import HeadersPage from '../components/HeadersPage.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
    Loader,
    Timer,
    HeadersPage
  },
  data() {
    return {
      isLoader: false,
      timeTicket: false,
      timeEnd: false,
      ticketId: this.$route.params.id,
      ticketTitle:"",
      rnd: {
        rnd:"Случайный набор вопросов",
        rndb:"Случайный билет",
        rnd20:"Случайный набор из 20 вопросов",
        rnd20t:"Случайный набор из 20 вопросов на время",
        rndmax:"Режим тотальной проверки знаний"
      },
      info:{
        ticket:'',
        mode:'',
        test:'',
        ticketTitle:'',
        ticketModeTitle:'',
      }
    }
  },
  computed:{
    ...mapGetters([
      "getSlug", 
      "getRandomTicket", 
      "getSelectTicket", 
      "getTestId",
      "getTicket",
      "getIsLoaderStatus"
      
    ]),
    testName() {
      return this.$store.getters.getTestTitleActive
    },
    questions() {
      return this.$store.getters.getQuestions
    },
    
  },
  watch:{
      $route(newRout){
        // console.log("newParentId -", newRout)
        
      }
    },
   methods: {
    ...mapActions([
      "getQuestionsDb", 
      "saveResultTicketUser", 
      "setIsLoaderStatus",
      "setTicketInfo",
      "setIsLoaderStatus"
    ]),
    async onSubmit(e){
      const question = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
        .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
        
      const ticket = {
        question,
        info: this.info
      } 
      await this.saveResultTicketUser(ticket)
      this.$router.push({ path:'/result'})
    },
    timerEnd(){ //написать действия при окончании времени таймера
      this.timeEnd = true
    },
    type(item) {
      return item.type.title ? item.type.title : item.type
    }
  },
  mounted(){
    window.scroll(0, 0)
  },
  async created(){
    
    this.setIsLoaderStatus({status: true})
    if (this.ticketId === "rndb" ) { 
      this.info.ticketModeTitle = this.rnd[this.ticketId]
      this.ticketId = await this.getRandomTicket
    }
    
    const regexp = new RegExp("rnd", 'i');
   
    if ( regexp.test(this.ticketId)){
      this.info.ticketModeTitle = this.rnd[this.ticketId]
      this.info.mode = this.ticketId
    } else {
      this.info.ticketTitle = 'Билет № ' + this.getTicket.title
      this.info.ticket = +this.ticketId
    }

    this.info.test = this.getTestId
    this.setTicketInfo(this.info) 
    
    await this.getQuestionsDb({id: this.ticketId, slug: this.getSlug})
    if (this.$route.params.id === "rnd20t" ) {this.timeTicket = true}
    this.setIsLoaderStatus({status: false})
  }
} 

</script>
<style lang="scss" scoped>
.cont{
  min-height: 90vh;
}
  .block{
    background-color: rgb(207 207 199);
    padding: 10px ;
    
  }
  .title{
    
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
    }
  }
  .test{
    display: flex;
  }
  .ticket{
    &-title{
      padding-top: 27px;
      font-weight: 700;
      font-size: 20px;
      line-height: 40px;
      color: var(--color-Black_blue);
    }
    &-number{
      width: 148px;
      height: 36px;
      color: var(--color-blue);
      border: 1px solid var(--color-blue);
      border-radius: 6px;
      display: flex;
      justify-content: center;
      align-items: center;
      
    }
  }
  .button{
    width: 148px;
    height: 52px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--color-blue);
    border: 1px solid var(--color-blue);
    border-radius: 6px;
    color: var(--color-white);
    font-weight: 700;
    font-size: 18px;
    line-height: 24px;
    margin-top: 30px 0;
    &:hover{
      background-color: var(--color-white);
      color: var(--color-blue);
    }
    &-cont{
      padding-top: 30px;
      padding-bottom: 141px;
    }
  }
@media (min-width: 1024px) {
 
}
</style>
