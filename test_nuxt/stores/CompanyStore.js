import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useModalStore  } from './ModalStore'

export const useCompanyStore = defineStore('company', {
  state: () => ({
    urlApi: useRuntimeConfig().public.urlApi,
    companyList: [],
    company: null, 
    companyAdmin: null
  }),
  getters: {
    getCompanyList:(state) => {
      return state.companyList
    },
    getCompany:(state) => {
      return state.company
    },
    getUserListCompany:(state) => {
      return state.company ? state.company.users : ''
    },
    getAdminCompany:(state) => {
      return state.companyAdmin ? state.companyAdmin : ''
    },
    
  },
  actions: {
    //запрос на получение списка компаний
    async getCompanyListDB({ page = 1, limit = 10, filter = {}, sort = {"title": "ASC"} }) {
      let url = `${this.urlApi}/api/admin/company/list?page=${page}&limit=${limit}`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
        },
        body: {
          "filter": filter,
          "sort": sort
        }
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      this.companyList = result.data.list
      if (result && result.data.pagination ){ 
        const pagination = usePaginationStore()
        pagination.paginationsAll(result.data.pagination)
      } 
    },
    //запрос на создание компании
    async createCompanyDB({id, conpanySend}){
      let url = `${this.urlApi}/api/admin/company`
      const data = new FormData(conpanySend);
      let dataJSON = {}
      for(let [name, value] of data) {
        // console.dir(`${name} = ${value}`); 
        dataJSON[name] = value
      }
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
        },
        body: dataJSON
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getCompanyListDB({})
    },
     //запрос на получение данных компании
    async getCompanyIdDB({id}){
      let url = `${this.urlApi}/api/admin/company/${id}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      this.company = result.data
      this.companyAdmin = result.data.admin.email
    },
    // запрос на редактирование данных компании
    async editCompanyDB({id, conpanySend}){
      let url = `${this.urlApi}/api/admin/company/${id}`
      const data = new FormData(conpanySend);
      let dataJSON = {}
      for(let [name, value] of data) {
        // console.dir(`${name} = ${value}`); 
        dataJSON[name] = value
      }
      const params = {
        method: 'put',
        headers: { 
          Accept: 'application/json', 
        },
        body: dataJSON
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getCompanyListDB({})
    },
    // запрос на удаление компании
    async deleteCompanyDb({id}){
      const token = await dispatch("getAutchUserTokenAction")
      let url = `${this.urlApi}/api/admin/company/${id}`
      const config = {
        method: 'delete',
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result.message )}
      await this.getCompanyListDB({})
    }
  }
})