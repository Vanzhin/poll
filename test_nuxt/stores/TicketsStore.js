import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useTestsStore } from './TestsStore'

export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    parent: '',
    tickets: null,
    ticketSelect: null, 
    pending: false,
    ticketModeTitle: ""
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets ? state.tickets.length : 0,
    getTicketsIs: (state) => state.tickets ? state.tickets.length > 0 : 0,
    getTicketSelect: (state) => state.ticketSelect,
    getTicketSelectTitle: (state) => state.ticketSelect ? state.ticketSelect.title : ''
  },
  actions: {
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
      let url = `${urlApi}/api/test/${parentId}`
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
    }
   
  }
})