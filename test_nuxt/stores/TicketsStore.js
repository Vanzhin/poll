import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'
import { useTestsStore } from './TestsStore'

export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    parent: '',
    tickets:[],
    ticketSelect: null 
    
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets.length,
    getTicketsIs: (state) => state.tickets.length > 0,
    getTicketSelect: (state) => state.ticketSelect
  },
  actions: {
    ticketsToChange(tickets) {
      console.log('ticketsToChange-',tickets)
      this.tickets = tickets
    },
    ticketSelectToChange(ticket) {
      console.log('ticketSelectToChange-',ticket)
      this.ticketSelect = ticket
    },
    async getApiTicketsTestIdNoAuthDb({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      loader.setIsLoaderStatus(true)
      let url = `${urlApi}/api/test/${parentId}`
      try {
        // this.userData = await api.post({ login, password })
        const { data: sections, pending, error } = await useFetch(() =>  url,
        {
          lazy: true,
        })
        let timerId = setInterval(() => {
          console.log('pending.value-',pending.value)
          if ( !pending.value) {
            clearInterval(timerId)
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
            loader.setIsLoaderStatus(false)
          }
        }, 200);

      } catch (error) {
        console.log(error)
      }
    }
   
  }
})