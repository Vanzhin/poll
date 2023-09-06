<template>
  <div class="block">
    <div class="container">
      <div class="row">
        <div class="title">
          <div>
            <div class="test">
              <h2>Всего вопросов в тесте: {{ tests.getTestQuestionCount }}</h2>  
            </div>
          </div>
          <div class="button-group" >
            <div class="button-item"
              title="Добавить вопрос"
              @click.stop="addQuestion"
            >
              <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.001 10.4077C19.001 15.1021 15.1954 18.9077 10.501 18.9077C5.80656 18.9077 2.00098 15.1021 2.00098 10.4077C2.00098 5.71329 5.80656 1.90771 10.501 1.90771C15.1954 1.90771 19.001 5.71329 19.001 10.4077ZM20.501 10.4077C20.501 15.9306 16.0238 20.4077 10.501 20.4077C4.97813 20.4077 0.500977 15.9306 0.500977 10.4077C0.500977 4.88487 4.97813 0.407715 10.501 0.407715C16.0238 0.407715 20.501 4.88487 20.501 10.4077ZM11.2512 12.4957H9.48718V12.2717C9.48718 11.4177 9.76718 10.8857 10.6912 10.1437C11.6152 9.40171 11.9792 8.91171 11.9792 8.19771C11.9792 7.46971 11.3772 7.00771 10.5232 7.00771C9.66918 7.00771 8.94118 7.52571 8.94118 8.39371C8.94118 8.57571 8.98318 8.77171 9.03918 8.91171L7.42918 9.24771C7.30318 8.98171 7.23318 8.65971 7.23318 8.23971C7.23318 6.83971 8.47918 5.41171 10.4812 5.41171C12.5112 5.41171 13.7152 6.48971 13.7152 8.07171C13.7152 9.19171 13.3092 9.87772 11.9092 11.0817C11.4052 11.5157 11.2512 11.8797 11.2512 12.4957ZM11.2932 15.4077H9.44518V13.4757H11.2932V15.4077Z" fill="#269EB7"/>
              </svg>
            </div>
            <div class="button-item"
              title="Импортировать вопросы из файла"
              @click.stop="importQuestionsFile"
            >
                  <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.42437 11.2804L6.598 10.4158C7.0572 8.12913 9.07919 6.40723 11.5 6.40723C13.5853 6.40723 15.3758 7.68415 16.1257 9.50449L16.4676 10.3345L17.3606 10.4255C19.1235 10.605 20.5 12.0963 20.5 13.9072C20.5 15.8402 18.933 17.4072 17 17.4072V18.9072C19.7614 18.9072 22 16.6687 22 13.9072C22 11.3189 20.0332 9.18986 17.5126 8.93318C16.5393 6.57045 14.2139 4.90723 11.5 4.90723C8.35069 4.90723 5.7245 7.14694 5.12736 10.1204C3.31336 10.7008 2 12.4006 2 14.4072C2 16.8925 4.01472 18.9072 6.5 18.9072H7V17.4072H6.5C4.84315 17.4072 3.5 16.0641 3.5 14.4072C3.5 13.0718 4.37365 11.9365 5.58444 11.5491L6.42437 11.2804ZM13.9455 17.1306L12.749 18.3368L12.749 12.9072H11.249L11.249 18.337L10.0524 17.1306L8.99603 18.1956L11.4707 20.6905C11.7624 20.9845 12.2354 20.9845 12.5271 20.6905L15.0018 18.1956L13.9455 17.1306Z" fill="#269EB7"/>
                  </svg>
                </div>
            <div class="button-item"
              :class="{published: questionsPublished.length > 0}"
              title="Утвердить все вопросы на странице"
              @click.stop="visibleConfirm(massege.approvePageQuestions)"
              v-if="questionsPublished.length > 0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-check" viewBox="0 0 16 16">
                <path d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
              </svg>
            </div>
            <div class="button-item"
              :class="{published: true}"
              title="Утвердить все вопросы теста"
              @click.stop="visibleConfirm(massege.approveTestQuestions)"
              v-if="noPublishedQuestionPriznak"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-arrow-down" viewBox="0 0 16 16">
                <path d="M8 5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5A.5.5 0 0 1 8 5z"/>
                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
              </svg>
            </div>
            <div class="button-item"
              
              title="Скрыть все вопросы теста"
              @click.stop="visibleConfirm(massege.hideTestQuestions)"
              v-if="noPublishedQuestionPriznakAll"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-arrow-up" viewBox="0 0 16 16">
                <path d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5z"/>
                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
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
              <AdminItemQuestion
                :question="question"
                :index="numQuestion(index)"
              />
            </div>
          </div>
          <ThePagination />
        </div>
        <div
          v-else
        >
          <p>В тесте нет вопросов. Вы можете их создать.</p>
        </div>
     
    </div>
  </div>
</template>
<script setup>
  import { useTestsStore  } from '@/stores/TestsStore'
  import { useQuestionsStore } from '@/stores/QuestionStore'
  import { useConfirmStore } from '../../../stores/ConfirmStore'
  import { usePaginationStore } from '@/stores/PaginationStore'
  
  const pagination = usePaginationStore()
  const tests = useTestsStore()
  const questionsSt = useQuestionsStore()
  const confirm = useConfirmStore()
  
  const route = useRoute()
  const testId = route.params.id
      
  const testTitle = ref ("")
       
  const  noPublishedQuestionPriznak = computed(()=>{ // неопубликованные есть 
    if (tests.getTest){
      return tests.getTest.questionUnPublishedCount > 0
    } else {
      return false
    }
  })
  const noPublishedQuestionPriznakAll = computed(()=>{ // все не опубликованны 
    if (tests.getTest){
    return !(tests.getTest.questionUnPublishedCount === tests.getTest.questionCount)
    }else {
      return false
    }
  })
  
  const  questions = computed(()=>{
    return questionsSt.getQuestions
  })
  const questionsPublished = computed(()=>{
    let publishedAt = []
    if (!questions.value) { return []}
    questions.value.forEach((question)=>{
      if (!question.publishedAt) {
        publishedAt.push( question.id)}
    })
    return publishedAt
  })
   
  function numQuestion(index){
    return index + (pagination.getActivePage - 1) * pagination.getTotalItemsPage
  }

  function addQuestion(){
    navigateTo(`/admin/categorys/${route.params.id}/test/${route.params.testId}/question/create`)
  }

  function importQuestionsFile(){
    navigateTo(`/admin/categorys/${+route.params.id}/test/${+route.params.testId}/questions/import`)
  }

  const approveQuestions = async function(){
    await questionsSt.approveQuestionDb({questionSend: questionsPublished.value})
    await questionsSt.getAdminQuestionsTestIdDb({id: +route.params.testId})
    await tests.getTestIdDb({id: +route.params.testId})
  }

  const questionsAllPublished = async function() {
    await questionsSt.approveQuestionsAllDb({id: +route.params.testId, param: true})
    await questionsSt.getAdminQuestionsTestIdDb({id: +route.params.testId})
    await tests.getTestIdDb({id: +route.params.testId})
  }

  const questionsAllNoPublished = async function() {
    await questionsSt.approveQuestionsAllDb({id: +route.params.testId, param: false})
    await questionsSt.getAdminQuestionsTestIdDb({id: +route.params.testId})
    await tests.getTestIdDb({id: +route.params.testId})
  }

  const massege = {
    approveTestQuestions:{
      massege:`Вы хотите утвердить все вопросы теста. <br>
      Подтвердите свои действия.`,
      func: questionsAllPublished
    },
    approvePageQuestions:{
      massege:`Вы хотите утвердить все вопросы на странице. <br>
      Подтвердите свои действия.`,
      func: approveQuestions
    },
    hideTestQuestions:{
      massege:`Вы хотите скрыть все вопросы теста. <br>
      Подтвердите свои действия.`,
      func: questionsAllNoPublished
    },
  }
  function visibleConfirm(activity){
    console.log("activity - ", activity)
    confirm.setConfirmMessage(activity.massege)
    let timerId = setInterval(() => {
      if (confirm.getConfirmAction) {
        clearInterval(timerId)
        if (confirm.getConfirmAction === "yes" ){
          console.log("activity - ", activity.func)
          activity.func()
        }
      }
    }, 200);
  }

  onMounted (async() => {
    await tests.getTestIdDb({id: +route.params.testId})
    await questionsSt.getAdminQuestionsTestIdDb({id: +route.params.testId})
  })
  


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