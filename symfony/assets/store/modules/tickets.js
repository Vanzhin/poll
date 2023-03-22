import { 
  SET_TICKET_TITLE,
  SET_TICKETS
 } from './mutation-types.js'
 import axios from 'axios';

const state = () => ({
  tickets: [],
  question:{},
  ticketTitle: ''
})

const actions = {
  
  getQuestion(){},
  setTickets ({dispatch, commit}, tickets) {
    commit("SET_TICKETS", tickets)
  },
  setTicketTitle ({dispatch, commit}, title) {
    commit("SET_TICKET_TITLE", title)
  }
};

const getters = {
  getTickets(state) {
    return state.tickets
  },
  getRandomTicket(state) {
    console.log(state.tickets.length)
    const num = Math.floor(Math.random() * (state.tickets.length ) )
    console.log(num)
    return state.tickets[num].id
  },
  getSelectTicket:(state)=>(id) =>{
    return state.tickets.find(ticket => {
      return +ticket.id === +id}) 
  },
  getTicketTitle(state) {
    return state.title
  },
}

const mutations = {
  GET_COURCES(state, cources) {
    state.cources = {
      active: 0, list: cources, isLoaded: true
    }
  },
  [SET_TICKETS] (state, tickets){
    state.tickets = tickets
  },
  [SET_TICKET_TITLE] (state, title){
    state.title = title
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}