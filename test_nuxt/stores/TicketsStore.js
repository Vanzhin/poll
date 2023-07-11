import { defineStore } from 'pinia'
import { usePaginationStore } from './PaginationStore'
import { useLoaderStore } from './Loader'


export const useTicketsStore = defineStore('tickets', {
  state: () => ({
    parent: '',
    tickets:[],
    testTitle:'',
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets.length,
    getTicketsIs: (state) => state.tickets.length > 0,
    
  },
  actions: {
    ticketsToChange(tickets) {
      console.log('ticketsToChange-',tickets)
      this.tickets = tickets
    },
    async getApiTicketsTestIdNoAuthDb({ page = null, parentId = null, admin = null, limit = 6 }){
      const loader = useLoaderStore()
      loader.setIsLoaderStutus(true)
      
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
              console.log("sections", sections)
              this.tickets = sections.value.ticket
              this.testTitle = sections.value.title
              
              loader.setIsLoaderStutus(false)
            }
          }, 200);

      } catch (error) {
        console.log(error)
      }
    }
   
  }
})