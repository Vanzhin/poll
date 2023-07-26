import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: null,
    refresh_token: null,
    isAutchUser: localStorage.getItem('token') ? true : false, 
    role: localStorage.getItem('token') ?
      JSON.parse(atob(JSON.parse(localStorage.getItem('token')).token.split('.')[1])).roles[0]: "",
  }),
  getters: {
    getTickets: (state) => state.tickets,
    getCountTickets: (state) => state.tickets.length,
    
  },
  actions: {
    saveTicketModeTitle(title) {
      console.log('saveTicketModeTitle-',title)
      this.ticketModeTitle = title
    },
 
   
   
  }
})