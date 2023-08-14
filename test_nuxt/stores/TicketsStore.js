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
    getCountTickets: (state) => state.tickets.length,
    getTicketsIs: (state) => state.tickets.length > 0,
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
    ticketSelectToChange(ticket) {
      console.log('ticketSelectToChange-',ticket)
      this.ticketSelect = ticket
    },
    async getApiTicketsTestIdNoAuthDb({ page = null, parentId = null, admin = null, limit = 6 }){
      console.log('getApiTicketsTestIdNoAuthDb получаю-',parentId)
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${urlApi}/api/test/${parentId}`
      try {
        // this.userData = await api.post({ login, password })
        console.log('получаю билеты url-',url)
        const { data: sections, pending, error } = await useAsyncData(
          () => $fetch(url,
            {
              // lazy: true,
            })
        )
        console.log("sections", sections.value)
        this.tickets = sections.value.ticket
        const test = useTestsStore()
        test.testTitleSave(sections.value.title)
        test.testActiveSave({
          alias:sections.value.alias,
          title:sections.value.title,
          time:sections.value.time,
          id:sections.value.id,
          slug:sections.value.slug,
          minTrudTest:sections.value.minTrudTest,
        })
        // this.pending = pending.value
        console.log('getApiTicketsTestIdNoAuthDb получил -', this.tickets)
        loader.setIsLoaderStatus(false)
        
        
        return pending

      } catch (error) {
        console.log(error)
      }
    }
   
  }
})