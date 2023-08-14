import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useCategoryStore } from './CategoryStore'

export const useTestsStore = defineStore('tests', {
  state: () => ({
    parent: '',
    tests:[],
    testActive: null,
    testTitle:'',
  }),
  getters: {
    getTests: (state) => state.tests,
    getTestActive: (state) => state.testActive,
    getTestTitle: (state) => state.testTitle,
    getTestActiveTime: (state) => state.testActive ? state.testActive.time / 60 : 20,
    getTestDescription: (state) => state.testActive ? state.testActive.description :''
  },
  actions: {
    testsToChange(tests) {
      console.log('testsToChange-',tests)
      this.tests = tests
    },
    testActiveSave(test) {
      console.log('testActiveSave-',test)
      this.testActive = test
    },
    testTitleSave(title) {
      console.log('testTitleSave-',title)
      this.testTitle = title
    },
    async getApiTests({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
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
        const { data: sections, pending, error } = await useAsyncData(
          () => $fetch (url,
            {
              
              query:query,
            })
        )
          console.log("sections", sections.value)
          console.log("sections", sections)
          this.tests = sections.value.test
          const pagination = usePaginationStore()
          pagination.paginationsAll(sections.value.pagination)
          if (sections.value.parent){
            const categorys = useCategoryStore()
            categorys.setParentCategory(sections.value.parent)
          }
          
          loader.setIsLoaderStatus(false)
            
        

      } catch (error) {
        console.log(error)
      }
    },
    
    //получение информации теста по его id
    async getTestIdDb( {id}){
      
      const config = {
        method: 'get',
        url: `/api/admin/test/${id}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      }
    
    }
  }
})