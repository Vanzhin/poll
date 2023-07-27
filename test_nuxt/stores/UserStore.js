import { defineStore } from 'pinia'
import { useModalStore  } from './ModalStore'
  
export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    refresh_token: null,
    page:null,
    role: null
    
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets.length,
    getIsAutchUser: (state) => state.token ? true : false,
    getPageName: (state) => state.page,
    getUserAdmin: (state) => state.role === "ROLE_ADMIN"
  },
  actions: {
    saveTicketModeTitle(title) {
      console.log('saveTicketModeTitle-',title)
      this.ticketModeTitle = title
    },
    setTokenIsLocalStorage(){
      console.log('проверяю стор')
      this.token = localStorage.getItem('token') ?
        JSON.parse(localStorage.getItem('token')).token: ""
      this.refresh_token = localStorage.getItem('token') ?
        JSON.parse(localStorage.getItem('token')).refresh_token: ""
      this.role = localStorage.getItem('token') ?
        JSON.parse(atob(JSON.parse(localStorage.getItem('token')).token.split('.')[1])).roles[0]: ""
    },
    //вход на сайт с помощью учетной записи
    async setLogInUser(user){
      const modal = useModalStore()
      let url = `${urlApi}/api/login_check`
      try{
        console.log('прохожу авторизацию url-', url)
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch(url,
            {
              method: 'POST',
              body: user,
              headers: { 
                Accept: 'application/json', 
                'Content-Type': 'application/json'
              },
              // lazy: true,
            }
          )
        )
        if (error.value){
          modal.setMessageError(error.value.data)
          return false
        } else {
          this.token = result.value.token
          this.refresh_token = result.value.refresh_token
          const base64Url = result.value.token.split('.')[1]
          const base64 = JSON.parse(atob(base64Url))
          this.role = base64.roles[0]
          const parsed = JSON.stringify({
            token: result.value.token,
            refresh_token: result.value.refresh_token
          });
          localStorage.setItem('token', parsed);
          return true
        }
      } catch (error) {
        console.log(error)
        modal.setMessageError(error.value)
      }
    }
    
   
  }
})