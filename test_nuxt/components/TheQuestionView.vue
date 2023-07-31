<template>
  <div >
   
    <UiLoaderView
      v-if="loader.isLoader"
    />
    <div class="cont"
      v-else
    >
      
      <div>
        <TheHeaderVsPage
          :title="tests.getTestTitle"
          subTitle=""
        />
        
        
        
        <div class="fon">
          <div class="container">
            <div class="wrapper">
              <!-- <Timer
                v-if="timeTicket"
                :time="20" 
                @time-end="timerEnd"
              /> -->
              <div class="ticket-title">
                {{ ticketModeTitle}}
              </div>
              <div class="ticket-number"
                v-if="ticketsStore.ticketSelect"
              >
                Билет № {{ ticketsStore.ticketSelect.title }}
              </div>
              <form @submit.prevent="onSubmit"
                id="formTest"
                v-if="questions.questions"
              >
                  <div v-for="(question, index ) in questions.questions" 
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

  </div>
</template>

<script setup>
  import { storeToRefs } from 'pinia'
  import { useTestsStore } from '../stores/TestsStore'
  import { useLoaderStore } from '../stores/Loader'
  import { useQuestionsStore } from '../stores/QuestionStore'
  import { useTicketsStore  } from '../stores/TicketsStore'
  import { useResultStore  } from '../stores/ResultStore'
  import rndOptions from '../utils/rndOptions.js'
  const route = useRoute()
  const ticketRnd = ref(route.params.rnd)
  const parentId = ref(+route.params.id)
  const ticketNum = ref(+route.params.num)
 
  const tests = useTestsStore()
  const loader = useLoaderStore()
  const questions = useQuestionsStore()
  const ticketsStore = useTicketsStore()
    
  const ticketModeTitle = ref('')
  
  const regexp = new RegExp("rnd", 'i');
   
  if ( regexp.test(ticketRnd.value)){
    ticketModeTitle.value = rndOptions[ticketRnd.value]
    ticketsStore.saveTicketModeTitle(ticketModeTitle.value)
  } 

  if (ticketRnd.value) {
    questions.getQuestionsTestIdDb({id: parentId.value, rnd: ticketRnd.value })
  } else {
    questions.getQuestionsIsTicketIdDb({id: ticketNum.value})
  }
  
  useSeoMeta({
    title: `Амулет Тест | Тест - ${parentId.value} ${ticketModeTitle.value !=='' ? ' | '+ ticketModeTitle.value: ''} ${ticketsStore.ticketSelect ? ' | Билет № ' + ticketsStore.ticketSelect.title: ''}`,
    ogTitle: 'Амулет Тест | ',
    description: 'Сервис онлайн тестирования по вопросам охраны труда, промышленной безопасности (тесты Ростехнадзора), электробезопасности, тепловые установки. Онлайн подготовка и проверка знаний.',
    ogDescription: 'This is my amazing site, let me tell you all about it.',
    ogImage: 'https://example.com/image.png',
    twitterCard: 'summary_large_image',
  })

  function type(item) {
    return item.type.title ? item.type.title : item.type
  }

  async function onSubmit(e){
   
    const question = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
      .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
      
    const ticket = {
      question,
      info: {
        ticket: ticketsStore.ticketSelect.id || '',
        mode: ticketRnd.value,
        test: parentId.value,
        ticketTitle: `Билет № ${ ticketsStore.ticketSelect.title }`,
        ticketModeTitle: ticketModeTitle.value,
      }
    } 
    
    const result = useResultStore() 
    await result.saveResultTicketUser(ticket)
    navigateTo(`/result`)
    
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
      &>button{
        @media (max-width: 480px) {
          margin: 0 auto;
        }
      }
    }
  }
@media (min-width: 1024px) {
 
}
</style>