import { defineStore } from 'pinia'
import { useLoaderStore } from './Loader'
import { useTicketsStore } from './TicketsStore'
import { useTestsStore } from './TestsStore'
export const useQuestionsStore = defineStore('questions', {
  state: () => ({
    
    questions:[],
    
  }),
  getters: {
    getQuestions: (state) => state.questions,
  },
  actions: {
    //запрос на получение вопросов по id теста
    async getQuestionsDb({id, rnd}) {
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = ''
      
      if (rnd === "rnd20" || rnd === "rnd20t") {
        url = `/api/test/${id}/question/20`
      } else if (rnd === "rnd"){
        const i = Math.floor(Math.random() * (30 - 1) )
        url = `/api/test/${id}/question/${i}`
      } else if (rnd === "rndmax"){
        // const i = await dispatch("getCountQuestionsTest")
      
        url = `/api/test/${id}/question/${10000}`
      } else if (id === "rndb"){
        const ticketsStore = useTicketsStore()
        const tickets = ticketsStore.getTickets
        // console.log ("countTicket  - ",tickets )
  
        const i = Math.floor(Math.random() * (tickets.length - 1)) + 1
        
        // console.log ("Ticket  - ", i )
        url = `/api/ticket/${ticketsStore[i].id}/question`
      } else {
        url = `/api/ticket/${id}/question`
      }
      console.log('url-',url=urlApi + url )
      try {
        // this.userData = await api.post({ login, password })
        const { data: questions, pending, error } = await useFetch(() =>  url,
        {
          lazy: true,
        })
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
            console.log("questions", questions.value)
            const test = useTestsStore()
            test.testTitleSave(questions.value.test)
            this.questions = questions.value.questions
           
            loader.setIsLoaderStatus(false)
          }
        }, 200);

      } catch (error) {
        console.log(error)
      }
    },
    //запрос на получение вопросов по id билета
    async getQuestionsIsTicketIdDb({id}) {
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${urlApi}/api/ticket/${id}/question`
      
      
      console.log('url-',url )
      try {
        // this.userData = await api.post({ login, password })
        const { data: questions, pending, error } = await useFetch(() =>  url,
        {
          lazy: true,
        })
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
            console.log("questions", questions.value)
            const test = useTestsStore()
            const ticketsStore = useTicketsStore()
            ticketsStore.ticketSelectToChange(questions.value.ticket)
            test.testTitleSave(questions.value.test)
            this.questions = questions.value.questions
           
            loader.setIsLoaderStatus(false)
          }
        }, 200);

      } catch (error) {
        console.log(error)
      }
    }
  }
})