import { defineStore } from 'pinia'
import { useUserStore } from './UserStore'
import { useLoaderStore } from './Loader'
import { useModalStore  } from './ModalStore'
import { usePaginationStore } from './PaginationStore'

export const useResultStore = defineStore('result', {
  state: () => ({
    parent: "",
    results:[], // результат проверки
    resultTicketUser: null, //результат для отправки на проверку
    info: "" ,
    statistiks: null, //для вывода статистики
    resultQuestions: null,
    formInfoVisible: false,
    formInfoParam: null,
    resultId: null,
  }),
  getters: {
    getResultTicketUser: (state) => state.resultTicketUser,
    getStatistiks: (state) => state.statistiks,
    getResultQuestions: (state) => state.resultQuestions,
    getFormInfoVisible: (state) => state.formInfoVisible,
    getFormInfoParam: (state) => state.formInfoParam
  },
  actions: {
    saveResultTicketUser( ticket ){
      this.resultTicketUser = ticket
      this.info = ticket.info
    },
    // отправка результата прохождения теста на сервер
    async setResultDb(){
      const user = useUserStore()
      let url = `${urlApi()}/api/test/handle`
        
      let params = {
        method: 'POST',
        body: JSON.stringify(this.resultTicketUser),
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
        },
      }
      if (user.getIsAutchUser) {
        url = `${urlApi()}/api/auth/test/handle`
        params.headers.Authorization = `Bearer ${user.token}`
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      console.log("result", result)
      this.results = result ? result.question : null
    },
    // получение данных статистики развернутый
    async getAuthAccountResultsDb() {
      const user = useUserStore()
      const pagination = usePaginationStore()
      const url= `${urlApi()}/api/auth/result`
      const params = {
        method: 'get',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${user.token}`
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      console.log("result", result)
      if (result){
        this.statistiks = result.results
        if (result.pagination){
          pagination.paginationsAll(result.pagination)
        }
      }
      
    },
    //получение вопросов результата по его id
    async getResultIdAnswersDb( {id} ){
      const url= `${urlApi()}/api/auth/result/${id}/answer`
      const params = {
        method: 'get',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: true, loading: false })
      console.log("result", )
      
      if (result){
        const questions = result
        this.resultQuestions = questions.map(question => {
          if (question.question){
            let questionItem = {...question.question}
            if (questionItem.type.title) {questionItem.type = questionItem.type.title}
    
            if (questionItem.type === 'input_one' ) {
              questionItem.result.true_answer = questionItem.variant[0].title
              if ( questionItem.variant[0].id === +questionItem.result.user_answer[0]){
                questionItem.result.user_answer[0] = questionItem.variant[0].title
              }
            } else {
              questionItem.result.true_answer = questionItem.result.true_answer.map(
                (item) =>{ return questionItem.variant.findIndex(variant => +variant.id === +item )}
              )
              questionItem.result.user_answer = questionItem.result.user_answer.map(
                (item) =>{ return questionItem.variant.findIndex(variant => +variant.id === +item )}
              )
            }
            return questionItem
          } else {
            return question
          }
        })
      }
    },
    saveFormInfo({param, visible}){
      this.formInfoVisible = visible
      this.formInfoParam = param
    },
    saveResultId({id}){
      this.resultId = id
    },
    async getResultsXmlDb({info}){

      const url= `${urlApi()}/api/auth/result/${this.resultId}/report`
      const params = {
        method: 'post',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: info
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      let xml = `data:application/xml,${result}`;
      let link = document.createElement("a");
      link.setAttribute("href", xml);
      link.setAttribute("download", Date.now()+"");
      link.click();
    }
  }


})
