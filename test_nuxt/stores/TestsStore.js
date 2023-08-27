import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useCategoryStore } from './CategoryStore'
import { useModalStore  } from './ModalStore'

export const useTestsStore = defineStore('tests', {
  state: () => ({
    parent: '',
    tests:[],
    testActive: null,
    testTitle: '',
    testsMinTrud: null
  }),
  getters: {
    getTests: (state) => state.tests,
    getTest: (state) => state.testActive,
    getTestActive: (state) => state.testActive,
    getTestTitle: (state) => state.testTitle,
    getTestActiveTime: (state) => state.testActive ? state.testActive.time ? state.testActive.time/ 60 : 20: 20,
    getTestDescription: (state) => state.testActive ? state.testActive.description :'',
    getTestSeoDescription: (state) => state.testActive ? state.testActive.descriptionSeo :'',
    getTestCanonical: (state) => state.testActive ? state.testActive.canonical :'',
    getTestRobots: (state) => state.testActive ? state.testActive.robots || 'max-image-preview:large' :'max-image-preview:large',
    getTestsMinTrud: (state) => state.testsMinTrud,
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
    //получение списка тестов по id  категории
    async getApiTests({ page = null, parentId = null, admin = false, limit = 6 }){
           
      let url = `${urlApi}/api/category`
      
      let  params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        params: { limit }
      }
      if (parentId) { params.params.parent = parentId }
      if (page) { params.params.page = page }
      console.log(params)
      
      const sections = await setUseAsyncFetch({ url, params, token: admin })
      
      console.log("sections", sections)
      if (sections && sections.test){
        this.tests = sections.test
      }
      if (sections && sections.pagination){
        const pagination = usePaginationStore()
        pagination.paginationsAll(sections.pagination)
      }
      if (sections && sections.parent){
        const categorys = useCategoryStore()
        categorys.setParentCategory(sections.parent)
      }
          
      
    },
    
    //получение информации теста по его id
    async getTestIdDb( {id}){
      const url = `${urlApi}/api/admin/test/${id}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
        }
      }
      const test = await setUseAsyncFetch({ url, params, token: true })
      this.testActive = test 
    },
    //запрос списка тестов мин труда
    async getTestsMinTrudDb() {
      if (localStorage.getItem('testsMinTrud')){
        this.testsMinTrud = JSON.parse(localStorage.getItem('testsMinTrud'))
        return
      }
      const url = `${urlApi}/api/admin/test/mintrud`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
        }
      }
      const tests = await setUseAsyncFetch({ url, params, token: true })
      if (tests) {
        this.testsMinTrud = tests
        const parsed = JSON.stringify(tests)
        localStorage.setItem('testsMinTrud', parsed)
      }
    },
    //создание теста
    async createTestDb({questionSend}) {
      const data = new FormData(questionSend);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); 
      }
      const url = `${urlApi}/api/admin/test/create`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
        },
        body: data
      }
      const test = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      modal.setMessage( test )
    }

  }
})