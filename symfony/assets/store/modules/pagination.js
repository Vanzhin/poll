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
    const len =  10 > pagination.totalPages ?
    pagination.totalPages : 10
    pagin = Array.from({length: len}).map((inp, index) => { 
      return {label: pagination.currentPage + index , active: index === 0 ? true : false,
        
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