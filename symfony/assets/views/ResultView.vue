<template>
  <div class="cont">
    <Loader
      v-if="loader"
    />
    <div
      v-else
    >
    <HeadersPage
        title="Тестирование Базовый курс"
        :subTitle="getTestTitleActive"
    />
    <div class="fon">
      <div class="container">
        <div class="wrapper">
          <div class="ticket-title">
            {{ getTicketTitle.ticketModeTitle }}
          </div>
          <div class="ticket-number"
            v-if="getTicketTitle.ticketTitle !== ''"
          >
            {{  getTicketTitle.ticketTitle }}
          </div>
          <div
            class="question-cont"
          >
          
            <ResultAutchView
              v-if="getIsAutchUser"
            />
            <ResultNoAutchView
              v-else
            />
          </div>
          </div> 
        </div>
      </div>
    </div> 
  </div>
</template>
<script>

import ResultAutchView from './ResultAutchView.vue'
import ResultNoAutchView from './ResultNoAutchView.vue'
import Loader from '../components/ui/LoaderView.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"
import HeadersPage from '../components/HeadersPage.vue'
export default {
  components: {
    ResultAutchView,
    ResultNoAutchView,
    Loader,
    HeadersPage
  },
  data() {
    return {
      loader: false
    }
  },
  computed:{
    ...mapGetters([
      "getIsAutchUser", 
      "getAutchUserToken", 
      "getTicketTitle",
      "getTestTitleActive",
      "getResultQuestions"
    ]),
    
  },
  methods: {
    ...mapActions([
      "getQuestionsDb", 
      "setResultDb", 
      "getAuthRefresh"]),
    ...mapMutations([
      "SET_RESULT_QUESTIONS"
    ])
  },
  async mounted(){
   
    
  },
  async created(){
    
    this.loader = true
    window.scroll(0, 0);
    if (!this.getResultQuestions){
      await this.setResultDb( {token: this.getAutchUserToken, userAuth:this.getIsAutchUser})
    }
    
    
    this.loader = false
  },
  unmounted(){
   this["SET_RESULT_QUESTIONS"](null)
  }

} 

</script>
<style lang="scss" scoped>
  .cont{
    min-height: 90vh;
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
  .question-cont{
    padding-bottom: 27px;
  }
</style>
