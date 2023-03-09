import { 
  SET_PAGINATION, 
 } from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  pagination:[],
  activePage: 1,
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
}

const mutations = {
 [SET_PAGINATION] (state, pagination) {
  let  pagin = []
  console.log("pagination -", pagination)
  if (pagination) {
    if (pagination.error) {
      state.pagination = []
      return
    }
    if ( 10 > pagination.totalPages ){
      const len =  pagination.totalPages
      pagin = Array.from({length: len}).map((inp, index) => { 
        return {label: index + 1 , active: index + 1  === pagination.currentPage ? 
          true : false,
        }})
    } else {
      const len = 10
      pagin = Array.from({length: len}).map((inp, index) => { 
        return {label: pagination.currentPage + index , active: index === 0 ? 
          true : false,
        }})
    }
    state.activePage = pagination.currentPage
  }
  console.log(pagin)
  
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