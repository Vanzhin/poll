import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useTestsStore } from './TestsStore'
import { useModalStore  } from './ModalStore'
export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    parent: '',
    tickets: null,
    ticketSelect: null, 
    pending: false,
    ticketModeTitle: "",
    ticket: null,
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getTicket: (state) => state.ticket,
    getCountTickets: (state) => state.tickets ? state.tickets.length : 0,
    getTicketsIs: (state) => state.tickets ? state.tickets.length > 0 : 0,
    getTicketSelect: (state) => state.ticketSelect,
    getTicketSelectTitle: (state) => state.ticketSelect ? state.ticketSelect.title : ''
  },
  actions: {
    setTicketActive(ticket) {
      console.log('setTicketActive-',ticket)
      this.ticket = ticket
    },
    saveTicketModeTitle(title) {
      console.log('saveTicketModeTitle-',title)
      this.ticketModeTitle = title
    },
    ticketsToChange(tickets) {
      console.log('ticketsToChange-',tickets)
      this.tickets = tickets
    },
    async ticketSelectToChange(ticket) {
      console.log('ticketSelectToChange-',ticket)
      this.ticketSelect = ticket
    },
    async getApiTicketsTestIdNoAuthDb({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${urlApi()}/api/test/${parentId}`
      try {
        const { data: sections, pending, error } = await useAsyncData(
          () => $fetch(url)
        )
        console.log(sections.value)
        const testRespons =  sections.value.test
        this.tickets = testRespons.ticket
        const test = useTestsStore()
        test.testTitleSave(testRespons.title)
        test.testActiveSave(testRespons)
        loader.setIsLoaderStatus(false)
        return pending
      } catch (error) {
        console.log(error)
      }
    },
    //запрос билетов теста по его id
    async getTicketsTestIdDb({id, page = null, limit = 10 }) {
      let url = `${urlApi()}/api/admin/test/${id}/ticket?limit=${limit}`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      }
      if (page) {url = url + `&page=${page}`}
      const result = await setUseAsyncFetch({ url, params, token: true })
      this.tickets = result.ticket
      if (result && result.pagination ){ 
        const pagination = usePaginationStore()
        pagination.paginationsAll(result.pagination)
      } 
    },
    //удаление билета из БД
    async deleteTicketIdDb({ id, testId, page} ){
      const url = `${urlApi()}/api/admin/ticket/${id}/delete`
      const params = {
        method: 'get',
        headers: { 
          Accept: 'application/json', 
        },
      };
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getTicketsTestIdDb({id: testId, page})
    },
    //запрос на создание, редактирование если передается id - сохранение билета в БД
    async createTicketDb ({ ticket, id = null, testId, page = null }){
      let url = `${urlApi()}/api/admin/ticket/${id ? id + '/edit': 'create'}`
      const params = {
        method: 'post',
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
        },
        body: ticket
      }
      const result = await setUseAsyncFetch({ url, params, token: true })
      const modal = useModalStore()
      if (result) { modal.setMessage( result )}
      await this.getTicketsTestIdDb({id: testId, page})
    },
  }
})