import { defineStore } from 'pinia'
import { useLoaderStore } from './Loader'
import { useTicketsStore } from './TicketsStore'
import { useTestsStore } from './TestsStore'
export const useQuestionsStore = defineStore('questions', {
  state: () => ({
    
    questions:null,
    urlApi: useRuntimeConfig().public.urlApi,
    
  }),
  getters: {
    getQuestions: (state) => state.questions,
  },
  actions: {
    //запрос на получение вопросов по id теста и значению параметра rnd(
      // rnd20 - 20 вопросов, rnd20t - 20 вопросов на время, rnd - случайный набор вопросов
      //   rndmax - все вопросы теста, rndb - случайный билет
      // )
    async getQuestionsTestIdDb({id, rnd}) {
      console.log ("получаю вопросы  - ", rnd )
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = ''
      
      if (rnd === "rnd20" || rnd === "rnd20t") {
        console.log ("rnd  - ", rnd )
        url = `/api/test/${id}/question/20`
      } else if (rnd === "rnd"){
        console.log ("rnd  - ", rnd )
        const i = Math.floor(Math.random() * (30 - 1) )
        url = `/api/test/${id}/question/${i}`
      } else if (rnd === "rndmax"){
        console.log ("rnd  - ", rnd )
        url = `/api/test/${id}/question/${10000}`
      } else if (rnd === "rndb"){
        console.log ("rnd  - ", rnd )
        const ticketsStore = useTicketsStore()
        let tickets = ticketsStore.getTickets 
        if (!tickets) {
          console.log ("нет билетов - " )
          const r = await ticketsStore.getApiTicketsTestIdNoAuthDb({parentId:id})
          let timerIdr = setInterval(() => {
            console.log('r -', r.value)
            console.log('tickets.', ticketsStore.pending)
            if ( !r.value) {
              clearInterval(timerIdr)
              const i = Math.floor(Math.random() * (ticketsStore.getTickets.length - 1)) + 0
              this.getQuestionsIsTicketIdDb({id: ticketsStore.getTickets[i].id})
            }
          }, 200);
        } else {
          console.log ("есть билеты - " )
          console.log ("countTicket  - ",tickets )
          const i = Math.floor(Math.random() * (tickets.length - 1)) + 0
          console.log ("Ticket  - ", tickets[i].id)
          this.getQuestionsIsTicketIdDb({id: tickets[i].id})
        }
        console.log ("получил вопросы  - " )
        return
      } 
      url = urlApi + url
      console.log('url-',url )
      try {
        // this.userData = await api.post({ login, password })
        const { data: questions, pending, error } = await useAsyncData(
          () => $fetch(url,
            {
              lazy: true,
            })
        )
        console.log("questions", questions.value)
        const test = useTestsStore()
        test.testTitleSave(questions.value.test.title)
        test.testActiveSave(questions.value.test)
        this.questions = questions.value.questions
        loader.setIsLoaderStatus(false)
      } catch (error) {
        console.log(error)
      }
    },
    //запрос на получение вопросов по id билета
    async getQuestionsIsTicketIdDb({id}) {
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${this.urlApi}/api/ticket/${id}/question`
      console.log('url-',url )
      try {
        
        const { data: questions, pending, error } = await useFetch(() =>  url,
        {
          lazy: true,
        })
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
            console.log("вопросы билета", questions.value)
            const test = useTestsStore()
            const ticketsStore = useTicketsStore()
            test.testTitleSave(questions.value.test.title || questions.value.test)
            test.testActiveSave(questions.value.test)
            this.questions = questions.value.questions
            if (questions.value.ticket) {
              ticketsStore.ticketSelectToChange(questions.value.ticket)
              console.log("билет -", questions.value.ticket.title)
            }
             loader.setIsLoaderStatus(false)
          }
        }, 200);
      } catch (error) {
        console.log(error)
      }
    }
  }
})