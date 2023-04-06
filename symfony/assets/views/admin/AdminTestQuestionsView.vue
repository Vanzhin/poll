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
              <p>Всего вопросов в тесте: {{ getTotalItem }}</p>  
            </div>
          </div>
          <div class="btn-group " >
            <div class="btn btn-outline-primary btn-center"
              title="Добавить вопрос"
              @click.stop="addQuestion"
            >
              <i class="bi bi-plus create-plus"></i>
            </div>
            <div class="btn btn-outline-primary btn-center"
                  title="Импортировать вопросы из файла"
                  @click.stop="importQuestionsFile"
                >
                  <i class="bi bi-cloud-arrow-down"></i>
                </div>
            <div class="btn btn-outline-primary btn-center"
              :class="{published: questionsPublished.length > 0}"
              title="Утвердить все вопросы на странице"
              @click.stop="visibleConfirm(massege.approvePageQuestions)"
              v-if="questionsPublished.length > 0"
            >
              <i class="bi bi-file-check"></i>
            </div>
            <div class="btn btn-outline-primary btn-center"
              :class="{published: true}"
              title="Утвердить все вопросы теста"
              @click.stop="visibleConfirm(massege.approveTestQuestions)"
              v-if="noPublishedQuestionPriznak"
            >
              <i class="bi bi-file-arrow-down"></i>
            </div>
            <div class="btn btn-outline-primary btn-center"
              
              title="Скрыть все вопросы теста"
              @click.stop="visibleConfirm(massege.hideTestQuestions)"
              v-if="noPublishedQuestionPriznakAll"
            >
            <i class="bi bi-file-arrow-up"></i>
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
      "getRandomTicket", 
      "getSelectTicket",
      "getTest",
      "getActivePage",
      "getTotalItemsPage",
      "getTotalItem",
      "getGonfimAction",
      "getTestCountPublishedQuestion"
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
      console.log(publishedAt)
      return publishedAt
    },
    
  },
   methods: {
    ...mapActions([
      "getQuestionsTestIdDb", 
      "approveQuestionDb",
      "setConfirmMessage",
      "approveQuestionsAllDb", 
      "getTestIdDb",
      
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
    importQuestionsFile(){
      this.$router.push({name: 'adminImportId',  params: {id: this.testId}})
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
    async questionsAllPublished( ){
      this.isLoader = true
      await this.approveQuestionsAllDb({id: this.testId, param: true})
      await this.getQuestionsTestIdDb({id: this.testId})
      await this.getTestIdDb({id: this.testId})
      this.isLoader = false
    },
    async questionsAllNoPublished(){
      this.isLoader = true
      await this.approveQuestionsAllDb({id: this.testId, param: false})
      await this.getQuestionsTestIdDb({id: this.testId})
      await this.getTestIdDb({id: this.testId})
      this.isLoader = false
    },
  },
  async created(){
    await this.getTestIdDb({id: +this.$route.params.id})
    await this.getQuestionsTestIdDb({id: this.testId})
    this.isLoader = false
  },
 
} 

</script>
<style lang="scss" scoped>
  .block{
    background-color: rgb(207 207 199);
  }
  .title{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin:0 10px 5px 10px;
    & h2{
      font-size: 1.4rem;
      color: #697a3f;
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
    background-color: rgb(167, 72, 69);
    &:hover{
      background-color: rgb(168, 108, 106);
    }
  }
@media (min-width: 1024px) {
 
}
</style>
