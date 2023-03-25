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
  async getTicketsTestIdDb({dispatch, commit}, {id, page = null, limit = 10 }){
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/test/${id}/ticket?limit=${limit}`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      if (page) {config.url = config.url + `?page=${page}`}
      await axios(config)
        .then(({data})=>{
          console.log("getTicketsTestIdDb - ",  data.question)
          commit("SET_TICKETS", data.ticket);
          dispatch("setPagination", data.pagination);
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('getTicketsTestIdDb', {id, page, limit})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  
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