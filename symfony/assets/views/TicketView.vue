<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="title">
      <Timer
        v-if="timeTicket"
        :time="20" 
        @time-end="timerEnd"
      />
      <h2> {{ testName }}</h2>
      <div class="test">
        <p> {{ ticketTitle }}</p>  
      </div>
    </div>
    
    <div class="container">
      <div class="row">
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
          <button type="submit" class="button">Проверить</button>
        </form>
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
import { mapGetters, mapActions, mapMutations} from "vuex"
export default {
  components: {
    TestQuestionRadio,
    TestQuestionCheckbox,
    TestQuestionOrdered,
    TestQuestionInputOne,
    TestQuestionConformity,
    Loader,
    Timer
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
        rnd20:"Случайный набор из 20 вопросов",
        rnd20t:"Случайный набор из 20 вопросов на время"
      },
      info:{}
    }
  },
  computed:{
    ...mapGetters([
      "getSlug", 
      "getRandomTicket", 
      "getSelectTicket", 
      "getTestId",
      
    ]),
    testName() {
      return this.$store.getters.getTestTitleActive
    },
    questions() {
      return this.$store.getters.getQuestions
    },
    
  },
   methods: {
    ...mapActions([
      "getQuestionsDb", 
      "saveResultTicketUser", 
      "setIsLoaderStatus",
      "setTicketTitle"
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
  async created(){
    this.isLoader = true
    if (this.ticketId === "rndb" ) { 
      this.ticketId = await this.getRandomTicket
      console.log("запрос случайного билета ", this.ticketId)
    }
    console.log("this.ticketId - ", this.ticketId)
    
    const regexp = new RegExp("rnd", 'i');
               
    console.log(" regexp.test(this.ticketId) - ",  regexp.test(this.ticketId))
    if ( regexp.test(this.ticketId)){
      this.ticketTitle =this.rnd[this.ticketId]
      this.info.mode = this.ticketId
      this.setTicketTitle(this.rnd[this.ticketId])
    } else {
      this.ticketTitle ='Билет № ' + this.getSelectTicket(+this.ticketId).title
      this.info.ticket = +this.ticketId
      this.setTicketTitle(+this.ticketId) 
    }
    this.info.test= this.getTestId
    
    await this.getQuestionsDb({id: this.ticketId, slug: this.getSlug})
    if (this.$route.params.id === "rnd20t" ) {this.timeTicket = true}
    this.isLoader = false
  }
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
@media (min-width: 1024px) {
 
}
</style>
