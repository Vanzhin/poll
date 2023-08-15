<template>
  <Head>
    <Title> {{ tests.getTestTitle }} | {{ ticketTitle }} | Амулет Тест</Title>
    <Meta name="description" :content="description" />
          
    <Meta name="robots" :content="tests.getTestRobots"/>
    <link rel="canonical" :href="tests.getTestCanonical"/>
  </Head>
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
              <UiTimer
                v-if="timeTicket"
                :time="tests.getTestActiveTime" 
                @time-end="timerEnd"
              />
              
              <div class="ticket-title">
                {{ ticketModeTitle}}
              </div>
              <div class="ticket-number"
                v-if="ticketsStore.ticketSelect"
              >
                Билет № {{ ticketsStore.getTicketSelectTitle }}
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
  import { useModalStore } from '../stores/ModalStore'
  import { useCrumbsStore } from '../stores/CrumbsStore'
  import { useUserStore  } from '../stores/UserStore'
  import rndOptions from '../utils/rndOptions.js'

  const route = useRoute()
  const ticketRnd = ref(route.params.rnd)
  const parentId = ref(+route.params.id)
  const ticketNum = ref(+route.params.num)
  
  const user = useUserStore()
  const crumbs = useCrumbsStore()
  const tests = useTestsStore()
  const loader = useLoaderStore()
  const questions = useQuestionsStore()
  const ticketsStore = useTicketsStore()
  const modal = useModalStore()
  const ticketModeTitle = ref('')
  const timeTicket = ref(false)
 
  
  const regexp = new RegExp("rnd", 'i');
  if ( regexp.test(ticketRnd.value)){
    ticketModeTitle.value = rndOptions[ticketRnd.value]
    ticketsStore.saveTicketModeTitle(ticketModeTitle.value)
    ticketsStore.ticketSelectToChange(null)
    // ticketLink.value = `/test/${parentId.value}/${ticketRnd.value}`
    if(ticketRnd.value === "rnd20t"){timeTicket.value = true}
  } else {
    // ticketLink.value = `/test/${parentId.value}/ticket/${ticketRnd.value}`
  }
  const description = computed(() => {
    return `${tests.getTestTitle}. ${tests.getTestDescription}. ${user.getGlobalDescription}`
  })
  //title для head
  const ticketTitle = computed(() => {
    return `${ticketModeTitle.value !=='' ? ticketModeTitle.value: 'Билет № ' + ticketsStore.getTicketSelectTitle}`
  })
  
  function type(item) {
    return item.type.title ? item.type.title : item.type
  }

  function timerEnd(){
    console.dir(formTest)
    // this.timeEnd = true
    modal.setMessageUser({
      err: true,
      mes:'Время отведенное на прохождение теста - закончилось!'
    })
    onSubmit({target:formTest})
  }

  async function onSubmit(e){
    const question = Array.from(e.target).filter(inp => inp.id.slice(0, 1) === "a")
      .map(inp => { return {id:inp.name, answer: inp.value.split(',')}})
    const ticket = {
      question,
      info: {
        ticket: ticketsStore.ticketSelect ? ticketsStore.ticketSelect.id : ticketNum.value,
        mode: ticketRnd.value,
        test: parentId.value,
        ticketTitle: `Билет № ${ ticketsStore.ticketSelect ? ticketsStore.ticketSelect.title : '' }`,
        ticketModeTitle: ticketModeTitle.value,
      }
    } 
    const result = useResultStore() 
    await result.saveResultTicketUser(ticket)
    navigateTo(`/result`)
  }

  async function getCondition() {
    if (ticketRnd.value) {
      await questions.getQuestionsTestIdDb({id: parentId.value, rnd: ticketRnd.value })
    } else {
      await questions.getQuestionsIsTicketIdDb({id: ticketNum.value})
    }
    await crumbs.getTestCrumbsDB(parentId.value)
    console.log(ticketsStore.getTicketSelectTitle)
    crumbs.addIteration({
      title: `/${ticketModeTitle.value !=='' ? ticketModeTitle.value: 'Билет № ' + ticketsStore.getTicketSelectTitle}`,
      link: route.path,
      active: false,

    })
  }

  getCondition()
  
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