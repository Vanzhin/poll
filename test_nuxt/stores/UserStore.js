import { defineStore } from 'pinia'
import { useModalStore  } from './ModalStore'
import { useLoaderStore } from './Loader'  

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    refresh_token: null,
    page: null,
    role: null,
    logoutLinkDate: {},
    
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets.length,
    getIsAutchUser: (state) => state.token ? true : false,
    getPageName: (state) => state.page,
    getUserAdmin: (state) => state.role === "ROLE_ADMIN"
  },
  actions: {
    savePage(page) {
      console.log('savePage-', page)
      this.page = page
    },
    async setTokenIsLocalStorage(){
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
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
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
          loader.setIsLoaderStatus(false)
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
          loader.setIsLoaderStatus(false)
          return true
        }
      } catch (error) {
        console.log(error)
        modal.setMessageError(error.value)
        loader.setIsLoaderStatus(false)
      }
    },
    //регистрация на сайте
    async setRegistrationUser( user ) {
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      const data = JSON.stringify(user)
      let url = `${urlApi}/api/register`
      const modal = useModalStore()
      try {
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch(url, {
          method: 'POST',
          maxBodyLength: Infinity,
          headers: { 
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: data 
        }))
        loader.setIsLoaderStatus(false)
        if (error.value){
          console.log("setRegistrationUser error- ",error.value )
          modal.setMessageError(error.value.data)
          return true
        } else {
          console.log("setRegistrationUser - ", result )
          return false
        }
       
      } catch (e) {
        console.log("setRegistrationUser e - ", e )
        modal.setMessageError(e)
        return true
      }
    },
    // выход с сайта
    async getLogOutUser() {
      const data = JSON.stringify({"refresh_token": this.refresh_token} )
      try{
        let url = `${urlApi}/api/token/invalidate`
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch(url,
            {
              method: 'POST',
              headers: { 
                Accept: 'application/json', 
                'Content-Type': 'application/json'
              },
              body: data  
            }
          )
        )
        console.log(result)    
        this.setDeleteUserToken()
      } catch (e) {
        console.log(e)    
        if (e.response.data.message === "No refresh_token found." ||
        e.response.data.message === "JWT Refresh Token Not Found"
        ) {
          this.setDeleteUserToken()
        }
      }
    },
    setDeleteUserToken(){
      this.token = ''
      this.refresh_token = ''
      localStorage.removeItem('token');
    },
    // вход по ссылке
    async getLoginByLinkUser( email ) {
      let url = `${urlApi}/api/login_link/${email}`
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      const modal = useModalStore()
      try {
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch(url,
        {
          method: 'get',
          headers: { 
            'Accept': 'application/json',
            // 'Content-Type': 'application/json'
          },
        }
        ))
        
        if (error.value){
          console.log("getLoginByLinkUser error-", error.value)
          modal.setMessageError(error.value.data)
          this.logoutLinkDate = {data:error.value, send: false }

        } else {
          console.log("getLoginByLinkUser result -", result)
        this.logoutLinkDate = {
          message: data.data.message,
          url: data.data.link ? data.data.link.url : '',
          send: true 
        }}
        loader.setIsLoaderStatus(false)
      } catch (e) {
        
        
      }
    },
    //получение данных пользователя из БД
    async getAutсhUserProfileDb() {
      console.log("получаю данные пользователя")
      const modal = useModalStore()
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${urlApi}/api/auth/user`
      
      try {
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch( url, {
            method: 'POST',
            headers: { 
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              Authorization: `Bearer ${this.token}`
            },
          })
        )

        if (error.value) {
          console.log(error.value.data)
          if (error.value.data){
            if (error.value.data.message === "Expired JWT Token"||
              error.value.data.message === "Invalid JWT Token") 
            {
              this.getAuthRefresh() //обновление токена
              this.getAutсhUserProfileDb()
            }
          } else {
            modal.setMessageError(error.value)
          }
        } else{
          console.log('result.value -',result.value)
          this.profile = result.value
        }
        loader.setIsLoaderStatus(false)
      } catch (e) {
        
        console.log(e)
        // modal.setMessageError(e.value)
        loader.setIsLoaderStatus(false)
      }
    },
    // повторное получение токена
    async getAuthRefresh({commit, state }, refresh_token) {
      const modal = useModalStore()
      let body = ''
      if (refresh_token) {
        body = JSON.stringify({"refresh_token": refresh_token})
      } else { data = JSON.stringify({"refresh_token": this.refresh_token})}
      let url = `${urlApi}/api/token/refresh`
      try {
        const { data: result, pending, error } = await useAsyncData(
          () => $fetch(url, {
            method: 'post',
            headers: { 
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: body 
          }
        ))
        if (error.value) {
          console.log(e)
          if (error.value.data.message === "No refresh_token found." ||
            error.value.data.message === "JWT Refresh Token Not Found" ||
            error.value.data.message === "Invalid JWT Refresh Token"
          ) {
            this.token = null
          }
          modal.setMessageError(error.value.data)
        } else {
          console.log('обновление токена - ', result.value)
          this.token = result.value
        }
      } catch (e) {
        console.log(e)
        modal.setMessageError(e.value)
      }
    },
  }
})