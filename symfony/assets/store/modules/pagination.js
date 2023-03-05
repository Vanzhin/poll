import { 
  SET_PAGINATION, 
 } from './mutation-types.js'
import axios from 'axios';

const state = () => ({
  pagination:[],
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
}

const mutations = {
 [SET_PAGINATION] (state, pagination) {
  let  pagin = []
  console.log("pagination -", pagination)
  if (pagination) {
    pagin = Array.from({length:pagination.totalPages}).map((inp, index) => { 
      return {label: index + 1, active: index + 1 === pagination.currentPage
    }})
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