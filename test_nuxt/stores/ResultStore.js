import { defineStore } from 'pinia'
import { useUserStore } from './UserStore'
import { useLoaderStore } from './Loader'
export const useResultStore = defineStore('result', {
  state: () => ({
    parent: "",
    results:[], // результат проверки
    resultTicketUser: null, //результат для отправки на проверку
    info: "" 
  }),
  getters: {
    getResultTicketUser: (state) => state.resultTicketUser,
  },
  actions: {
    saveResultTicketUser( ticket){
      this.resultTicketUser = ticket
      this.info = ticket.info
    },
    // отправка результата прохождения теста на сервер
    async setResultDb(){
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      const user = useUserStore()
      // const token = user.token

      let url = `${urlApi}/api/test/handle`
        
      let params = {
        method: 'POST',
        body: JSON.stringify(this.resultTicketUser),
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json'
        },
      }
      if (user.getIsAutchUser) {
        url = `${urlApi}/api/auth/test/handle`
        params.headers.Authorization = `Bearer ${user.token}`
      }
      try{
        console.log('отправляю на проверку url-', url)
        const { data: result, pending, error } = await useAsyncData(
          () =>  $fetch( url, params )
        )
        this.pending = pending.value
        let timerId = setInterval(() => {
          console.log('получаю билеты pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
            console.log("result", result.value)
            this.results = result.value.question
            loader.setIsLoaderStatus(false)
          }
        }, 200);
      } catch (error) {
        console.log(error)
          // if (e.response.data.message === "Expired JWT Token") {
          //   await dispatch('getAuthRefresh')
          //   await dispatch('setResultDb', {userAuth})
          // } else {
          //   dispatch('setMessageError', e)
          // }
          
      }
    },

  }


})