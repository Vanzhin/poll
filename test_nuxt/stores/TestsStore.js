import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useCategoryStore } from './CategoryStore'
export const useTestsStore = defineStore('tests', {
  state: () => ({
    parent: '',
    tests:[],
    
  }),
  getters: {
    getTests: (state) => state.tests,
  },
  actions: {
    testsToChange(categorys) {
      console.log('categorysToChange-',categorys)
      this.categorys = categorys
    },
    async getApiTests({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      loader.setIsLoaderStutus(true)
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
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)


              console.log("sections", sections.value.value)
              console.log("sections", sections.value)
              console.log("sections", sections)
              this.tests = sections.value.test
              const pagination = usePaginationStore()
              if (sections.value.parent){
                const categorys = useCategoryStore()
                categorys.setParentCategory(sections.value.parent)
              }
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