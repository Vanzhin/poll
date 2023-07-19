<template>
  <Loader
    v-if="isLoader"
  />
  <div class="block"
    v-else
  >
    <div class="container">
      <div class="row">
        <div class="title">
          <div>
            <div class="test">
              <h2>Всего сотрудников в компании: {{ getTotalUserCompany }}</h2>  
            </div>
          </div>
          <div class="button-group" >
            <div class="button-item"
              title="Добавить сотрудника"
              @click.stop="addQuestion"
            >
              <svg width="36" height="37" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M30.4023 18.8252C30.4023 25.9054 24.6627 31.645 17.5825 31.645C10.5023 31.645 4.7627 25.9054 4.7627 18.8252C4.7627 11.745 10.5023 6.00537 17.5825 6.00537C24.6627 6.00537 30.4023 11.745 30.4023 18.8252ZM31.9023 18.8252C31.9023 26.7338 25.4911 33.145 17.5825 33.145C9.6739 33.145 3.2627 26.7338 3.2627 18.8252C3.2627 10.9166 9.6739 4.50537 17.5825 4.50537C25.4911 4.50537 31.9023 10.9166 31.9023 18.8252ZM24.7419 19.899H10.4221V17.751H24.7419V19.899Z" fill="#269EB7"/>
                <path d="M16.5088 11.6646L16.5088 25.9844L18.6567 25.9844L18.6567 11.6646L16.5088 11.6646Z" fill="#269EB7"/>
              </svg>
            </div>
            <div class="button-item"
              title="Импортировать список сотрудников из файла"
              @click.stop="importQuestionsFile"
            >
                  <svg width="24" height="25" viewBox="0 0 24 25" fill="none" >
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.42437 11.2804L6.598 10.4158C7.0572 8.12913 9.07919 6.40723 11.5 6.40723C13.5853 6.40723 15.3758 7.68415 16.1257 9.50449L16.4676 10.3345L17.3606 10.4255C19.1235 10.605 20.5 12.0963 20.5 13.9072C20.5 15.8402 18.933 17.4072 17 17.4072V18.9072C19.7614 18.9072 22 16.6687 22 13.9072C22 11.3189 20.0332 9.18986 17.5126 8.93318C16.5393 6.57045 14.2139 4.90723 11.5 4.90723C8.35069 4.90723 5.7245 7.14694 5.12736 10.1204C3.31336 10.7008 2 12.4006 2 14.4072C2 16.8925 4.01472 18.9072 6.5 18.9072H7V17.4072H6.5C4.84315 17.4072 3.5 16.0641 3.5 14.4072C3.5 13.0718 4.37365 11.9365 5.58444 11.5491L6.42437 11.2804ZM13.9455 17.1306L12.749 18.3368L12.749 12.9072H11.249L11.249 18.337L10.0524 17.1306L8.99603 18.1956L11.4707 20.6905C11.7624 20.9845 12.2354 20.9845 12.5271 20.6905L15.0018 18.1956L13.9455 17.1306Z" fill="#269EB7"/>
                  </svg>
                </div>
           
            
           
          </div>
        </div> 
      </div>
    </div>
    
    <div class="container">
      
        <div class="colums-blok"
          v-if="questions"
        >
          <div class="question-blok">
            <div v-for="(question, index ) in questions" 
              :key="question.id"
            >
              <ItemQuestion
                :question="question"
                :index="numQuestion(index)"
              />
            </div>
          </div>
          <Pagination
            type="getQuestionsTestIdDb"
          />
        </div>
        <div
          v-else
        >
          <p>В тесте нет вопросов. Вы можете их создать.</p>
        </div>
     
    </div>
  </div>
</template>

<script>

import ItemQuestion from '../../components/Admin/ItemQuestion.vue'
import Pagination from "../../components/Pagination.vue"
import Loader from '../../components/ui/LoaderView.vue'
import { mapGetters, mapActions, mapMutations} from "vuex"

export default {
  components: {
    ItemQuestion,
    Loader,
    Pagination,
  },
  data() {
    return {
      isLoader: true,
      testId: this.$route.params.id,
      testTitle:"",
      massege:{
        approveTestQuestions:{
          massege:`Вы хотите утвердить все вопросы теста. <br>
          Подтвердите свои действия.`,
          func:'questionsAllPublished'
        },
        approvePageQuestions:{
          massege:`Вы хотите утвердить все вопросы на странице. <br>
          Подтвердите свои действия.`,
          func:'approveQuestions'
        },
        hideTestQuestions:{
          massege:`Вы хотите скрыть все вопросы теста. <br>
          Подтвердите свои действия.`,
          func:'questionsAllNoPublished'
        },
      }
    }
  },
  computed:{
    ...mapGetters([
      "getSlug", 
      "getTest",
      "getActivePage",
      "getTotalItemsPage",
      "getTotalUserCompany",
      "getGonfimAction",
      
    ]),
    noPublishedQuestionPriznak(){ // неопубликованные есть 
      return this.getTest.questionUnPublishedCount > 0
    },
    noPublishedQuestionPriznakAll(){ // все не опубликованны 
      return !(this.getTest.questionUnPublishedCount === this.getTest.questionCount)
    },
    testName () {
      return this.$store.getters.getTestTitleActive
    },
    questions () {
      return this.$store.getters.getQuestions
    },
    questionsPublished(){
      let publishedAt = []
      if (!this.questions) { return []}
      this.questions.forEach((question)=>{
        if (!question.publishedAt) {
          publishedAt.push( question.id)}
      })
      return publishedAt
    },
    
  },
   methods: {
    ...mapActions([
      
      "setConfirmMessage",
      
      
    ]),
    numQuestion(index){
      return index + (this.getActivePage - 1) * this.getTotalItemsPage
    },
    addQuestion(){
      this.$router.push({
        name: 'adminQuestionsCreate', 
        params: {
          testId: this.testId,
          questionId: 0,
          operation: "create"
        }
      })
    },
    async approveQuestions(){
      this.isLoader = true
      await this.approveQuestionDb({questionSend: this.questionsPublished})
      await this.getQuestionsTestIdDb({id: this.testId})
      await this.getTestIdDb({id: this.testId})
      this.isLoader = false
    },
    
    visibleConfirm(activity){
      this.setConfirmMessage(activity.massege)
      let timerId = setInterval(() => {
        if (this.getGonfimAction) {
          clearInterval(timerId)
          if (this.getGonfimAction === "yes" ){this[activity.func]()}
        }
      }, 200);
    },
    
  },
  async created(){
    // if () {}
    // await this.getTestIdDb({id: +this.$route.params.id})
    
    this.isLoader = false
  },
 
} 

</script>
<style lang="scss" scoped>
  .title{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin:0 10px 5px 10px;
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
    p{
      margin: 0;
    }
  }
  [class*="col-"] {
  padding-top: 7px;
  padding-right: 7px;
  padding-left: 7px;
  margin-bottom: 10px;
  }
  .colums-blok{
    display: flex;
    flex-direction: column;
  }
  .question-blok{
    flex: 37em;
    overflow-y:auto;
    padding: 0 5px;
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
  .published{
    & svg{fill:#FF3F3F }
    
  }
@media (min-width: 1024px) {
 
}
</style>
