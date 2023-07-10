import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'

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
      const loader = useLoaderStore()
      // let url = `${urlApi}/api/category?limit=6${page > 1? '&page=' + page: ''}`
      let url = `${urlApi}/api/category`
      // if (admin) { 
      //   config.headers.Authorization = `Bearer ${token}`
      // }
      let query = { limit }
      if (parentId) { query.parent = parentId }
      
      if (page) { query.page = page }
      
      console.log(query)
      loader.setIsLoaderStutus(true)
      try {
        // this.userData = await api.post({ login, password })
        const { data: sections, pending, error } = await useFetch(() =>  url,
        {
          
          lazy: true,
          query:query,
          
        })
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
            
            console.log("sections", sections.value)
            this.categorys = sections.value.children
            console.log('error - ',sections.value.pagination.hasOwnProperty(error))
            const pagination = usePaginationStore()
            pagination.paginationsAll(sections.value.pagination)
            loader.setIsLoaderStutus(false)
          }
        }, 200);

        
      } catch (error) {
        console.log(error)
       
      }
    }
   
  }
})