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
    getTestQuestionCount: (state) => state.testActive ? state.testActive.questionCount : "",
    getTestSectionCount: (state) => state.testActive ? state.testActive.sectionCount : "",
    getTestTicketCount: (state) => state.testActive ? state.testActive.ticketCount : "",
    getTestTitleActive: (state) => state.testActive ? state.testActive.title : '',
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
    async getApiTestsIdDb({ page = null, parentId = null, admin = false, limit = 6, loading = true }){
      let url = `${urlApi()}/api/category`
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
      const sections = await setUseAsyncFetch({ url, params, token: admin, loading })
      console.log("sections", sections)
      if (sections && sections.test){
        this.tests = sections.test
      }
      const pagination = usePaginationStore()
      pagination.paginations = []
      if (sections && sections.pagination){
        pagination.paginationsAll(sections.pagination)
      } 
      if (sections && sections.parent){
        const categorys = useCategoryStore()
        categorys.setParentCategory(sections.parent)
      }
    },
    
    //получение информации теста по его id
    async getTestIdDb( {id}){
      const url = `${urlApi()}/api/admin/test/${id}`
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
      const url = `${urlApi()}/api/admin/test/mintrud`
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
      // for(let [name, value] of data) {
      //   console.dir(`${name} = ${value}`); 
      // }
      const url = `${urlApi()}/api/admin/test/create`
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
    },
    //удаление теста по id
    async deleteTestDb({id, parentId, page, type}){
      
      const url= `${urlApi()}/api/admin/test/${id}/delete`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        }
      }
      const test = await setUseAsyncFetch({ url, params, token: true })
      this.getApiTestsIdDb({ page, parentId})
      const modal = useModalStore()
      modal.setMessage( test )
    },
    //редактирование теста
    async editTestDb({questionSend, id}) {
      const data = new FormData(questionSend);
      for(let [name, value] of data) {
        console.dir(`${name} = ${value}`); 
      }
      const url = `${urlApi()}/api/admin/test/${id}/edit`
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
    },
    //получение списка всех тестов 
    async getApiAllTestsDb({ page = null, limit = 10, loading = true }){
      let url = `${urlApi()}/api/admin/test?limit=${limit}`
      let  params = {
        method: 'GET',
        headers: { 
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        params: { limit }
      }
      if (page) { params.params.page = page }

      const result = await setUseAsyncFetch({ url, params, token: true, loading })
     
      console.log("result", result)
      if (result && result.test){
        this.tests = result.test
      }
      const pagination = usePaginationStore()
      pagination.paginations = []
      if (result && result.pagination){
        pagination.paginationsAll(result.pagination)
      } 
      
    },
  }
})