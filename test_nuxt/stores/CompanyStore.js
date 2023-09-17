import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useModalStore  } from './ModalStore'

export const useCompanyStore = defineStore('companys', {
  state: () => ({
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
      return state.company ? state.company.admin.email : ''
    },
    
  },
  actions: {
    //запрос на получение списка компаний
    async getCompanyListDB({ page = null, limit = 10, filter = {}, sort = {"title": "ASC"} }) {
      let url = `${urlApi()}/api/admin/company/list?&limit=${limit}`
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
      if (page) { params.params.page = page }
      const result = await setUseAsyncFetch({ url, params, token: true })
      console.log(result)
      if (result && result.status === 200 && result.data){
        this.companyList = result.data.list
        if (result && result.data.pagination ){ 
          const pagination = usePaginationStore()
          pagination.paginationsAll(result.data.pagination)
        } 
      } else {
        const pagination = usePaginationStore()
          pagination.paginationsAll()
      }
    },
    //запрос на создание компании
    async createCompanyDB({id, conpanySend}){
      let url = `${urlApi()}/api/admin/company`
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
      let url = `${urlApi()}/api/admin/company/${id}`
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
      let url = `${urlApi()}/api/admin/company/${id}`
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
    async deleteCompanyDB({id}){
      let url = `${urlApi()}/api/admin/company/${id}`
      const params = {
        method: 'delete',
        headers: { 
          Accept: 'application/json', 
        },
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getCompanyListDB({})
    }
  }
})