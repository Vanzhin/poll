import { defineStore } from 'pinia'

export const useResultStore = defineStore('result', {
  state: () => ({
    parent: "",
    result:[],
    resultTicketUser: null,
    info: ""
  }),
  getters: {
    getResultTicketUser: (state) => state.resultTicketUser,
    
  },
  actions: {
    saveResultTicketUser( ticket){
      this.resultTicketUser = ticket.question
      this.info = ticket.info
   
    }
  }
})