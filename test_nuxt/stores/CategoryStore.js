import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'

export const useCategoryStore = defineStore('gategory', {
  state: () => ({
    // categorys: localStorage.getItem('categorys') ?
    //   JSON.parse(localStorage.getItem('categorys')):[],
    // iter: 1,
    // parent: localStorage.getItem('categoryParent') ?
    //   JSON.parse(localStorage.getItem('categoryParent')): null,
    // categorysFooter: localStorage.getItem('categorysFooter') ?
    //   JSON.parse(localStorage.getItem('categorysFooter')): null,
    categorys:[]
  }),
  getters: {
    getCategogys: (state) => state.categorys,
  },
  actions: {
    categorysToChange(categorys) {
      console.log('categorysToChange-',categorys)
      this.categorys = categorys
    },
    async getApiCategorys({ page = null, parentId = null, admin = null, limit = 6 }){
   
      // let url = `${urlApi}/api/category?limit=6${page > 1? '&page=' + page: ''}`
      let url = `${urlApi}/api/category`
      // if (admin) { 
      //   config.headers.Authorization = `Bearer ${token}`
      // }
      let query = { limit }
      if (parentId) { query.parent = parentId }
      
      if (page) { query.page = page }
      
      console.log(query)
      try {
        // this.userData = await api.post({ login, password })
        const { data: sections, pending, error } = await useFetch(() =>  url,
        {
          
          lazy: true,
          query:query,
          
        })
        console.log("sections", sections.value.value)
        console.log("sections", sections.value)
        console.log("sections", sections)
        this.categorys = sections.value.children
        const pagination = usePaginationStore()
        pagination.paginationsAll(sections.value.pagination)
      } catch (error) {
        console.log(error)
       
      }
    }
   
  }
})