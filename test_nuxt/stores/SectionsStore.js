import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useModalStore  } from './ModalStore'
export const useSectionsStore = defineStore('sections', {
  state: () => ({
    urlApi: useRuntimeConfig().public.urlApi,
    sections: [],
    section: null,
    
  }),
  getters: {
    getSections: (state) => state.sections,
    getSection: (state) => state.section,
    
  },
  actions: {
    //сохранение выбранной для редактирования секции
    setSectionActive (section){
      this.section = section
    },
    //запрос секций теста по его id
    async getSectionTestIdDb({id, page = null, limit = 10 }) {
      let url = `${urlApi()}/api/admin/section/test/${id}?limit=${limit}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      }
      if (page) {url = url + `&page=${page}`}
      const result = await setUseAsyncFetch({ url, params, token: true })
      this.sections = result.section
      if (result && result.pagination ){ 
        const pagination = usePaginationStore()
        pagination.paginationsAll(result.pagination)
      } 
    },
    //удаление секции из БД
    async deleteSectionIdDb({ id, testId, page} ){
      const url = `${this.urlApi}/api/admin/section/${id}/delete`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      };
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getSectionTestIdDb({id: testId, page})
    },
    //запрос на создание, редактирование если передается id - сохранение секуии в БД
    async createSectionDb ({ section, id = null, testId, page = null }){
      
      let url = `${this.urlApi}/api/admin/section/${id ? id + '/edit': 'create'}`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
        },
        body:  section
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getSectionTestIdDb({id: testId, page})
    },
   
  }
})