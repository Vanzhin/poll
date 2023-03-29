import { 
  SET_TICKET_TITLE,
  SET_TICKETS,
  SET_TICKET
 } from './mutation-types.js'
 import axios from 'axios';

const state = () => ({
  tickets: [],
  question:{},
  title: localStorage.getItem('ticketTitle') ?
  localStorage.getItem('ticketTitle'):"",
  ticket: localStorage.getItem('ticket') ?
  JSON.parse(localStorage.getItem('ticket')):"",
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
      if (page) {config.url = config.url + `&page=${page}`}
      await axios(config)
        .then(({data})=>{
          console.log("getTicketsTestIdDb - ",  data)
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
  async deleteTicketIdDb({dispatch, commit}, {id, testId, activePage }){
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'get',
        url: `/api/admin/ticket/${id}/delete`,
        headers: { 
          Accept: 'application/json', 
          Authorization: `Bearer ${token}`
        }
      };
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('getTicketsTestIdDb', {
            id: testId,
            // page: activePage,
          })
          
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteTicketIdDb', {id, page, limit})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async createTicket({dispatch, commit}, {id, ticket, operation}){
    console.log("id - ",  id)
    const token = await dispatch("getAutchUserTokenAction")
    try{
      const config = {
        method: 'post',
        url: `/api/admin/ticket/create`,
        headers: { 
          Accept: 'application/json', 
          'Content-Type': 'application/json', 
          Authorization: `Bearer ${token}`
        },
        data: ticket
      };
      if (operation === 'edit') {
        config.url = `/api/admin/ticket/${id}/edit`
      }
      console.log(config)
      await axios(config)
        .then(({data})=>{
          console.log(data)
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('createTicket', {id, ticket, operation})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  
  async getTicketsTestIdNoAuthDb({dispatch, commit}, {id}){
    console.log("id - ",  id)
    try{
      const config = {
        method: 'get',
        url: `/api/test/${id}`,
        headers: { 
          Accept: 'application/json', 
        }
      };
     
      await axios(config)
        .then(({data})=>{
          console.log("getTicketsTestIdNoAuthDb - ",  data)
          commit("SET_TICKETS", data.ticket);
         
        })
    } catch (e) {
      dispatch('setMessageError', e)
    }
  },
  setTickets ({dispatch, commit}, tickets) {
    commit("SET_TICKETS", tickets)
  },
  setTicketTitle ({dispatch, commit}, title) {
    commit("SET_TICKET_TITLE", title)
  },
  saveSelectTicketStore({dispatch, commit},{ticket}){
    commit("SET_TICKET", ticket)
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
    console.log(state.title)
    return state.title
  },
  getTicket(state) {
    return state.ticket
  },
}

const mutations = {
  
  [SET_TICKETS] (state, tickets){
    state.tickets = tickets
  },
  [SET_TICKET_TITLE] (state, title){
    
    localStorage.setItem('ticketTitle',title);
    state.title = title
  }, 
  [SET_TICKET] (state, ticket){
    const parsed = JSON.stringify(ticket)
    localStorage.setItem('ticket', parsed);
    state.ticket = ticket
  },
}
export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}