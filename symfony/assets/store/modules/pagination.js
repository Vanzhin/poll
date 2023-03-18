import { 
  SET_PAGINATION, 
 } from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  pagination:[],
  activePage: 1,
  totalItemsPage: 0,
  paginVisible: 10,
  paginLeft: 1,
  paginRight: 10,
  totalPages: 1,
  totalItem: null
})

const actions = {
  setPagination({commit}, pagination){
    console.log("pagination -", pagination)
    commit("SET_PAGINATION",pagination);
  },
};

const getters = {
  getPagination(state) {
    return state.pagination 
  },
  getActivePage(state) {
    return state.activePage 
  },
  getTotalItemsPage(state) {
    return state.totalItemsPage 
  },
  getTotalPage(state) {
    return state.totalPages 
  },
  getTotalItem(state) {
    return state.totalItem 
  },
}

const mutations = {
  [SET_PAGINATION] (state, pagination) {
    let  pagin = []
    if (pagination) {
      if (pagination.error) {
        state.pagination = []
        state.activePage = 1
        return
      }
      state.totalItemsPage = pagination.limit
      state.activePage = pagination.currentPage
      state.totalPages = pagination.totalPages
      state.totalItem = pagination.totalItem
      if (pagination.totalPages <= state.paginVisible){
        const len =  pagination.totalPages
        state.paginRight = pagination.totalPages
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: index + 1 , active: index + 1  === state.activePage ? 
            true : false,
          }})
      } else {
        if (state.activePage === 1 ) {state.paginRight = state.paginVisible }
        if ( state.paginRight < state.activePage ) { 
          if ( (state.activePage + state.paginVisible - 1) < state.totalPages){
            state.paginRight = state.activePage + state.paginVisible - 1
          } else {
            state.paginRight = state.totalPages
          }
          state.paginLeft = state.paginRight - (state.paginVisible - 1)
        } else if (state.paginLeft > state.activePage ) {
          if ( (state.activePage - state.paginVisible + 1) > 0){
            state.paginLeft = state.activePage - (state.paginVisible - 1)
          } else {
            state.paginLeft = 1
          }
          state.paginRight = state.paginLeft + state.paginVisible - 1
        } 
        const len = state.paginVisible
        pagin = Array.from({length: len}).map((inp, index) => { 
          return {label: state.paginLeft + index , active: state.paginLeft + index === state.activePage ? 
            true : false,
        }})
      }
    }
    
    state.pagination = pagin
  },
}

export default {
  namespaced: false,
  state,
  actions,
  getters,
  mutations
}