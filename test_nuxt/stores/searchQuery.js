import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useTestsStore } from './TestsStore'
export const useSearchQueryStore = defineStore('searchQuery', {
  state: () => ({
    searchResult: null,
    searchSign: false,
    searchValue: null
  }),
  getters: {
    getSearchResult: (state) => state.searchResult,
    getSearchSign: (state) => state.searchSign,
    
  },
  actions: {
    async getSearchDb({filter = '', limit = 6, page = 1, data = state.searchValue}) {
            
      this.searchValue = data 
      
      const url= `${urlApi}/api/search/test?limit=${limit}&page=${page}`
      const params = {
        method: 'post',
        maxBodyLength: Infinity,
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          "filter": {
            "title": data
          }
        })
      }
      if (filter !== ''){
        url += `&filter[title]=${filter}`
      }
      const result = await setUseAsyncFetch({ url, params })
      
      const pagination = usePaginationStore()
      pagination.paginationsAll(result.pagination)
      const test = useTestsStore()
      test.tests = result.test
      this.searchSign = true
     
    },
 
   
   
  }
})