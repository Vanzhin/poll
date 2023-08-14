<template>
  <Head>
    <Title>Результат тестирования | Амулет Тест</Title>
    <Meta name="description" :content="getGlobalDescription" />
          
    <Meta name="robots" content="noindex"/>
    <link rel="canonical" href=""/>
  </Head>
  <div class="cont">
    <UiLoaderView
      v-if="loader.isLoader"
    />
    <div
      v-else
    >
      <TheHeaderVsPage
          :title="tests.getTestTitle"
          subTitle=""
      />
      <div class="fon">
        <div class="container">
          <div class="wrapper">
            <div class="ticket-title">
              {{ ticketsStore.ticketModeTitle }}
            </div>
            <div class="ticket-number"
              v-if="ticketsStore.ticketSelect"
            >
              Билет № {{ ticketsStore.ticketSelect.title }}
            </div>
            <div
              class="question-cont"
              v-if="result.resultTicketUser"
            >
            
              <ResultViewAutch
                v-if="autch"
              />
              <ResultViewNoAutch
                v-else
              />
            </div>
            <div
              class="question-cont"
              v-else
            >
             У Вас нет результатов для проверки. 
             Выберите тест. 
             Пройдите тестирование.
              
            </div>
          </div> 
        </div>
      </div>
    </div> 
  </div>
</template>
<script setup>
  import { storeToRefs } from 'pinia'
  import { useTestsStore } from '../../stores/TestsStore'
  import { useLoaderStore } from '../../stores/Loader'
  import { useQuestionsStore } from '../../stores/QuestionStore'
  import { useTicketsStore  } from '../../stores/TicketsStore'
  import { useResultStore  } from '../../stores/ResultStore'
  import { useUserStore  } from '../../stores/UserStore'
  import { useCrumbsStore } from '../../stores/CrumbsStore'

  const loader = useLoaderStore()
  const tests = useTestsStore()
  const ticketsStore = useTicketsStore()
  const crumbs = useCrumbsStore()

  // const user = useUserStore()
  const autch = ref(false)
  const result = useResultStore()
  const getGlobalDescription = ref('')
  onMounted(() => {
    console.log('я на клиенте результат')
    const user = useUserStore()
    autch.value = user.getIsAutchUser
    getGlobalDescription.value = `Результаты прохождения тестирования. ${user.getGlobalDescription}`
  })
  if (result.resultTicketUser) {
    result.setResultDb()
    crumbs.addIteration({
        title:`/результат тестирования`,
        link: `/`,
        active: false
      }) 
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
