import { defineStore } from 'pinia'
import { useLoaderStore } from './Loader'
import { useTicketsStore } from './TicketsStore'
import { useTestsStore } from './TestsStore'
import { useModalStore  } from './ModalStore'
import { usePaginationStore } from './PaginationStore'
export const useQuestionsStore = defineStore('questions', {
  state: () => ({
    questionsImportError: null,
    questions: null,
    urlApi: useRuntimeConfig().public.urlApi,
    questionsImportError: null,
    questionsSection: [],
    questionsTicket: [],
    question: null,
    questionsDb: [],
    
  }),
  getters: {
    getQuestions: (state) => state.questions,
    getQuestionsImportError: (state) => state.questionsImportError,
    getQuestion: (state)=>state.question,
    getQuestionsSection: (state) => state.questionsSection,
    getQuestionsTicket: (state) => state. questionsTicket,
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
      url = this.urlApi + url
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
    },
    //запрос на добавление  в БД а вопросов теста из файла - импорт
    async importQuestionsFileDb( {id, testFile} ){
      const data = new FormData(testFile);
      const url = `${this.urlApi}/api/admin/test/${id}/upload`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
        },
        body:  data
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      console.log('получил ответ - ', result)
      if ( result ) {
        const modal = useModalStore()
        modal.setMessage( result )
      }
    },
    //запрос на создание, редактирование если передается id - сохранение вопроса в БД
    async saveQuestionDb ({questionSend, id = null}){
      for(let [name, value] of questionSend) {
        console.dir(`${name} = ${value}`)
      } 
      let url = `${this.urlApi}/api/admin/question/${id ? id + '/edit_with_variant': 'create_with_variant'}`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
        },
        body:  questionSend
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
    },
     //запрос на получение вопросов теста по его id для админки
    async getAdminQuestionsTestIdDb( {id, page=null, limit=10}) {
      let url = `${this.urlApi}/api/admin/test/${id}/question?limit=${limit}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      }
      if (page) {url = url + `&page=${page}`}
      const result = await setUseAsyncFetch({ url, params, token: true })  
                 
      this.questions = result.question
      const pagination = usePaginationStore()
      pagination.paginations = []
      if (result && result.pagination ){ 
        pagination.paginationsAll(result.pagination)
      } 
     
    },
    //получение вороса по его id
    async getQuestionIdDb( {id}) {
      const url = `${this.urlApi}/api/admin/question/${id}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      }
      const result = await setUseAsyncFetch({ url, params, token: true }) 
      this.question =  result
    },
    //удаление вопроса из БД
    async deleteQuestionDb({ id, testId, page = null} ){
      const url = `${this.urlApi}/api/admin/question/${id}/delete`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      };
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getAdminQuestionsTestIdDb({id: testId, page})
    },
    //утверждение вопроса
    async approveQuestionDb( {questionSend} ){
      const url = `${this.urlApi}/api/admin/question/publish`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({"questionIds": questionSend})
      };
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      
    },
    //утверждение или скрытие всех вопросов теста по его id
    async approveQuestionsAllDb( {id, param} ){
      const url = `${this.urlApi}/api/admin/question/publish/test/${id}?publish=${param}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      };
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
    },
     //запрос на получение вопросов секции по id для админки
     async getQuestionsSectionIdDb( {id, limit = 10000}) {
      let url = `${this.urlApi}/api/admin/question/section/${id}?limit=${limit}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      }
      const result = await setUseAsyncFetch({ url, params, token: true })  
                 
      this.questionsSection = result.question
    },
     //запрос на получение вопросов билетов по id для админки
     async getQuestionsTicketIdDb( {id, limit = 10000}) {
      let url = `${this.urlApi}/api/ticket/${id}/question`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      }
      const result = await setUseAsyncFetch({ url, params })  
                 
      this.questionsTicket = result.questions
      
      if (result.ticket) {
        const ticketsStore = useTicketsStore()
        ticketsStore.ticketSelectToChange(result.ticket)
        console.log("билет -", result.ticket.title)
      }
    },
     
  }
})
