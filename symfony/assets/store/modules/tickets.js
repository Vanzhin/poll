import { 
  SET_TICKET_TITLE,
  SET_TICKETS,
  SET_TICKET
 } from './mutation-types.js'
 import axios from 'axios';

const state = () => ({
  tickets: localStorage.getItem('tickets') ?
  JSON.parse(localStorage.getItem('tickets')):[],
  question:{},
  ticketTitle: localStorage.getItem('ticketTitle') ?
  JSON.parse(localStorage.getItem('ticketTitle')):"",
  ticket: localStorage.getItem('ticket') ?
    JSON.parse(localStorage.getItem('ticket')):"",
})

const actions = {
  async getTicketsTestIdDb({dispatch, commit}, {id, page = null, limit = 10 }){
    
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
          if (data.ticket) {
            commit("SET_TICKETS", data.ticket);
            dispatch("setPagination", data.pagination);
          }
          
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
      
      await axios(config)
        .then(({data})=>{
         
          dispatch('getTicketsTestIdDb', {
            id: testId,
            // page: activePage,
          })
          
          dispatch('setMessage', data)
        })
    } catch (e) {
      if (e.response.data.message === "Expired JWT Token") {
        await dispatch('getAuthRefresh')
        await dispatch('deleteTicketIdDb', {id, testId, activePage})
      } else {
        dispatch('setMessageError', e)
      }
    }
  },
  async createTicket({dispatch, commit}, {id, ticket, operation}){
    
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
      
      await axios(config)
        .then(({data})=>{
          
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
  //запрос билетов для теста по его id в режиме тестирования
  async getTicketsTestIdNoAuthDb({dispatch, commit}, {id}){
   
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
          if (data.ticket) {commit("SET_TICKETS", data.ticket)}
        })
    } catch (e) {
      dispatch('setMessageError', e)
    }
  },
  getTicketsRND({state}) {
    return state.tickets
  },
  setTickets ({dispatch, commit}, tickets) {
    commit("SET_TICKETS", tickets)
  },
  setTicketInfo ({dispatch, commit}, title) {
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
    const num = Math.floor(Math.random() * (state.tickets.length ) )
    return state.tickets[num].id
  },
  getSelectTicket:(state)=>(id) =>{
    return state.tickets.find(ticket => {
      return +ticket.id === +id}) 
  },
  getTicketTitle(state) {
    return state.ticketTitle
  },
  getTicket(state) {
    return state.ticket
  },
}

const mutations = {
  
  [SET_TICKETS] (state, tickets){
    const parsed = JSON.stringify(tickets)
    localStorage.setItem('tickets', parsed);
    state.tickets = tickets
  },
  [SET_TICKET_TITLE] (state, title){
    
    localStorage.setItem('ticketTitle',JSON.stringify(title));
    state.ticketTitle = title
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